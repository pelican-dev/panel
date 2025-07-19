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
                    ->columnSpanFull()
                    ->label('Schedule Name')
                    ->placeholder('A human readable identifier for this schedule.')
                    ->autocomplete(false)
                    ->required(),
                Toggle::make('only_when_online')
                    ->label('Only when Server is Online?')
                    ->hintIconTooltip('Only execute this schedule when the server is in a running state.')
                    ->hintIcon('tabler-question-mark')
                    ->inline(false)
                    ->required()
                    ->default(1),
                Toggle::make('is_active')
                    ->label('Enable Schedule?')
                    ->hintIconTooltip('This schedule will be executed automatically if enabled.')
                    ->hintIcon('tabler-question-mark')
                    ->inline(false)
                    ->hiddenOn('view')
                    ->required()
                    ->default(1),
                ToggleButtons::make('Status')
                    ->formatStateUsing(fn (Schedule $schedule) => !$schedule->is_active ? 'inactive' : ($schedule->is_processing ? 'processing' : 'active'))
                    ->options(fn (Schedule $schedule) => !$schedule->is_active ? ['inactive' => 'Inactive'] : ($schedule->is_processing ? ['processing' => 'Processing'] : ['active' => 'Active']))
                    ->colors([
                        'inactive' => 'danger',
                        'processing' => 'warning',
                        'active' => 'success',
                    ])
                    ->visibleOn('view'),
                Section::make('Cron')
                    ->description(fn (Get $get) => new HtmlString('Please keep in mind that the cron inputs below always assume UTC.<br>Next run in your timezone (' . auth()->user()->timezone . '): <b>'. Utilities::getScheduleNextRunDate($get('cron_minute'), $get('cron_hour'), $get('cron_day_of_month'), $get('cron_month'), $get('cron_day_of_week'))->timezone(auth()->user()->timezone) . '</b>'))
                    ->schema([
                        Actions::make([
                            CronPresetAction::make('hourly')
                                ->cron('0', '*', '*', '*', '*'),
                            CronPresetAction::make('daily')
                                ->cron('0', '0', '*', '*', '*'),
                            CronPresetAction::make('weekly_monday')
                                ->label('Weekly (Monday)')
                                ->cron('0', '0', '*', '*', '1'),
                            CronPresetAction::make('weekly_sunday')
                                ->label('Weekly (Sunday)')
                                ->cron('0', '0', '*', '*', '0'),
                            CronPresetAction::make('monthly')
                                ->cron('0', '0', '1', '*', '*'),
                            CronPresetAction::make('every_x_minutes')
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
                                        ->prefix('Every')
                                        ->suffix('Minutes'),
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
                                        ->prefix('Every')
                                        ->suffix('Hours'),
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
                                        ->prefix('Every')
                                        ->suffix('Days'),
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
                                        ->prefix('Every')
                                        ->suffix('Months'),
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
                                        ->prefix('Every')
                                        ->options([
                                            '1' => 'Monday',
                                            '2' => 'Tuesday',
                                            '3' => 'Wednesday',
                                            '4' => 'Thursday',
                                            '5' => 'Friday',
                                            '6' => 'Saturday',
                                            '0' => 'Sunday',
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
                                ->columnSpan([
                                    'default' => 2,
                                    'lg' => 1,
                                ])
                                ->label('Minute')
                                ->default('*/5')
                                ->required()
                                ->live(),
                            TextInput::make('cron_hour')
                                ->columnSpan([
                                    'default' => 2,
                                    'lg' => 1,
                                ])
                                ->label('Hour')
                                ->default('*')
                                ->required()
                                ->live(),
                            TextInput::make('cron_day_of_month')
                                ->columnSpan([
                                    'default' => 2,
                                    'lg' => 1,
                                ])
                                ->label('Day of Month')
                                ->default('*')
                                ->required()
                                ->live(),
                            TextInput::make('cron_month')
                                ->columnSpan([
                                    'default' => 2,
                                    'lg' => 1,
                                ])
                                ->label('Month')
                                ->default('*')
                                ->required()
                                ->live(),
                            TextInput::make('cron_day_of_week')
                                ->columnSpan([
                                    'default' => 2,
                                    'lg' => 1,
                                ])
                                ->label('Day of Week')
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
                    ->searchable(),
                TextColumn::make('cron')
                    ->state(fn (Schedule $schedule) => $schedule->cron_minute . ' ' . $schedule->cron_hour . ' ' . $schedule->cron_day_of_month . ' ' . $schedule->cron_month . ' ' . $schedule->cron_day_of_week),
                TextColumn::make('status')
                    ->state(fn (Schedule $schedule) => !$schedule->is_active ? 'Inactive' : ($schedule->is_processing ? 'Processing' : 'Active')),
                IconColumn::make('only_when_online')
                    ->boolean()
                    ->sortable(),
                DateTimeColumn::make('last_run_at')
                    ->label('Last run')
                    ->placeholder('Never')
                    ->since()
                    ->sortable(),
                DateTimeColumn::make('next_run_at')
                    ->label('Next run')
                    ->placeholder('Never')
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
                ->title('The cron data provided does not evaluate to a valid expression')
                ->danger()
                ->send();

            throw new Halt();
        }
    }
}
