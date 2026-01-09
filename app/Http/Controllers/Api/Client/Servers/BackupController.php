<?php

namespace App\Http\Controllers\Api\Client\Servers;

use App\Enums\ServerState;
use App\Enums\SubuserPermission;
use App\Facades\Activity;
use App\Http\Controllers\Api\Client\ClientApiController;
use App\Http\Requests\Api\Client\Servers\Backups\RenameBackupRequest;
use App\Http\Requests\Api\Client\Servers\Backups\RestoreBackupRequest;
use App\Http\Requests\Api\Client\Servers\Backups\StoreBackupRequest;
use App\Models\Backup;
use App\Models\Server;
use App\Repositories\Daemon\DaemonBackupRepository;
use App\Services\Backups\DeleteBackupService;
use App\Services\Backups\DownloadLinkService;
use App\Services\Backups\InitiateBackupService;
use App\Transformers\Api\Client\BackupTransformer;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Fractalistic\Exceptions\InvalidTransformation;
use Spatie\Fractalistic\Exceptions\NoTransformerSpecified;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

#[Group('Server - Backup')]
class BackupController extends ClientApiController
{
    public function __construct(
        private readonly DaemonBackupRepository $daemonRepository,
        private readonly DeleteBackupService $deleteBackupService,
        private readonly InitiateBackupService $initiateBackupService,
        private readonly DownloadLinkService $downloadLinkService,
    ) {
        parent::__construct();
    }

    /**
     * List backups
     *
     * Returns all the backups for a given server instance in a paginated result set.
     *
     * @return array<array-key, mixed>
     *
     * @throws AuthorizationException
     */
    public function index(Request $request, Server $server): array
    {
        if (!$request->user()->can(SubuserPermission::BackupRead, $server)) {
            throw new AuthorizationException();
        }

        $limit = min($request->query('per_page') ?? 20, 50);

        return $this->fractal->collection($server->backups()->paginate($limit))
            ->transformWith($this->getTransformer(BackupTransformer::class))
            ->addMeta([
                'backup_count' => $server->backups()->nonFailed()->count(),
            ])
            ->toArray();
    }

    /**
     * Create backup
     *
     * Starts the backup process for a server.
     *
     * @return array<array-key, mixed>
     *
     * @throws InvalidTransformation
     * @throws NoTransformerSpecified
     * @throws Throwable
     */
    public function store(StoreBackupRequest $request, Server $server): array
    {
        $action = $this->initiateBackupService
            ->setIgnoredFiles(explode(PHP_EOL, $request->input('ignored') ?? ''));

        // Only set the lock status if the user even has permission to delete backups,
        // otherwise ignore this status. This gets a little funky since it isn't clear
        // how best to allow a user to create a backup that is locked without also preventing
        // them from just filling up a server with backups that can never be deleted?
        if ($request->user()->can(SubuserPermission::BackupDelete, $server)) {
            $action->setIsLocked($request->boolean('is_locked'));
        }

        $backup = Activity::event('server:backup.start')->transaction(function ($log) use ($action, $server, $request) {
            $server->backups()->lockForUpdate();

            $backup = $action->handle($server, $request->input('name'));

            $log->subject($backup)->property([
                'name' => $backup->name,
                'locked' => $request->boolean('is_locked'),
            ]);

            return $backup;
        });

        return $this->fractal->item($backup)
            ->transformWith($this->getTransformer(BackupTransformer::class))
            ->toArray();
    }

    /**
     * Toggle lock
     *
     * Toggles the lock status of a given backup for a server.
     *
     * @return array<array-key, mixed>
     *
     * @throws Throwable
     * @throws AuthorizationException
     */
    public function toggleLock(Request $request, Server $server, Backup $backup): array
    {
        if (!$request->user()->can(SubuserPermission::BackupDelete, $server)) {
            throw new AuthorizationException();
        }

        $action = $backup->is_locked ? 'server:backup.unlock' : 'server:backup.lock';

        $backup->update(['is_locked' => !$backup->is_locked]);

        Activity::event($action)->subject($backup)->property('name', $backup->name)->log();

        return $this->fractal->item($backup)
            ->transformWith($this->getTransformer(BackupTransformer::class))
            ->toArray();
    }

