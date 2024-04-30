<?php

namespace App\Filament\Resources\ServerResource\RelationManagers;

use App\Models\Allocation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AllocationsRelationManager extends RelationManager
{
    protected static string $relationship = 'allocations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('ip')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ip')
            ->checkIfRecordIsSelectableUsing(fn (Allocation $record) => $record->id !== $this->getOwnerRecord()->allocation_id)
            // ->actions
            // ->groups
            ->columns([
                Tables\Columns\TextColumn::make('ip_alias')->label('Alias'),
                Tables\Columns\TextColumn::make('ip')->label('IP'),
                Tables\Columns\TextColumn::make('port')->label('Port'),
                Tables\Columns\IconColumn::make('primary')
                    ->icon(fn ($state) => match ($state) {
                        false => 'tabler-star',
                        true => 'tabler-star-filled',
                    })
                    ->color(fn ($state) => match ($state) {
                        false => 'gray',
                        true => 'warning',
                    })
                    ->action(fn (Allocation $allocation) => $this->getOwnerRecord()->update(['allocation_id' => $allocation->id]))
                    ->default(fn (Allocation $allocation) => $allocation->id === $this->getOwnerRecord()->allocation_id)
                    ->label('Primary'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('make-primary')
                    ->action(fn (Allocation $allocation) => $this->getOwnerRecord()->update(['allocation_id' => $allocation->id]))
                    ->label(fn (Allocation $allocation) => $allocation->id === $this->getOwnerRecord()->allocation_id ? '' : 'Make Primary'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Create Allocation'),
                //Tables\Actions\AssociateAction::make()->label('Add Allocation'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
