<?php

namespace App\Filament\Admin\Resources\ApiKeyResource\Pages;

use App\Filament\Admin\Resources\ApiKeyResource;
use App\Filament\Components\Tables\Columns\DateTimeColumn;
use App\Models\ApiKey;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ListApiKeys extends ListRecords
{
    protected static string $resource = ApiKeyResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->modifyQueryUsing(fn ($query) => $query->where('key_type', ApiKey::TYPE_APPLICATION))
            ->columns([
                TextColumn::make('key')
                    ->label(trans('admin/apikey.table.key'))
                    ->copyable()
                    ->icon('tabler-clipboard-text')
                    ->state(fn (ApiKey $key) => $key->identifier . $key->token),

                TextColumn::make('memo')
                    ->label(trans('admin/apikey.table.description'))
                    ->wrap()
                    ->limit(50),

                TextColumn::make('identifier')
                    ->hidden()
                    ->searchable(),

                DateTimeColumn::make('last_used_at')
                    ->label(trans('admin/apikey.table.last_used'))
                    ->placeholder(trans('admin/apikey.table.never_used'))
                    ->sortable(),

                DateTimeColumn::make('created_at')
                    ->label(trans('admin/apikey.table.created'))
                    ->sortable(),

                TextColumn::make('user.username')
                    ->label(trans('admin/apikey.table.created_by'))
                    ->url(fn (ApiKey $apiKey): string => route('filament.admin.resources.users.edit', ['record' => $apiKey->user])),
            ])
            ->actions([
                DeleteAction::make(),
            ])
            ->emptyStateIcon('tabler-key')
            ->emptyStateDescription('')
            ->emptyStateHeading(trans('admin/apikey.empty_table'))
            ->emptyStateActions([
                CreateAction::make('create')
                    ->label(trans('admin/apikey.create_action', ['action' => trans('filament-actions::create.single.modal.actions.create.label')]))
                    ->button(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(trans('admin/apikey.create_action', ['action' => trans('filament-actions::create.single.modal.actions.create.label')]))
                ->hidden(fn () => ApiKey::where('key_type', ApiKey::TYPE_APPLICATION)->count() <= 0),
        ];
    }
}
