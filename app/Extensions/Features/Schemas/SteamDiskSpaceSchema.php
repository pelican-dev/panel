<?php

namespace App\Extensions\Features\Schemas;

use App\Extensions\Features\FeatureSchemaInterface;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class SteamDiskSpaceSchema implements FeatureSchemaInterface
{
    /** @return array<string> */
    public function getListeners(): array
    {
        return [
            'steamcmd needs 250mb of free disk space to update',
            '0x202 after update job',
        ];
    }

    public function getId(): string
    {
        return 'steam_disk_space';
    }

    public function getAction(): Action
    {
        return Action::make($this->getId())
            ->requiresConfirmation()
            ->modalHeading('Out of available disk space...')
            ->modalDescription(new HtmlString(Blade::render(
                user()?->isAdmin() ? <<<'HTML'
                    <p>
                        This server has run out of available disk space and cannot complete the install or update
                        process.
                    </p>
                    <p class="mt-4">
                        Ensure the machine has enough disk space by typing{' '}
                        <code class="rounded py-1 px-2">df -h</code> on the machine hosting
                        this server. Delete files or increase the available disk space to resolve the issue.
                    </p>
                HTML
                :
                <<<'HTML'
                    <p>
                        This server has run out of available disk space and cannot complete the install or update
                        process. Please get in touch with the administrator(s) and inform them of disk space issues.
                    </p>
                HTML
            )))
            ->modalCancelActionLabel('Close')
            ->action(fn () => null);
    }
}
