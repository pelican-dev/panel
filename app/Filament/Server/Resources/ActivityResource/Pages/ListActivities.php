<?php

namespace App\Filament\Server\Resources\ActivityResource\Pages;

use App\Filament\Admin\Resources\UserResource\Pages\EditUser;
use App\Filament\Server\Resources\ActivityResource;
use App\Models\ActivityLog;
use App\Filament\Components\Tables\Columns\DateTimeColumn;
use App\Models\Server;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class ListActivities extends ListRecords
{
    protected static string $resource = ActivityResource::class;

    public function table(Table $table): Table
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $table
            ->paginated([25, 50, 100, 250])
            ->defaultPaginationPageOption(25)
            ->columns([
                TextColumn::make('event')
                    ->html()
                    ->description(fn ($state) => $state)
                    ->icon(fn (ActivityLog $activityLog) => $activityLog->getIcon())
                    ->formatStateUsing(fn (ActivityLog $activityLog) => $activityLog->getLabel()),
                TextColumn::make('user')
                    ->state(function (ActivityLog $activityLog) use ($server) {
                        if (!$activityLog->actor instanceof User) {
                            return $activityLog->actor_id === null ? 'System' : 'Deleted user';
                        }

                        $user = $activityLog->actor->username;

                        // Only show the email if the actor is the server owner/ a subuser or if the viewing user is an admin
                        if (auth()->user()->isAdmin() || $server->owner_id === $activityLog->actor->id || $server->subusers->where('user_id', $activityLog->actor->id)->first()) {
                            $user .= " ({$activityLog->actor->email})";
                        }

                        return $user;
                    })
                    ->tooltip(fn (ActivityLog $activityLog) => auth()->user()->can('seeIps activityLog') ? $activityLog->ip : '')
                    ->url(fn (ActivityLog $activityLog) => $activityLog->actor instanceof User && auth()->user()->can('update user') ? EditUser::getUrl(['record' => $activityLog->actor], panel: 'admin') : '')
                    ->grow(false),
                DateTimeColumn::make('timestamp')
                    ->since()
                    ->sortable()
                    ->grow(false),
            ])
            ->defaultSort('timestamp', 'desc')
            ->actions([
                ViewAction::make()
                    //->visible(fn (ActivityLog $activityLog) => $activityLog->hasAdditionalMetadata())
                    ->form([
                        Placeholder::make('event')
                            ->content(fn (ActivityLog $activityLog) => new HtmlString($activityLog->getLabel())),
                        TextInput::make('user')
                            ->formatStateUsing(function (ActivityLog $activityLog) use ($server) {
                                if (!$activityLog->actor instanceof User) {
                                    return $activityLog->actor_id === null ? 'System' : 'Deleted user';
                                }

                                $user = $activityLog->actor->username;

                                // Only show the email if the actor is the server owner/ a subuser or if the viewing user is an admin
                                if (auth()->user()->isAdmin() || $server->owner_id === $activityLog->actor->id || $server->subusers->where('user_id', $activityLog->actor->id)->first()) {
                                    $user .= " ({$activityLog->actor->email})";
                                }

                                if (auth()->user()->can('seeIps activityLog')) {
                                    $user .= " - $activityLog->ip";
                                }

                                return $user;
                            })
                            ->hintAction(
                                Action::make('edit')
                                    ->label(trans('filament-actions::edit.single.label'))
                                    ->icon('tabler-edit')
                                    ->visible(fn (ActivityLog $activityLog) => $activityLog->actor instanceof User && auth()->user()->can('update user'))
                                    ->url(fn (ActivityLog $activityLog) => EditUser::getUrl(['record' => $activityLog->actor], panel: 'admin'))
                            ),
                        DateTimePicker::make('timestamp'),
                        KeyValue::make('properties')
                            ->label('Metadata')
                            ->formatStateUsing(fn ($state) => collect($state)->filter(fn ($item) => !is_array($item))->all()),
                    ]),
            ])
            ->filters([
                SelectFilter::make('event')
                    ->options(fn (Table $table) => $table->getQuery()->pluck('event', 'event')->unique()->sort())
                    ->searchable()
                    ->preload(),
            ]);
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
