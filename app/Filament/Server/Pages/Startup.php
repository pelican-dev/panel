<?php

namespace App\Filament\Server\Pages;

use App\Facades\Activity;
use App\Filament\Components\Forms\Actions\PreviewStartupAction;
use App\Models\Permission;
use App\Models\Server;
use App\Models\ServerVariable;
use Closure;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class Startup extends ServerFormPage
{
    protected static ?string $navigationIcon = 'tabler-player-play';

    protected static ?int $navigationSort = 9;

    public function form(Form $form): Form
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $form
            ->columns([
                'default' => 1,
                'sm' => 1,
                'md' => 4,
                'lg' => 6,
            ])
            ->schema([
                Hidden::make('previewing')
                    ->default(false),
                Textarea::make('startup')
                    ->label(trans('server/startup.command'))
                    ->columnSpan([
                        'default' => 1,
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 4,
                    ])
                    ->autosize()
                    ->hintAction(PreviewStartupAction::make())
                    ->readOnly(),
                TextInput::make('custom_image')
                    ->label(trans('server/startup.docker_image'))
                    ->readOnly()
                    ->visible(fn (Server $server) => !in_array($server->image, $server->egg->docker_images))
                    ->formatStateUsing(fn (Server $server) => $server->image)
                    ->columnSpan([
                        'default' => 1,
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 2,
                    ]),
                Select::make('image')
                    ->label(trans('server/startup.docker_image'))
                    ->live()
                    ->visible(fn (Server $server) => in_array($server->image, $server->egg->docker_images))
                    ->disabled(fn () => !auth()->user()->can(Permission::ACTION_STARTUP_DOCKER_IMAGE, $server))
                    ->afterStateUpdated(function ($state, Server $server) {
                        $original = $server->image;
                        $server->forceFill(['image' => $state])->saveOrFail();

                        if ($original !== $server->image) {
                            Activity::event('server:startup.image')
                                ->property(['old' => $original, 'new' => $state])
                                ->log();
                        }

                        Notification::make()
                            ->title(trans('server/startup.notification_docker'))
                            ->body(trans('server/startup.notification_docker_body'))
                            ->success()
                            ->send();
                    })
                    ->options(function (Server $server) {
                        $images = $server->egg->docker_images;

                        return array_flip($images);
                    })
                    ->selectablePlaceholder(false)
                    ->columnSpan([
                        'default' => 1,
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 2,
                    ]),
                Section::make(trans('server/startup.variables'))
                    ->schema([
                        Repeater::make('server_variables')
                            ->hiddenLabel()
                            ->relationship('serverVariables', fn (Builder $query) => $query->where('egg_variables.user_viewable', true)->orderByPowerJoins('variable.sort'))
                            ->grid()
                            ->disabled(fn () => !auth()->user()->can(Permission::ACTION_STARTUP_UPDATE, $server))
                            ->reorderable(false)->addable(false)->deletable(false)
                            ->schema(function () {
                                $text = TextInput::make('variable_value')
                                    ->hidden($this->shouldHideComponent(...))
                                    ->dehydratedWhenHidden()
                                    ->disabled(fn (ServerVariable $serverVariable) => !$serverVariable->variable->user_editable)
                                    ->required(fn (ServerVariable $serverVariable) => $serverVariable->variable->getRequiredAttribute())
                                    ->rules([
                                        fn (ServerVariable $serverVariable): Closure => function (string $attribute, $value, Closure $fail) use ($serverVariable) {
                                            $validator = Validator::make(['validatorkey' => $value], [
                                                'validatorkey' => $serverVariable->variable->rules,
                                            ]);

                                            if ($validator->fails()) {
                                                $message = str($validator->errors()->first())->replace('validatorkey', $serverVariable->variable->name);

                                                $fail($message);
                                            }
                                        },
                                    ]);

                                $select = Select::make('variable_value')
                                    ->hidden($this->shouldHideComponent(...))
                                    ->dehydratedWhenHidden()
                                    ->disabled(fn (ServerVariable $serverVariable) => !$serverVariable->variable->user_editable)
                                    ->options($this->getSelectOptionsFromRules(...))
                                    ->selectablePlaceholder(false);

                                $components = [$text, $select];

                                foreach ($components as &$component) {
                                    $component = $component
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function ($state, ServerVariable $serverVariable) {
                                            $this->update($state, $serverVariable);
                                        })
                                        ->hintIcon('tabler-code')
                                        ->label(fn (ServerVariable $serverVariable) => $serverVariable->variable->name)
                                        ->hintIconTooltip(fn (ServerVariable $serverVariable) => implode('|', $serverVariable->variable->rules))
                                        ->prefix(fn (ServerVariable $serverVariable) => '{{' . $serverVariable->variable->env_variable . '}}')
                                        ->helperText(fn (ServerVariable $serverVariable) => empty($serverVariable->variable->description) ? '—' : $serverVariable->variable->description);
                                }

                                return $components;
                            })
                            ->columnSpan(6),
                    ]),
            ]);
    }

    protected function authorizeAccess(): void
    {
        abort_unless(auth()->user()->can(Permission::ACTION_STARTUP_READ, Filament::getTenant()), 403);
    }

    public static function canAccess(): bool
    {
        return parent::canAccess() && auth()->user()->can(Permission::ACTION_STARTUP_READ, Filament::getTenant());
    }

    private function shouldHideComponent(ServerVariable $serverVariable, Component $component): bool
    {
        $containsRuleIn = array_first($serverVariable->variable->rules, fn ($value) => str($value)->startsWith('in:'), false);

        if ($component instanceof Select) {
            return !$containsRuleIn;
        }

        if ($component instanceof TextInput) {
            return $containsRuleIn;
        }

        throw new \Exception('Component type not supported: ' . $component::class);
    }

    /**
     * @return string[]
     */
    private function getSelectOptionsFromRules(ServerVariable $serverVariable): array
    {
        $inRule = array_first($serverVariable->variable->rules, fn ($value) => str($value)->startsWith('in:'));

        return str($inRule)
            ->after('in:')
            ->explode(',')
            ->each(fn ($value) => str($value)->trim())
            ->mapWithKeys(fn ($value) => [$value => $value])
            ->all();
    }

    public function update(?string $state, ServerVariable $serverVariable): null
    {
        $original = $serverVariable->variable_value;

        try {

            $validator = Validator::make(
                ['variable_value' => $state],
                ['variable_value' => $serverVariable->variable->rules]
            );

            if ($validator->fails()) {
                Notification::make()
                    ->title(trans('server/startup.validation_fail', ['variable' => $serverVariable->variable->name]))
                    ->body(implode(', ', $validator->errors()->all()))
                    ->danger()
                    ->send();

                return null;
            }

            ServerVariable::query()->updateOrCreate([
                'server_id' => $this->getRecord()->id,
                'variable_id' => $serverVariable->variable->id,
            ], [
                'variable_value' => $state ?? '',
            ]);

            if ($original !== $state) {
                Activity::event('server:startup.edit')
                    ->property([
                        'variable' => $serverVariable->variable->env_variable,
                        'old' => $original,
                        'new' => $state,
                    ])
                    ->log();
            }
            Notification::make()
                ->title(trans('server/startup.update', ['variable' => $serverVariable->variable->name]))
                ->body(fn () => $original . ' -> ' . $state)
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title(trans('server/startup.fail', ['variable' => $serverVariable->variable->name]))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }

        return null;
    }

    public function getTitle(): string|Htmlable
    {
        return trans('server/startup.title');
    }

    public static function getNavigationLabel(): string
    {
        return trans('server/startup.title');
    }
}
