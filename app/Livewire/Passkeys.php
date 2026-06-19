<?php

namespace App\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\View\View;
use Livewire\Component;

final class Passkeys extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public string $name = '';

    public function confirmDelete(int|string $passkeyId): void
    {
        $this->mountAction('deleteAction', ['passkey' => $passkeyId]);
    }

    public function deleteAction(): Action
    {
        return Action::make('deleteAction')
            ->label(trans('passkeys.delete'))
            ->color('danger')
            ->requiresConfirmation()
            ->action(fn (array $arguments) => $this->deletePasskey((int) $arguments['passkey']));
    }

    public function deletePasskey(int|string $passkeyId): void
    {
        auth()->user()->passkeys()->findOrFail($passkeyId)->delete();

        Notification::make()
            ->title(trans('passkeys.deleted_notification_title'))
            ->success()
            ->send();
    }

    public function onPasskeyRegistered(): void
    {
        $this->name = '';

        Notification::make()
            ->title(trans('passkeys.created_notification_title'))
            ->success()
            ->send();
    }

    public function registrationFailed(string $message): void
    {
        Notification::make()
            ->title(trans('passkeys.registration_failed_notification_title'))
            ->body($message)
            ->danger()
            ->send();
    }

    public function render(): View
    {
        return view('passkeys.livewire.passkeys', data: [
            'passkeys' => auth()->user()->passkeys()->get(),
        ]);
    }
}
