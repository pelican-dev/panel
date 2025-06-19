<?php

namespace App\Filament\Admin\Resources\EggResource\Pages;

use App\Filament\Admin\Resources\EggResource;
use App\Filament\Components\Actions\ImportEggAction as ImportEggHeaderAction;
use App\Filament\Components\Tables\Actions\ExportEggAction;
use App\Filament\Components\Tables\Actions\ImportEggAction;
use App\Filament\Components\Tables\Actions\UpdateEggAction;
use App\Filament\Components\Tables\Actions\UpdateEggBulkAction;
use App\Filament\Components\Tables\Filters\TagsFilter;
use App\Models\Egg;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction as CreateHeaderAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class ListEggs extends ListRecords
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = EggResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->searchable(true)
            ->defaultPaginationPageOption(25)
            ->columns([
                TextColumn::make('id')
                    ->label('Id')
                    ->hidden(),
                TextColumn::make('name')
                    ->label(trans('admin/egg.name'))
                    ->icon('tabler-egg')
                    ->description(fn ($record): ?string => (strlen($record->description) > 120) ? substr($record->description, 0, 120).'...' : $record->description)
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('servers_count')
                    ->counts('servers')
                    ->icon('tabler-server')
                    ->label(trans('admin/egg.servers')),
            ])
            ->actions([
                EditAction::make()
                    ->iconButton()
                    ->tooltip(trans('filament-actions::edit.single.label')),
                ExportEggAction::make()
                    ->iconButton()
                    ->tooltip(trans('filament-actions::export.modal.actions.export.label')),
                UpdateEggAction::make()
                    ->iconButton()
                    ->tooltip(trans('admin/egg.update')),
                ReplicateAction::make()
                    ->iconButton()
                    ->tooltip(trans('filament-actions::replicate.single.label'))
                    ->modal(false)
                    ->excludeAttributes(['author', 'uuid', 'update_url', 'servers_count', 'created_at', 'updated_at'])
                    ->beforeReplicaSaved(function (Egg $replica) {
                        $replica->author = auth()->user()->email;
                        $replica->name .= ' Copy';
                        $replica->uuid = Str::uuid()->toString();
                    })
                    ->after(fn (Egg $record, Egg $replica) => $record->variables->each(fn ($variable) => $variable->replicate()->fill(['egg_id' => $replica->id])->save()))
                    ->successRedirectUrl(fn (Egg $replica) => EditEgg::getUrl(['record' => $replica])),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make()
                    ->before(fn (DeleteBulkAction $action, Collection $records) => $action->records($records->filter(function ($egg) {
                        /** @var Egg $egg */
                        return $egg->servers_count <= 0;
                    }))),
                UpdateEggBulkAction::make()
                    ->before(fn (UpdateEggBulkAction $action, Collection $records) => $action->records($records->filter(function ($egg) {
                        /** @var Egg $egg */
                        return cache()->get("eggs.$egg->uuid.update", false);
                    }))),
            ])
            ->emptyStateIcon('tabler-eggs')
            ->emptyStateDescription('')
            ->emptyStateHeading(trans('admin/egg.no_eggs'))
            ->emptyStateActions([
                CreateAction::make(),
                ImportEggAction::make()
                    ->multiple(),
            ])
            ->filters([
                TagsFilter::make()
                    ->model(Egg::class),
            ]);
    }

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            ImportEggHeaderAction::make()
                ->multiple(),
            CreateHeaderAction::make(),
        ];
    }
}
