<?php

namespace App\Services\Backups;

use App\Exceptions\Service\Backup\TooManyBackupsException;
use App\Extensions\Backups\BackupManager;
use App\Models\Backup;
use App\Models\Server;
use App\Repositories\Daemon\DaemonBackupRepository;
use Illuminate\Database\ConnectionInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;
use Webmozart\Assert\Assert;

class InitiateBackupService
{
    /** @var string[] */
    private array $ignoredFiles;

    private bool $isLocked = false;

    /**
     * InitiateBackupService constructor.
     */
    public function __construct(
        private readonly ConnectionInterface $connection,
        private readonly DaemonBackupRepository $daemonBackupRepository,
        private readonly DeleteBackupService $deleteBackupService,
        private readonly BackupManager $backupManager
    ) {}

    /**
     * Set if the backup should be locked once it is created which will prevent
     * its deletion by users or automated system processes.
     */
    public function setIsLocked(bool $isLocked): self
    {
        $this->isLocked = $isLocked;

        return $this;
    }

    /**
     * Sets the files to be ignored by this backup.
     *
     * @param  string[]|null  $ignored
     */
    public function setIgnoredFiles(?array $ignored): self
    {
        if (is_array($ignored)) {
            foreach ($ignored as $value) {
                Assert::string($value);
            }
        }

        // Set the ignored files to be any values that are not empty in the array. Don't use
        // the PHP empty function here incase anything that is "empty" by default (0, false, etc.)
        // were passed as a file or folder name.
        $this->ignoredFiles = is_null($ignored) ? [] : array_filter($ignored, function ($value) {
            return strlen($value) > 0;
        });

        return $this;
    }

    /**
     * Initiates the backup process for a server on daemon.
     *
     * @throws Throwable
     * @throws TooManyBackupsException
     * @throws TooManyRequestsHttpException
     */
    public function handle(Server $server, ?string $name = null, bool $override = false): Backup
    {
        $limit = config('backups.throttles.limit');
        $period = config('backups.throttles.period');
        if ($period > 0) {
            $previous = $server
                ->backups()
                ->where('created_at', '>=', now()->subSeconds($period))
                ->nonFailed()
                ->withTrashed()
                ->get();

            if ($previous->count() >= $limit) {
                $message = sprintf('Only %d backups may be generated within a %d second span of time.', $limit, $period);

                throw new TooManyRequestsHttpException((int) now()->diffInSeconds($previous->last()->created_at->addSeconds((int) $period)), $message);
            }
        }

        // Check if the server has reached or exceeded its backup limit.
        // completed_at == null will cover any ongoing backups, while is_successful == true will cover any completed backups.
        $successful = $server->backups()->nonFailed();
        if (!$server->backup_limit || $successful->count() >= $server->backup_limit) {
            // Do not allow the user to continue if this server is already at its limit and can't override.
            if (!$override || $server->backup_limit <= 0) {
                throw new TooManyBackupsException($server->backup_limit);
            }

            // Get the oldest backup the server has that is not "locked" (indicating a backup that should
            // never be automatically purged). If we find a backup we will delete it and then continue with
            // this process. If no backup is found that can be used an exception is thrown.
            $oldest = $successful->where('is_locked', false)->orderBy('created_at')->first();
            if (!$oldest) {
                throw new TooManyBackupsException($server->backup_limit);
            }

            $this->deleteBackupService->handle($oldest);
        }

        return $this->connection->transaction(function () use ($server, $name) {
            /** @var Backup $backup */
            $backup = Backup::query()->create([
                'server_id' => $server->id,
                'uuid' => Uuid::uuid4()->toString(),
                'name' => trim($name) ?: sprintf('Backup at %s', now()->toDateTimeString()),
                'ignored_files' => array_values($this->ignoredFiles ?? []),
                'disk' => $this->backupManager->getDefaultAdapter(),
                'is_locked' => $this->isLocked,
            ]);

            $this->daemonBackupRepository->setServer($server)
                ->setBackupAdapter($this->backupManager->getDefaultAdapter())
                ->backup($backup);

            return $backup;
        });
    }
}
