<?php

namespace App\Filament\Resources\ServerResource\Pages;

use App\Filament\Resources\ServerResource;
use App\Services\Servers\ServerCreationService;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateServer extends CreateRecord
{
    protected static string $resource = ServerResource::class;
    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        /** @var ServerCreationService $service */
        $service = resolve(ServerCreationService::class);

        $server = $service->handle($data);

        return $server;
    }

//    protected function getRedirectUrl(): string
//    {
//        return $this->getResource()::getUrl('edit');
//    }
}
