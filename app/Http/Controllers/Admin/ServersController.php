<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use App\Models\Mount;
use App\Models\Server;
use App\Models\Database;
use App\Models\MountServer;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use App\Exceptions\DisplayException;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Services\Servers\SuspensionService;
use App\Services\Servers\ServerDeletionService;
use App\Services\Servers\ReinstallServerService;
use App\Exceptions\Model\DataValidationException;
use App\Repositories\Daemon\DaemonServerRepository;
use App\Services\Servers\BuildModificationService;
use App\Services\Databases\DatabasePasswordService;
use App\Services\Servers\DetailsModificationService;
use App\Services\Servers\StartupModificationService;
use App\Services\Databases\DatabaseManagementService;
use App\Services\Servers\ServerConfigurationStructureService;
use App\Http\Requests\Admin\Servers\Databases\StoreServerDatabaseRequest;

class ServersController extends Controller
{
    /**
     * ServersController constructor.
     */
    public function __construct(
        protected AlertsMessageBag $alert,
        protected BuildModificationService $buildModificationService,
        protected DaemonServerRepository $daemonServerRepository,
        protected DatabaseManagementService $databaseManagementService,
        protected DatabasePasswordService $databasePasswordService,
        protected ServerDeletionService $deletionService,
        protected DetailsModificationService $detailsModificationService,
        protected ReinstallServerService $reinstallService,
        protected ServerConfigurationStructureService $serverConfigurationStructureService,
        protected StartupModificationService $startupModificationService,
        protected SuspensionService $suspensionService
    ) {
    }

    /**
     * Update the details for a server.
     *
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function setDetails(Request $request, Server $server): RedirectResponse
    {
        $this->detailsModificationService->handle($server, $request->only([
            'owner_id', 'external_id', 'name', 'description',
        ]));

        $this->alert->success(trans('admin/server.alerts.details_updated'))->flash();

        return redirect()->route('admin.servers.view.details', $server->id);
    }

    /**
     * Toggles the installation status for a server.
     *
     * @throws \App\Exceptions\DisplayException
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function toggleInstall(Server $server): RedirectResponse
    {
        if ($server->status === Server::STATUS_INSTALL_FAILED) {
            throw new DisplayException(trans('admin/server.exceptions.marked_as_failed'));
        }

        $server->status = $server->isInstalled() ? Server::STATUS_INSTALLING : null;
        $server->save();

        $this->alert->success(trans('admin/server.alerts.install_toggled'))->flash();

        return redirect()->route('admin.servers.view.manage', $server->id);
    }

    /**
     * Reinstalls the server with the currently assigned service.
     *
     * @throws \App\Exceptions\DisplayException
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function reinstallServer(Server $server): RedirectResponse
    {
        $this->reinstallService->handle($server);
        $this->alert->success(trans('admin/server.alerts.server_reinstalled'))->flash();

        return redirect()->route('admin.servers.view.manage', $server->id);
    }

    /**
     * Manage the suspension status for a server.
     *
     * @throws \App\Exceptions\DisplayException
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function manageSuspension(Request $request, Server $server): RedirectResponse
    {
        $this->suspensionService->toggle($server, $request->input('action'));
        $this->alert->success(trans('admin/server.alerts.suspension_toggled', [
            'status' => $request->input('action') . 'ed',
        ]))->flash();

        return redirect()->route('admin.servers.view.manage', $server->id);
    }

    /**
     * Update the build configuration for a server.
     *
     * @throws \App\Exceptions\DisplayException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateBuild(Request $request, Server $server): RedirectResponse
    {
        try {
            $this->buildModificationService->handle($server, $request->only([
                'allocation_id', 'add_allocations', 'remove_allocations',
                'memory', 'swap', 'io', 'cpu', 'threads', 'disk',
                'database_limit', 'allocation_limit', 'backup_limit', 'oom_disabled',
            ]));
        } catch (DataValidationException $exception) {
            throw new ValidationException($exception->getValidator());
        }

        $this->alert->success(trans('admin/server.alerts.build_updated'))->flash();

        return redirect()->route('admin.servers.view.build', $server->id);
    }

    /**
     * Start the server deletion process.
     *
     * @throws \App\Exceptions\DisplayException
     * @throws \Throwable
     */
    public function delete(Request $request, Server $server): RedirectResponse
    {
        $this->deletionService->withForce($request->filled('force_delete'))->handle($server);
        $this->alert->success(trans('admin/server.alerts.server_deleted'))->flash();

        return redirect()->route('admin.servers');
    }

    /**
     * Update the startup command as well as variables.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveStartup(Request $request, Server $server): RedirectResponse
    {
        $data = $request->except('_token');
        if (!empty($data['custom_docker_image'])) {
            $data['docker_image'] = $data['custom_docker_image'];
            unset($data['custom_docker_image']);
        }

        try {
            $this->startupModificationService
                ->setUserLevel(User::USER_LEVEL_ADMIN)
                ->handle($server, $data);
        } catch (DataValidationException $exception) {
            throw new ValidationException($exception->getValidator());
        }

        $this->alert->success(trans('admin/server.alerts.startup_changed'))->flash();

        return redirect()->route('admin.servers.view.startup', $server->id);
    }

    /**
     * Creates a new database assigned to a specific server.
     *
     * @throws \Throwable
     */
    public function newDatabase(StoreServerDatabaseRequest $request, Server $server): RedirectResponse
    {
        $this->databaseManagementService->create($server, [
            'database' => DatabaseManagementService::generateUniqueDatabaseName($request->input('database'), $server->id),
            'remote' => $request->input('remote'),
            'database_host_id' => $request->input('database_host_id'),
            'max_connections' => $request->input('max_connections'),
        ]);

        return redirect()->route('admin.servers.view.database', $server->id)->withInput();
    }

    /**
     * Resets the database password for a specific database on this server.
     *
     * @throws \Throwable
     */
    public function resetDatabasePassword(Request $request, Server $server): Response
    {
        /** @var \App\Models\Database $database */
        $database = $server->databases()->findOrFail($request->input('database'));

        $this->databasePasswordService->handle($database);

        return response('', 204);
    }

    /**
     * Deletes a database from a server.
     *
     * @throws \Exception
     */
    public function deleteDatabase(Server $server, Database $database): Response
    {
        $this->databaseManagementService->delete($database);

        return response('', 204);
    }

    /**
     * Add a mount to a server.
     *
     * @throws \Throwable
     */
    public function addMount(Request $request, Server $server): RedirectResponse
    {
        $mountServer = (new MountServer())->forceFill([
            'mount_id' => $request->input('mount_id'),
            'server_id' => $server->id,
        ]);

        $mountServer->saveOrFail();

        $this->alert->success('Mount was added successfully.')->flash();

        return redirect()->route('admin.servers.view.mounts', $server->id);
    }

    /**
     * Remove a mount from a server.
     */
    public function deleteMount(Server $server, Mount $mount): RedirectResponse
    {
        MountServer::where('mount_id', $mount->id)->where('server_id', $server->id)->delete();

        $this->alert->success('Mount was removed successfully.')->flash();

        return redirect()->route('admin.servers.view.mounts', $server->id);
    }
}
