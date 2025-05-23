<?php

namespace App\Filament\Server\Resources;

use App\Facades\Activity;
use App\Filament\Components\Tables\Columns\DateTimeColumn;
use App\Filament\Server\Resources\ScheduleResource\Pages;
use App\Filament\Server\Resources\ScheduleResource\RelationManagers\TasksRelationManager;
use App\Helpers\Utilities;
use App\Models\Permission;
use App\Models\Schedule;
use App\Models\Server;
use Carbon\Carbon;
use Exception;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Exceptions\Halt;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'tabler-clock';

    // TODO: find better way handle server conflict state
    public static function canAccess(): bool
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        if ($server->isInConflictState()) {
            return false;
        }

        return parent::canAccess();
    }

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

    public static function form(Form $form): Form
    {
        return $form
            ->columns([
                'default' => 4,
                'lg' => 5,
            ])
            ->schema([
                TextInput::make('name')
                    ->columnSpan([
                        'default' => 4,
                        'md' => 3,
                        'lg' => 4,
                    ])
                    ->label('Schedule Name')
                    ->placeholder('A human readable identifier for this schedule.')
                    ->autocomplete(false)
                    ->required(),
                ToggleButtons::make('Status')
                    ->formatStateUsing(fn (Schedule $schedule) => !$schedule->is_active ? 'inactive' : ($schedule->is_processing ? 'processing' : 'active'))
                    ->options(fn (Schedule $schedule) => !$schedule->is_active ? ['inactive' => 'Inactive'] : ($schedule->is_processing ? ['processing' => 'Processing'] : ['active' => 'Active']))
                    ->colors([
                        'inactive' => 'danger',
                        'processing' => 'warning',
                        'active' => 'success',
                    ])
                    ->visibleOn('view')
                    ->columnSpan([
                        'default' => 4,
                        'md' => 1,
                        'lg' => 1,
                    ]),
                Toggle::make('only_when_online')
                    ->label('Only when Server is Online?')
                    ->hintIconTooltip('Only execute this schedule when the server is in a running state.')
                    ->hintIcon('tabler-question-mark')
                    ->inline(false)
                    ->columnSpan([
                        'default' => 2,
                        'lg' => 3,
                    ])
                    ->required()
                    ->default(1),
                Toggle::make('is_active')
                    ->label('Enable Schedule?')
                    ->hintIconTooltip('This schedule will be executed automatically if enabled.')
                    ->hintIcon('tabler-question-mark')
                    ->inline(false)
                    ->columnSpan([
                        'default' => 2,
                        'lg' => 2,
                    ])
                    ->required()
                    ->default(1),
                TextInput::make('cron_minute')
                    ->columnSpan([
                        'default' => 2,
                        'lg' => 1,
                    ])
                    ->label('Minute')
                    ->default('*/5')
                    ->required(),
                TextInput::make('cron_hour')
                    ->columnSpan([
                        'default' => 2,
                        'lg' => 1,
                    ])
                    ->label('Hour')
                    ->default('*')
                    ->required(),
                TextInput::make('cron_day_of_month')
                    ->columnSpan([
                        'default' => 2,
                        'lg' => 1,
                    ])
                    ->label('Day of Month')
                    ->default('*')
                    ->required(),
                TextInput::make('cron_month')
                    ->columnSpan([
                        'default' => 2,
                        'lg' => 1,
                    ])
                    ->label('Month')
                    ->default('*')
                    ->required(),
                TextInput::make('cron_day_of_week')
                    ->columnSpan([
                        'default' => 2,
                        'lg' => 1,
                    ])
                    ->label('Day of Week')
                    ->default('*')
                    ->required(),
                Section::make('Presets')
                    ->hiddenOn('view')
                    ->columns(1)
                    ->schema([
                        Actions::make([
                            Action::make('hourly')
                                ->disabled(fn (string $operation) => $operation === 'view')
                                ->action(function (Set $set) {
                                    $set('cron_minute', '0');
                                    $set('cron_hour', '*');
                                    $set('cron_day_of_month', '*');
                                    $set('cron_month', '*');
                                    $set('cron_day_of_week', '*');
                                }),
                            Action::make('daily')
                                ->disabled(fn (string $operation) => $operation === 'view')
                                ->action(function (Set $set) {
                                    $set('cron_minute', '0');
                                    $set('cron_hour', '0');
                                    $set('cron_day_of_month', '*');
                                    $set('cron_month', '*');
                                    $set('cron_day_of_week', '*');
                                }),
                            Action::make('weekly')
                                ->disabled(fn (string $operation) => $operation === 'view')
                                ->action(function (Set $set) {
                                    $set('cron_minute', '0');
                                    $set('cron_hour', '0');
                                    $set('cron_day_of_month', '*');
                                    $set('cron_month', '*');
                                    $set('cron_day_of_week', '0');
                                }),
                            Action::make('monthly')
                                ->disabled(fn (string $operation) => $operation === 'view')
                                ->action(function (Set $set) {
                                    $set('cron_minute', '0');
                                    $set('cron_hour', '0');
                                    $set('cron_day_of_month', '1');
                                    $set('cron_month', '*');
                                    $set('cron_day_of_week', '0');
                                }),
                            Action::make('every_x_minutes')
                                ->disabled(fn (string $operation) => $operation === 'view')
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
                            Action::make('every_x_hours')
                                ->disabled(fn (string $operation) => $operation === 'view')
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
                            Action::make('every_x_days')
                                ->disabled(fn (string $operation) => $operation === 'view')
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
                            Action::make('every_x_months')
                                ->disabled(fn (string $operation) => $operation === 'view')
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
                            Action::make('every_x_day_of_week')
                                ->disabled(fn (string $operation) => $operation === 'view')
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
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
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

    public static function getRelations(): array
    {
        return [
            TasksRelationManager::class,
        ];
    }

    public static function getPages(): array
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