    /**
     * View backup
     *
     * Returns information about a single backup.
     *
     * @return array<array-key, mixed>
     *
     * @throws AuthorizationException
     */
    public function view(Request $request, Server $server, Backup $backup): array
    {
        if (!$request->user()->can(SubuserPermission::BackupRead, $server)) {
            throw new AuthorizationException();
        }

        return $this->fractal->item($backup)
            ->transformWith($this->getTransformer(BackupTransformer::class))
            ->toArray();
    }

    /**
     * Delete backup
     *
     * Deletes a backup from the panel as well as the remote source where it is currently
     * being stored.
     *
     * @throws Throwable
     */
    public function delete(Request $request, Server $server, Backup $backup): JsonResponse
    {
        if (!$request->user()->can(SubuserPermission::BackupDelete, $server)) {
            throw new AuthorizationException();
        }

        $this->deleteBackupService->handle($backup);

        Activity::event('server:backup.delete')
            ->subject($backup)
            ->property(['name' => $backup->name, 'failed' => !$backup->is_successful])
            ->log();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Download backup
     *
     * Download the backup for a given server instance. For daemon local files, the file
     * will be streamed back through the Panel. For AWS S3 files, a signed URL will be generated
     * which the user is redirected to.
     *
     * @throws Throwable
     * @throws AuthorizationException
     */
    public function download(Request $request, Server $server, Backup $backup): JsonResponse
    {
        if (!$request->user()->can(SubuserPermission::BackupDownload, $server)) {
            throw new AuthorizationException();
        }

        if ($backup->disk !== Backup::ADAPTER_AWS_S3 && $backup->disk !== Backup::ADAPTER_DAEMON) {
            throw new BadRequestHttpException('The backup requested references an unknown disk driver type and cannot be downloaded.');
        }

        $url = $this->downloadLinkService->handle($backup, $request->user());

        Activity::event('server:backup.download')->subject($backup)->property('name', $backup->name)->log();

        return new JsonResponse([
            'object' => 'signed_url',
            'attributes' => ['url' => $url],
        ]);
    }

    /**
     * Rename backup
     *
     * Updates the name of a backup for a server instance.
     *
     * @return array<array-key, mixed>
     *
     * @throws Throwable
     * @throws AuthorizationException
     */
    public function rename(RenameBackupRequest $request, Server $server, Backup $backup): array
    {
        $oldName = $backup->name;
        $newName = $request->input('name');

        $backup->update(['name' => $newName]);

        if ($oldName !== $newName) {
            Activity::event('server:backup.rename')
                ->subject($backup)
                ->property(['old_name' => $oldName, 'new_name' => $newName])
                ->log();
        }

        return $this->fractal->item($backup)
            ->transformWith($this->getTransformer(BackupTransformer::class))
            ->toArray();
    }

    /**
     * Restore backup
     *
     * Handles restoring a backup by making a request to the daemon instance telling it
     * to begin the process of finding (or downloading) the backup and unpacking it
     * over the server files.
     *
     * If the "truncate" flag is passed through in this request then all the
     * files that currently exist on the server will be deleted before restoring.
     * Otherwise, the archive will simply be unpacked over the existing files.
     *
     * @throws Throwable
     */
    public function restore(RestoreBackupRequest $request, Server $server, Backup $backup): JsonResponse
    {
        // Cannot restore a backup unless a server is fully installed and not currently
        // processing a different backup restoration request.
        if (!is_null($server->status)) {
            throw new BadRequestHttpException('This server is not currently in a state that allows for a backup to be restored.');
        }

        if (!$backup->is_successful && is_null($backup->completed_at)) {
            throw new BadRequestHttpException('This backup cannot be restored at this time: not completed or failed.');
        }

        $log = Activity::event('server:backup.restore')
            ->subject($backup)
            ->property(['name' => $backup->name, 'truncate' => $request->input('truncate')]);

        $log->transaction(function () use ($backup, $server, $request) {
            // If the backup is for an S3 file we need to generate a unique Download link for
            // it that will allow daemon to actually access the file.
            if ($backup->disk === Backup::ADAPTER_AWS_S3) {
                $url = $this->downloadLinkService->handle($backup, $request->user());
            }

            // Update the status right away for the server so that we know not to allow certain
            // actions against it via the Panel API.
            $server->update(['status' => ServerState::RestoringBackup]);

            $this->daemonRepository->setServer($server)->restore($backup, $url ?? null, $request->input('truncate'));
        });

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
