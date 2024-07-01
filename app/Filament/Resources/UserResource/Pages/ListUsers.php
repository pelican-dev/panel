<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use App\Services\Users\UserCreationService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->columns([
                Tables\Columns\ImageColumn::make('picture')
                    ->visibleFrom('lg')
                    ->label('')
                    ->extraImgAttributes(['class' => 'rounded-full'])
                    ->defaultImageUrl(fn (User $user) => 'https://gravatar.com/avatar/' . md5(strtolower($user->email))),
                Tables\Columns\TextColumn::make('external_id')
                    ->searchable()
                    ->hidden(),
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->hidden()
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->icon('tabler-mail'),
                Tables\Columns\IconColumn::make('root_admin')
                    ->visibleFrom('md')
                    ->label('Admin')
                    ->boolean()
                    ->trueIcon('tabler-star-filled')
                    ->falseIcon('tabler-star-off')
                    ->sortable(),
                Tables\Columns\IconColumn::make('use_totp')->label('2FA')
                    ->visibleFrom('lg')
                    ->icon(fn (User $user) => $user->use_totp ? 'tabler-lock' : 'tabler-lock-open-off')
                    ->boolean()->sortable(),
                Tables\Columns\TextColumn::make('servers_count')
                    ->counts('servers')
                    ->icon('tabler-server')
                    ->label('Servers'),
                Tables\Columns\TextColumn::make('subusers_count')
                    ->visibleFrom('sm')
                    ->label('Subusers')
                    ->counts('subusers')
                    ->icon('tabler-users'),
                // ->formatStateUsing(fn (string $state, $record): string => (string) ($record->servers_count + $record->subusers_count))
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->checkIfRecordIsSelectableUsing(fn (User $user) => auth()->user()->id !== $user->id && !$user->servers_count)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make('create')
                ->label('Create User')
                ->createAnother(false)
                ->form([
                    Forms\Components\Grid::make()
                        ->schema([
                            Forms\Components\TextInput::make('username')
                                ->alphaNum()
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->required()
                                ->unique()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('password')
                                ->hintIcon('tabler-question-mark')
                                ->hintIconTooltip('Providing a user password is optional. New user email will prompt users to create a password the first time they login.')
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
                                ->inline()
                                ->required()
                                ->default(false),
                        ]),
                ])
                ->successRedirectUrl(route('filament.admin.resources.users.index'))
                ->action(function (array $data) {
                    resolve(UserCreationService::class)->handle($data);
                    Notification::make()->title('User Created!')->success()->send();

                    return redirect()->route('filament.admin.resources.users.index');
                }),
        ];
    }
}
