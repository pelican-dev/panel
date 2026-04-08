<?php

namespace App\Extensions\BackupAdapter\Schemas;

use App\Models\Backup;
use App\Models\User;
use App\Repositories\Daemon\DaemonBackupRepository;
use App\Services\Nodes\NodeJWTService;
use Carbon\CarbonImmutable;
use Exception;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Illuminate\Http\Response;

final class WingsBackupSchema extends BackupAdapterSchema
{
    public function __construct(private readonly DaemonBackupRepository $repository, private readonly NodeJWTService $jwtService) {}

    public function getId(): string
    {
        return 'wings';
    }

    public function createBackup(Backup $backup): void
    {
        $this->repository->setServer($backup->server)->create($backup);
    }

    /** @throws Exception */
    public function deleteBackup(Backup $backup): void
    {
        try {
            $this->repository->setServer($backup->server)->delete($backup);
        } catch (Exception $exception) {
            // Don't fail the request if the Daemon responds with a 404, just assume the backup
            // doesn't actually exist and remove its reference from the Panel as well.
            if ($exception->getCode() !== Response::HTTP_NOT_FOUND) {
                throw $exception;
            }
        }
    }

    public function getDownloadLink(Backup $backup, User $user): string
    {
        $token = $this->jwtService
            ->setExpiresAt(CarbonImmutable::now()->addMinutes(15))
            ->setUser($user)
            ->setClaims([
                'backup_uuid' => $backup->uuid,
                'server_uuid' => $backup->server->uuid,
            ])
            ->handle($backup->server->node, $user->id . $backup->server->uuid);

        return $backup->server->node->getConnectionAddress() . '/download/backup?token=' . $token->toString();
    }

    /** @return Component[] */
    public function getConfigurationForm(): array
    {
        return [
            TextEntry::make(trans('admin/backuphost.no_configuration')),
        ];
    }
}
