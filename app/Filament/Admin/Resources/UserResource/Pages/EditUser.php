<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use App\Services\Users\UserUpdateService;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    private UserUpdateService $service;

    public function boot(UserUpdateService $service): void
    {
        $this->service = $service;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label(fn (User $user) => auth()->user()->id === $user->id ? 'Can\'t Delete Yourself' : ($user->servers()->count() > 0 ? 'User Has Servers' : 'Delete'))
                ->disabled(fn (User $user) => auth()->user()->id === $user->id || $user->servers()->count() > 0),
            $this->getSaveFormAction()->formId('form'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (!$record instanceof User) {
            return $record;
        }

        return $this->service->handle($record, $data);
    }
}
