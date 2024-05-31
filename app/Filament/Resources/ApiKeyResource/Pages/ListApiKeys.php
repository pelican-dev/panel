<?php

namespace App\Filament\Resources\ApiKeyResource\Pages;

use App\Filament\Resources\ApiKeyResource;
use App\Models\ApiKey;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Filament\Tables;

class ListApiKeys extends ListRecords
{
    protected static string $resource = ApiKeyResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->modifyQueryUsing(fn ($query) => $query->where('key_type', ApiKey::TYPE_APPLICATION))
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->copyable()
                    ->icon('tabler-clipboard-text')
                    ->state(fn (ApiKey $key) => $key->identifier . decrypt($key->token)),

                Tables\Columns\TextColumn::make('memo')
                    ->label('Description')
                    ->wrap()
                    ->limit(50),

                Tables\Columns\TextColumn::make('identifier')
                    ->hidden()
                    ->searchable(),

                Tables\Columns\TextColumn::make('last_used_at')
                    ->label('Last Used')
                    ->placeholder('Not Used')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.username')
                    ->label('Created By')
                    ->url(fn (ApiKey $apiKey): string => route('filament.admin.resources.users.edit', ['record' => $apiKey->user])),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
