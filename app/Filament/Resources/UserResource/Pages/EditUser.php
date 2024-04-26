<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enums\ServerState;
use App\Filament\Resources\UserResource;
use App\Services\Servers\SuspensionService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\TextInput::make('username')->required()->maxLength(191),
                    Forms\Components\TextInput::make('email')->email()->required()->maxLength(191),

                    Forms\Components\TextInput::make('password')
                        ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->password(),

                    Forms\Components\ToggleButtons::make('root_admin')
                        ->label('Administrator (Root)')
                        ->options([
                            false => 'No',
                            true => 'Admin',
                        ])
                        ->colors([
                            false => 'primary',
                            true => 'danger',
                        ])
                        ->disableOptionWhen(function (string $operation, $value, User $user) {
                            if ($operation !== 'edit' || $value) {
                                return false;
                            }

                            return $user->isLastRootAdmin();
                        })
                        ->hint(fn (User $user) => $user->isLastRootAdmin() ? 'This is the last root administrator!' : '')
                        ->helperText(fn (User $user) => $user->isLastRootAdmin() ? 'You must have at least one root administrator in your system.' : '')
                        ->hintColor('warning')
                        ->inline()
                        ->required()
                        ->default(false),

                    Forms\Components\Hidden::make('skipValidation')->default(true),

                    Forms\Components\Select::make('language')
                        ->required()
                        ->hidden()
                        ->default('en')
                        ->options(fn (User $user) => $user->getAvailableLanguages()),

                ])->columns(),
            ]);
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),

            Actions\Action::make('toggleSuspend')
                ->hidden(fn (User $user) => $user->servers()->whereNot('status', ServerState::Suspended)->count() === 0)
                ->label('Suspend Servers')
                ->color('warning')
                ->action(function (User $user) {
                    foreach ($user->servers()->whereNot('status', ServerState::Suspended)->get() as $server) {
                        resolve(SuspensionService::class)->toggle($server);
                    }
                }),

            Actions\Action::make('toggleUnsuspend')
                ->hidden(fn (User $user) => $user->servers()->where('status', ServerState::Suspended)->count() === 0)
                ->label('Unsuspend Servers')
                ->color('success')
                ->action(function (User $user) {
                    foreach ($user->servers()->where('status', ServerState::Suspended)->get() as $server) {
                        resolve(SuspensionService::class)->toggle($server, SuspensionService::ACTION_UNSUSPEND);
                    }
                }),
        ];
    }
}
