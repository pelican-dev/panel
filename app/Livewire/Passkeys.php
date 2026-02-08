<?php

namespace App\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\View\View;
use Spatie\LaravelPasskeys\Livewire\PasskeysComponent;

final class Passkeys extends PasskeysComponent implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function confirmDelete(int $passkeyId): void
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

    public function deletePasskey(int $passkeyId): void
    {
        $this->currentUser()->passkeys()->findOrFail($passkeyId);

        parent::deletePasskey($passkeyId);

        Notification::make()
            ->title(trans('passkeys.deleted_notification_title'))
            ->success()
            ->send();
    }

    public function storePasskey(string $passkey): void
    {
        parent::storePasskey($passkey);

        Notification::make()
            ->title(trans('passkeys.created_notification_title'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('passkeys.livewire.passkeys', data: [
            'passkeys' => $this->currentUser()->passkeys()->get(),
        ]);
    }
}
