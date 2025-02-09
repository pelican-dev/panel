<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('username')
                        ->label(trans('admin/user.username'))
                        ->required()
                        ->minLength(3)
                        ->maxLength(255),
                    TextInput::make('email')
                        ->label(trans('admin/user.email'))
                        ->email()
                        ->required()
                        ->maxLength(255),
                    TextInput::make('password')
                        ->label(trans('admin/user.password'))
                        ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->password(),
                    Hidden::make('skipValidation')
                        ->default(true),
                    CheckboxList::make('roles')
                        ->disabled(fn (User $user) => $user->id === auth()->user()->id)
                        ->disableOptionWhen(fn (string $value): bool => $value == Role::getRootAdmin()->id)
                        ->relationship('roles', 'name')
                        ->label(trans('admin/user.admin_roles'))
                        ->columnSpanFull()
                        ->bulkToggleable(false),
                ])
                    ->columns(['default' => 1, 'lg' => 3]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label(fn (User $user) => auth()->user()->id === $user->id ? trans('admin/user.self_delete') : ($user->servers()->count() > 0 ? trans('admin/user.has_servers') : trans('filament-actions::delete.single.modal.actions.delete.label')))
                ->disabled(fn (User $user) => auth()->user()->id === $user->id || $user->servers()->count() > 0),
            $this->getSaveFormAction()->formId('form'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
