<?php

namespace App\Filament\Server\Resources;

use App\Facades\Activity;
use App\Filament\Components\Forms\Actions\CronPresetAction;
use App\Filament\Components\Tables\Columns\DateTimeColumn;
use App\Filament\Server\Resources\ScheduleResource\Pages;
use App\Filament\Server\Resources\ScheduleResource\RelationManagers\TasksRelationManager;
use App\Helpers\Utilities;
use App\Models\Permission;
use App\Models\Schedule;
use App\Traits\Filament\BlockAccessInConflict;
use App\Traits\Filament\CanCustomizePages;
use App\Traits\Filament\CanCustomizeRelations;
use App\Traits\Filament\CanModifyForm;
use App\Traits\Filament\CanModifyTable;
use Carbon\Carbon;
use Exception;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Support\Exceptions\Halt;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class ScheduleResource extends Resource
{
    use BlockAccessInConflict;
    use CanCustomizePages;
    use CanCustomizeRelations;
    use CanModifyForm;
    use CanModifyTable;

    protected static ?string $model = Schedule::class;

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'tabler-clock';

    public static function canViewAny(): bool
    {
        return auth()->user()->can(Permission::ACTION_SCHEDULE_READ, Filament::getTenant());
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can(Permission::ACTION_SCHEDULE_CREATE, Filament::getTenant());
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_SCHEDULE_UPDATE, Filament::getTenant());
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_SCHEDULE_DELETE, Filament::getTenant());
    }

    public static function defaultForm(Form $form): Form
    {
        return $form
            ->columns([
                'default' => 1,
                'lg' => 2,
            ])
            ->schema([
                TextInput::make('name')
                    ->label(trans('server/schedule.name'))
                    ->columnSpanFull()
                    ->autocomplete(false)
                    ->required(),
                Toggle::make('only_when_online')
                    ->label(trans('server/schedule.only_online'))
                    ->hintIconTooltip(trans('server/schedule.only_online_hint'))
                    ->hintIcon('tabler-question-mark')
                    ->inline(false)
                    ->required()
                    ->default(1),
                Toggle::make('is_active')
                    ->label(trans('server/schedule.enabled'))
                    ->hintIconTooltip(trans('server/schedule.enabled_hint'))
                    ->hintIcon('tabler-question-mark')
                    ->inline(false)
                    ->hiddenOn('view')
                    ->required()
                    ->default(1),
                ToggleButtons::make('Status')
                    ->formatStateUsing(fn (Schedule $schedule) => !$schedule->is_active ? 'inactive' : ($schedule->is_processing ? 'processing' : 'active'))
                    ->options(fn (Schedule $schedule) => !$schedule->is_active ? ['inactive' => trans('server/schedule.inactive')] : ($schedule->is_processing ? ['processing' => trans('server/schedule.processing')] : ['active' => trans('server/schedule.active')]))
                    ->colors([
                        'inactive' => 'danger',
                        'processing' => 'warning',
                        'active' => 'success',
                    ])
                    ->visibleOn('view'),
                Section::make('Cron')
                    ->label(trans('server/schedule.cron'))
                    ->description(function (Get $get) {
                        try {
                            $nextRun = Utilities::getScheduleNextRunDate($get('cron_minute'), $get('cron_hour'), $get('cron_day_of_month'), $get('cron_month'), $get('cron_day_of_week'))->timezone(auth()->user()->timezone);
                        } catch (Exception) {
                            $nextRun = trans('server/schedule.invalid');
                        }

                        return new HtmlString(trans('server/schedule.cron_body') . '<br>' . trans('server/schedule.cron_timezone', ['timezone' => auth()->user()->timezone, 'next_run' => $nextRun]));
                    })
                    ->schema([
                        Actions::make([
                            CronPresetAction::make('hourly')
                                ->label(trans('server/schedule.time.hourly'))
                                ->cron('0', '*', '*', '*', '*'),
                            CronPresetAction::make('daily')
                                ->label(trans('server/schedule.time.daily'))
                                ->cron('0', '0', '*', '*', '*'),
                            CronPresetAction::make('weekly_monday')
                                ->label(trans('server/schedule.time.weekly_mon'))
                                ->cron('0', '0', '*', '*', '1'),
                            CronPresetAction::make('weekly_sunday')
                                ->label(trans('server/schedule.time.weekly_sun'))
                                ->cron('0', '0', '*', '*', '0'),
                            CronPresetAction::make('monthly')
                                ->label(trans('server/schedule.time.monthly'))
                                ->cron('0', '0', '1', '*', '*'),
                            CronPresetAction::make('every_x_minutes')
                                ->label(trans('server/schedule.time.every_min'))
                                ->color(fn (Get $get) => str($get('cron_minute'))->startsWith('*/')
                                                    && $get('cron_hour') == '*'
                                                    && $get('cron_day_of_month') == '*'
                                                    && $get('cron_month') == '*'
                                                    && $get('cron_day_of_week') == '*' ? 'success' : 'primary')
                                ->form([
                                    TextInput::make('x')
                                        ->label('')
                                        ->numeric()
                                        ->minValue(1)
                                        ->maxValue(60)
                                        ->prefix(trans('server/schedule.time.every'))
                                        ->suffix(trans('server/schedule.time.minutes')),
                                ])
                                ->action(function (Set $set, $data) {
                                    $set('cron_minute', '*/' . $data['x']);
                                    $set('cron_hour', '*');
                                    $set('cron_day_of_month', '*');
                                    $set('cron_month', '*');
                                    $set('cron_day_of_week', '*');
                                }),
                            CronPresetAction::make('every_x_hours')
                                ->color(fn (Get $get) => $get('cron_minute') == '0'
                                                    && str($get('cron_hour'))->startsWith('*/')
                                                    && $get('cron_day_of_month') == '*'
                                                    && $get('cron_month') == '*'
                                                    && $get('cron_day_of_week') == '*' ? 'success' : 'primary')
                                ->form([
                                    TextInput::make('x')
                                        ->label('')
                                        ->numeric()
                                        ->minValue(1)
                                        ->maxValue(24)
                                        ->prefix(trans('server/schedule.time.every'))
                                        ->suffix(trans('server/schedule.time.hours')),
                                ])
                                ->action(function (Set $set, $data) {
                                    $set('cron_minute', '0');
                                    $set('cron_hour', '*/' . $data['x']);
                                    $set('cron_day_of_month', '*');
                                    $set('cron_month', '*');
                                    $set('cron_day_of_week', '*');
                                }),
                            CronPresetAction::make('every_x_days')
                                ->color(fn (Get $get) => $get('cron_minute') == '0'
                                                    && $get('cron_hour') == '0'
                                                    && str($get('cron_day_of_month'))->startsWith('*/')
                                                    && $get('cron_month') == '*'
                                                    && $get('cron_day_of_week') == '*' ? 'success' : 'primary')
                                ->form([
                                    TextInput::make('x')
                                        ->label('')
                                        ->numeric()
                                        ->minValue(1)
                                        ->maxValue(24)
                                        ->prefix(trans('server/schedule.time.every'))
                                        ->suffix(trans('server/schedule.time.days')),
                                ])
                                ->action(function (Set $set, $data) {
                                    $set('cron_minute', '0');
                                    $set('cron_hour', '0');
                                    $set('cron_day_of_month', '*/' . $data['x']);
                                    $set('cron_month', '*');
                                    $set('cron_day_of_week', '*');
                                }),
                            CronPresetAction::make('every_x_months')
                                ->color(fn (Get $get) => $get('cron_minute') == '0'
                                                    && $get('cron_hour') == '0'
                                                    && $get('cron_day_of_month') == '1'
                                                    && str($get('cron_month'))->startsWith('*/')
                                                    && $get('cron_day_of_week') == '*' ? 'success' : 'primary')
                                ->form([
                                    TextInput::make('x')
                                        ->label('')
                                        ->numeric()
                                        ->minValue(1)
                                        ->maxValue(24)
                                        ->prefix(trans('server/schedule.time.every'))
                                        ->suffix(trans('server/schedule.time.months')),
                                ])
                                ->action(function (Set $set, $data) {
                                    $set('cron_minute', '0');
                                    $set('cron_hour', '0');
                                    $set('cron_day_of_month', '1');
                                    $set('cron_month', '*/' . $data['x']);
                                    $set('cron_day_of_week', '*');
                                }),
                            CronPresetAction::make('every_x_day_of_week')
                                ->color(fn (Get $get) => $get('cron_minute') == '0'
                                                    && $get('cron_hour') == '0'
                                                    && $get('cron_day_of_month') == '*'
                                                    && $get('cron_month') == '*'
                                                    && $get('cron_day_of_week') != '*' ? 'success' : 'primary')
                                ->form([
                                    Select::make('x')
                                        ->label('')
                                        ->prefix(trans('server/schedule.time.every'))
                                        ->options([
                                            '1' => trans('server/schedule.time.monday'),
                                            '2' => trans('server/schedule.time.tuesday'),
                                            '3' => trans('server/schedule.time.wednesday'),
                                            '4' => trans('server/schedule.time.thursday'),
                                            '5' => trans('server/schedule.time.friday'),
                                            '6' => trans('server/schedule.time.saturday'),
                                            '0' => trans('server/schedule.time.sunday'),
                                        ])
                                        ->selectablePlaceholder(false)
                                        ->native(false),
                                ])
                                ->action(function (Set $set, $data) {
                                    $set('cron_minute', '0');
                                    $set('cron_hour', '0');
                                    $set('cron_day_of_month', '*');
                                    $set('cron_month', '*');
                                    $set('cron_day_of_week', $data['x']);
                                }),
                        ])
                            ->hiddenOn('view'),
                        Group::make([
                            TextInput::make('cron_minute')
                                ->label(trans('server/schedule.time.minute'))
                                ->columnSpan([
                                    'default' => 2,
                                    'lg' => 1,
                                ])
                                ->default('*/5')
                                ->required()
                                ->live(),
                            TextInput::make('cron_hour')
                                ->label(trans('server/schedule.time.hour'))
                                ->columnSpan([
                                    'default' => 2,
                                    'lg' => 1,
                                ])
                                ->default('*')
                                ->required()
                                ->live(),
                            TextInput::make('cron_day_of_month')
                                ->label(trans('server/schedule.time.day_of_month'))
                                ->columnSpan([
                                    'default' => 2,
                                    'lg' => 1,
                                ])
                                ->default('*')
                                ->required()
                                ->live(),
                            TextInput::make('cron_month')
                                ->label(trans('server/schedule.time.month'))
                                ->columnSpan([
                                    'default' => 2,
                                    'lg' => 1,
                                ])
                                ->default('*')
                                ->required()
                                ->live(),
                            TextInput::make('cron_day_of_week')
                                ->label(trans('server/schedule.time.day_of_week'))
                                ->columnSpan([
                                    'default' => 2,
                                    'lg' => 1,
                                ])
                                ->default('*')
                                ->required()
                                ->live(),
                        ])
                            ->columns([
                                'default' => 4,
                                'lg' => 5,
                            ]),
                    ]),
            ]);
    }

    public static function defaultTable(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(trans('server/schedule.name'))
                    ->searchable(),
                TextColumn::make('cron')
                    ->label(trans('server/schedule.cron'))
                    ->state(fn (Schedule $schedule) => $schedule->cron_minute . ' ' . $schedule->cron_hour . ' ' . $schedule->cron_day_of_month . ' ' . $schedule->cron_month . ' ' . $schedule->cron_day_of_week),
                TextColumn::make('status')
                    ->label(trans('server/schedule.status'))
                    ->state(fn (Schedule $schedule) => !$schedule->is_active ? trans('server/schedule.inactive') : ($schedule->is_processing ? trans('server/schedule.processing') : trans('server/schedule.active'))),
                IconColumn::make('only_when_online')
                    ->label(trans('server/schedule.online_only'))
                    ->boolean()
                    ->sortable(),
                DateTimeColumn::make('last_run_at')
                    ->label(trans('server/schedule.last_run'))
                    ->placeholder(trans('server/schedule.never'))
                    ->since()
                    ->sortable(),
                DateTimeColumn::make('next_run_at')
                    ->label(trans('server/schedule.next_run'))
                    ->placeholder(trans('server/schedule.never'))
                    ->since()
                    ->sortable()
                    ->state(fn (Schedule $schedule) => $schedule->is_active ? $schedule->next_run_at : null),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->after(function (Schedule $schedule) {
                        Activity::event('server:schedule.delete')
                            ->subject($schedule)
                            ->property('name', $schedule->name)
                            ->log();
                    }),
            ]);
    }

    /** @return class-string<RelationManager>[] */
    public static function getDefaultRelations(): array
    {
        return [
            TasksRelationManager::class,
        ];
    }

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'view' => Pages\ViewSchedule::route('/{record}'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }

    public static function getNextRun(string $minute, string $hour, string $dayOfMonth, string $month, string $dayOfWeek): Carbon
    {
        try {
            return Utilities::getScheduleNextRunDate($minute, $hour, $dayOfMonth, $month, $dayOfWeek);
        } catch (Exception) {
            Notification::make()
                ->title(trans('server/schedule.notification_invalid_cron'))
                ->danger()
                ->send();

            throw new Halt();
        }
    }

    public static function getNavigationLabel(): string
    {
        return trans('server/schedule.title');
    }
}
