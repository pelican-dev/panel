<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Services\Users\UserCreationService;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->columns([
                ImageColumn::make('picture')
                    ->visibleFrom('lg')
                    ->label('')
                    ->extraImgAttributes(['class' => 'rounded-full'])
                    ->defaultImageUrl(fn (User $user) => 'https://gravatar.com/avatar/' . md5(strtolower($user->email))),
                TextColumn::make('external_id')
                    ->searchable()
                    ->hidden(),
                TextColumn::make('uuid')
                    ->label('UUID')
                    ->hidden()
                    ->searchable(),
                TextColumn::make('username')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable()
                    ->icon('tabler-mail'),
                IconColumn::make('use_totp')
                    ->label('2FA')
                    ->visibleFrom('lg')
                    ->icon(fn (User $user) => $user->use_totp ? 'tabler-lock' : 'tabler-lock-open-off')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->icon('tabler-users-group')
                    ->placeholder('No roles'),
                TextColumn::make('servers_count')
                    ->counts('servers')
                    ->icon('tabler-server')
                    ->label('Servers'),
                TextColumn::make('subusers_count')
                    ->visibleFrom('sm')
                    ->label('Subusers')
                    ->counts('subusers')
                    ->icon('tabler-users'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->checkIfRecordIsSelectableUsing(fn (User $user) => auth()->user()->id !== $user->id && !$user->servers_count)
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize(fn () => auth()->user()->can('delete user')),
                ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make('create')
                ->label('Create User')
                ->createAnother(false)
                ->form([
                    Grid::make()
                        ->schema([
                            TextInput::make('username')
                                ->alphaNum()
                                ->required()
                                ->unique()
                                ->minLength(3)
                                ->maxLength(255),
                            TextInput::make('email')
                                ->email()
                                ->required()
                                ->unique()
                                ->maxLength(255),
                            TextInput::make('password')
                                ->hintIcon('tabler-question-mark')
                                ->hintIconTooltip('Providing a user password is optional. New user email will prompt users to create a password the first time they login.')
                                ->password(),
                            CheckboxList::make('roles')
                                ->disableOptionWhen(fn (string $value): bool => $value == Role::getRootAdmin()->id)
                                ->relationship('roles', 'name')
                                ->dehydrated()
                                ->label('Admin Roles')
                                ->columnSpanFull()
                                ->bulkToggleable(false),
                        ]),
                ])
                ->successRedirectUrl(route('filament.admin.resources.users.index'))
                ->action(function (array $data, UserCreationService $creationService) {
                    $roles = $data['roles'];
                    $roles = collect($roles)->map(fn ($role) => Role::findById($role));
                    unset($data['roles']);

                    $user = $creationService->handle($data);

                    $user->syncRoles($roles);

                    Notification::make()
                        ->title('User Created!')
                        ->success()
                        ->send();

                    return redirect()->route('filament.admin.resources.users.index');
                }),
        ];
    }
}
