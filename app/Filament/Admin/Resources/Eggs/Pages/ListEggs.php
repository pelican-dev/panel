<?php

namespace App\Filament\Admin\Resources\Eggs\Pages;

use App\Enums\TablerIcon;
use App\Filament\Admin\Resources\Eggs\EggResource;
use App\Filament\Components\Actions\ExportEggAction;
use App\Filament\Components\Actions\ImportEggAction;
use App\Filament\Components\Actions\UpdateEggAction;
use App\Filament\Components\Actions\UpdateEggBulkAction;
use App\Filament\Components\Tables\Filters\TagsFilter;
use App\Models\Egg;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Exception;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ListEggs extends ListRecords
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = EggResource::class;

    /**
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->searchable(true)
            ->defaultPaginationPageOption(25)
            ->columns([
                TextColumn::make('id')
                    ->label('Id')
                    ->hidden(),
                ImageColumn::make('image')
                    ->label('')
                    ->alignCenter()
                    ->circular()
                    ->getStateUsing(fn ($record) => $record->image
                        ? $record->image
                        : 'data:image/svg+xml;base64,' . base64_encode(file_get_contents(public_path('pelican.svg')))),
                TextColumn::make('name')
                    ->label(trans('admin/egg.name'))
                    ->description(fn ($record): ?string => (strlen($record->description) > 120) ? substr($record->description, 0, 120).'...' : $record->description)
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('servers_count')
                    ->counts('servers')
                    ->label(trans('admin/egg.servers')),
            ])
            ->recordActions([
                EditAction::make()
                    ->tooltip(trans('filament-actions::edit.single.label')),
                ExportEggAction::make()
                    ->tooltip(trans('filament-actions::export.modal.actions.export.label')),
                UpdateEggAction::make()
                    ->tooltip(trans_choice('admin/egg.update', 1)),
                ReplicateAction::make()
                    ->tooltip(trans('filament-actions::replicate.single.label'))
                    ->modal(false)
                    ->excludeAttributes(['author', 'uuid', 'update_url', 'servers_count', 'created_at', 'updated_at'])
                    ->beforeReplicaSaved(function (Egg $replica) {
                        $replica->author = user()?->email;
                        $replica->name .= ' Copy';
                        $replica->uuid = Str::uuid()->toString();
                    })
                    ->after(fn (Egg $record, Egg $replica) => $record->variables->each(fn ($variable) => $variable->replicate()->fill(['egg_id' => $replica->id])->save()))
                    ->successRedirectUrl(fn (Egg $replica) => EditEgg::getUrl(['record' => $replica])),
            ])
            ->toolbarActions([
                ImportEggAction::make()
                    ->multiple(),
                CreateAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function (Collection &$records) {
                            $eggsWithServers = $records->filter(fn (Egg $egg) => $egg->servers_count > 0);

                            if ($eggsWithServers->isNotEmpty()) {
                                $eggNames = $eggsWithServers->map(fn (Egg $egg) => sprintf('%s (%d server%s)', $egg->name, $egg->servers_count, $egg->servers_count > 1 ? 's' : ''))
                                    ->join(', ');
                                Notification::make()
                                    ->danger()
                                    ->title(trans('admin/egg.cannot_delete', ['count' => $eggsWithServers->count()]))
                                    ->body(trans('admin/egg.eggs_have_servers', ['eggs' => $eggNames]))
                                    ->send();
                            }

                            $records = $records->filter(fn (Egg $egg) => $egg->servers_count <= 0);

                            if ($records->isEmpty()) {
                                $this->halt();
                            }
                        }),
                    UpdateEggBulkAction::make()
                        ->before(function (Collection &$records) {
                            $eggsWithoutUpdateUrl = $records->filter(fn (Egg $egg) => $egg->update_url === null);

                            if ($eggsWithoutUpdateUrl->isNotEmpty()) {
                                $eggNames = $eggsWithoutUpdateUrl->pluck('name')->join(', ');

                                Notification::make()
                                    ->warning()
                                    ->title(trans('admin/egg.cannot_update', ['count' => $eggsWithoutUpdateUrl->count()]))
                                    ->body(trans('admin/egg.no_update_url', ['eggs' => $eggNames]))
                                    ->send();
                            }

                            $records = $records->filter(fn (Egg $egg) => $egg->update_url !== null);

                            if ($records->isEmpty()) {
                                $this->halt();
                            }
                        }),
                ]),
            ])
            ->emptyStateIcon(TablerIcon::Eggs)
            ->emptyStateDescription('')
            ->emptyStateHeading(trans('admin/egg.no_eggs'))
            ->filters([
                TagsFilter::make()
                    ->model(Egg::class),
            ]);
    }
}
