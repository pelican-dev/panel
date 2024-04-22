<?php

namespace App\Filament\Resources\ServerResource\Pages;

use App\Filament\Resources\ServerResource;
use App\Models\Server;
use App\Services\Servers\ServerDeletionService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServer extends EditRecord
{
    protected static string $resource = ServerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Delete')
                ->successRedirectUrl($this->getResource()::getUrl('index'))
                ->color('danger')
                ->action(fn (Server $server) => resolve(ServerDeletionService::class)->handle($server))
                ->requiresConfirmation(),
        ];
    }
}
