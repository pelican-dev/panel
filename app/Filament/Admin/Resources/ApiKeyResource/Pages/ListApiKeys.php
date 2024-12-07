<?php

namespace App\Filament\Admin\Resources\ApiKeyResource\Pages;

use App\Filament\Admin\Resources\ApiKeyResource;
use App\Models\ApiKey;
use App\Tables\Columns\DateTimeColumn;
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
                    ->copyable()
                    ->icon('tabler-clipboard-text')
                    ->state(fn (ApiKey $key) => $key->identifier . $key->token),

                TextColumn::make('memo')
                    ->label('Description')
                    ->wrap()
                    ->limit(50),

                TextColumn::make('identifier')
                    ->hidden()
                    ->searchable(),

                DateTimeColumn::make('last_used_at')
                    ->label('Last Used')
                    ->placeholder('Not Used')
                    ->sortable(),

                DateTimeColumn::make('created_at')
                    ->label('Created')
                    ->sortable(),

                TextColumn::make('user.username')
                    ->label('Created By')
                    ->url(fn (ApiKey $apiKey): string => route('filament.admin.resources.users.edit', ['record' => $apiKey->user])),
            ])
            ->actions([
                DeleteAction::make(),
            ])
            ->emptyStateIcon('tabler-key')
            ->emptyStateDescription('')
            ->emptyStateHeading('No API Keys')
            ->emptyStateActions([
                CreateAction::make('create')
                    ->label('Create API Key')
                    ->button(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create API Key')
                ->hidden(fn () => ApiKey::where('key_type', ApiKey::TYPE_APPLICATION)->count() <= 0),
        ];
    }
}
