<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Hash;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static bool $canCreateAnother = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Forms\Components\TextInput::make('username')
                        ->alphaNum()
                        ->required()
                        ->maxLength(191),
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
                ])->columns(2),
            ]);
    }
}
