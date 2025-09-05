<?php

namespace App\Filament\Server\Resources;

use App\Filament\Admin\Resources\UserResource\Pages\EditUser;
use App\Filament\Components\Tables\Columns\DateTimeColumn;
use App\Filament\Server\Resources\ActivityResource\Pages;
use App\Models\ActivityLog;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Server;
use App\Models\User;
use App\Traits\Filament\CanCustomizePages;
use App\Traits\Filament\CanCustomizeRelations;
use App\Traits\Filament\CanModifyTable;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;

class ActivityResource extends Resource
{
    use CanCustomizePages;
    use CanCustomizeRelations;
    use CanModifyTable;

    protected static ?string $model = ActivityLog::class;

    protected static ?int $navigationSort = 8;

    protected static ?string $navigationIcon = 'tabler-stack';

    public static function defaultTable(Table $table): Table
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $table
            ->paginated([25, 50])
            ->defaultPaginationPageOption(25)
            ->columns([
                TextColumn::make('event')
                    ->label(trans('server/activity.event'))
                    ->html()
                    ->description(fn ($state) => $state)
                    ->icon(fn (ActivityLog $activityLog) => $activityLog->getIcon())
                    ->formatStateUsing(fn (ActivityLog $activityLog) => $activityLog->getLabel()),
                TextColumn::make('user')
                    ->label(trans('server/activity.user'))
                    ->state(function (ActivityLog $activityLog) use ($server) {
                        if (!$activityLog->actor instanceof User) {
                            return $activityLog->actor_id === null ? trans('server/activity.system') : trans('server/activity.deleted_user');
                        }

                        $user = $activityLog->actor->username;

                        // Only show the email if the actor is the server owner/ a subuser or if the viewing user is an admin
                        if (auth()->user()->isAdmin() || $server->owner_id === $activityLog->actor->id || $server->subusers->where('user_id', $activityLog->actor->id)->first()) {
                            $user .= " ({$activityLog->actor->email})";
                        }

                        return $user;
                    })
                    ->tooltip(fn (ActivityLog $activityLog) => auth()->user()->can('seeIps activityLog') ? $activityLog->ip : '')
                    ->url(fn (ActivityLog $activityLog) => $activityLog->actor instanceof User && auth()->user()->can('update', $activityLog->actor) ? EditUser::getUrl(['record' => $activityLog->actor], panel: 'admin') : '')
                    ->grow(false),
                DateTimeColumn::make('timestamp')
                    ->label(trans('server/activity.timestamp'))
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
                            ->label(trans('server/activity.event'))
                            ->content(fn (ActivityLog $activityLog) => new HtmlString($activityLog->getLabel())),
                        TextInput::make('user')
                            ->label(trans('server/activity.user'))
                            ->formatStateUsing(function (ActivityLog $activityLog) use ($server) {
                                if (!$activityLog->actor instanceof User) {
                                    return $activityLog->actor_id === null ? trans('server/activity.system') : trans('server/activity.deleted_user');
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
                                    ->visible(fn (ActivityLog $activityLog) => $activityLog->actor instanceof User && auth()->user()->can('update', $activityLog->actor))
                                    ->url(fn (ActivityLog $activityLog) => EditUser::getUrl(['record' => $activityLog->actor], panel: 'admin'))
                            ),
                        DateTimePicker::make('timestamp')
                            ->label(trans('server/activity.timestamp')),
                        KeyValue::make('properties')
                            ->label(trans('server/activity.metadata'))
                            ->formatStateUsing(fn ($state) => Arr::dot($state)),
                    ]),
            ])
            ->filters([
                SelectFilter::make('event')
                    ->options(fn (Table $table) => $table->getQuery()->pluck('event', 'event')->unique()->sort())
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return ActivityLog::whereHas('subjects', fn (Builder $query) => $query->where('subject_id', $server->id)->where('subject_type', $server->getMorphClass()))
            ->whereNotIn('activity_logs.event', ActivityLog::DISABLED_EVENTS)
            ->when(config('activity.hide_admin_activity'), function (Builder $builder) use ($server) {
                // We could do this with a query and a lot of joins, but that gets pretty
                // painful so for now we'll execute a simpler query.
                $subusers = $server->subusers()->pluck('user_id')->merge([$server->owner_id]);
                $rootAdmins = Role::getRootAdmin()->users()->pluck('id');

                $builder->select('activity_logs.*')
                    ->leftJoin('users', function (JoinClause $join) {
                        $join->on('users.id', 'activity_logs.actor_id')
                            ->where('activity_logs.actor_type', (new User())->getMorphClass());
                    })
                    ->where(function (Builder $builder) use ($subusers, $rootAdmins) {
                        $builder->whereNull('users.id')
                            ->orWhereNotIn('users.id', $rootAdmins)
                            ->orWhereIn('users.id', $subusers);
                    });
            });
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can(Permission::ACTION_ACTIVITY_READ, Filament::getTenant());
    }

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return trans('server/activity.title');
    }
}
