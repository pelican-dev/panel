<?php

namespace App\Filament\Resources\ApiKeyResource\Pages;

use App\Filament\Resources\ApiKeyResource;
use App\Models\ApiKey;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;

class ListApiKeys extends ListRecords
{
    protected static string $resource = ApiKeyResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->searchable(false)
            ->columns([
                Tables\Columns\TextColumn::make('user.username')
                    ->searchable()
                    ->hidden()
                    ->sortable(),

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
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Keys'),
            'application' => Tab::make('Application Keys')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('key_type', ApiKey::TYPE_APPLICATION)
                ),
            'account' => Tab::make('Account Keys')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('key_type', ApiKey::TYPE_ACCOUNT)
                ),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return 'application';
    }
}
