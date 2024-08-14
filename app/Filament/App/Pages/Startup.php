<?php

namespace App\Filament\App\Pages;

use App\Models\Permission;
use App\Models\Server;
use App\Models\ServerVariable;
use Closure;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Validator;

class Startup extends SimplePage
{
    protected static ?string $navigationIcon = 'tabler-player-play';
    protected static ?int $navigationSort = 8;

    public function form(Form $form): Form
    {
        return $form
            ->columns([
                'default' => 1,
                'sm' => 1,
                'md' => 4,
                'lg' => 6,
            ])
            ->schema([
                Textarea::make('startup')
                    ->label('Startup Command')
                    ->columnSpan([
                        'default' => 1,
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 4,
                    ])
                    ->formatStateUsing(function () {
                        /** @var Server $server */
                        $server = Filament::getTenant();

                        return $server->startup;
                    })
                    ->autosize()
                    ->readOnly(),
                Select::make('select_image') //TODO: Show Custom Image if Image !== $egg->docker_images
                    ->label('Docker Image')
                    ->afterStateUpdated(fn (Set $set, $state) => $set('image', $state))
                    ->options(function (Set $set) {
                        /** @var Server $server */
                        $server = Filament::getTenant();

                        $images = $server->egg->docker_images ?? [];

                        $currentImage = $server->image;
                        if (!$currentImage && $images) {
                            $defaultImage = collect($images)->first();
                            $set('image', $defaultImage);
                            $set('select_image', $defaultImage);
                        }

                        return array_flip($images);
                    })
                    ->selectablePlaceholder(false)
                    ->columnSpan([
                        'default' => 1,
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 2,
                    ]),
                Section::make('Server Variables')
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'lg' => 2,
                    ])
                    ->schema(function () {
                        /** @var Server $server */
                        $server = Filament::getTenant();

                        $variableComponents = [];

                        /** @var ServerVariable $serverVariable */
                        foreach ($server->serverVariables as $serverVariable) {
                            if (!$serverVariable->variable->user_viewable) {
                                continue;
                            }

                            $text = TextInput::make('var_' . $serverVariable->variable->name)
                                ->hidden(fn (Component $component) => $this->shouldHideComponent($serverVariable, $component))
                                ->readOnly(fn () => !$serverVariable->variable->user_editable)
                                ->required(fn () => in_array('required', explode('|', $serverVariable->variable->rules)))
                                ->rules([
                                    fn (): Closure => function (string $attribute, $value, Closure $fail) use ($serverVariable) {
                                        $validator = Validator::make(['validatorkey' => $value], [
                                            'validatorkey' => $serverVariable->variable->rules,
                                        ]);

                                        if ($validator->fails()) {
                                            $message = str($validator->errors()->first())->replace('validatorkey', $serverVariable->variable->name);

                                            $fail($message);
                                        }
                                    },
                                ]);

                            $select = Select::make('var_' . $serverVariable->variable->name)
                                ->hidden(fn (Component $component) => $this->shouldHideComponent($serverVariable, $component))
                                ->disabled(fn () => !$serverVariable->variable->user_editable) // TODO: find better way, doesn't look so nice
                                ->options(fn () => $this->getSelectOptionsFromRules($serverVariable))
                                ->selectablePlaceholder(false);

                            $components = [$text, $select];

                            foreach ($components as &$component) {
                                $component = $component
                                    ->live(onBlur: true)
                                    ->hintIcon('tabler-code')
                                    ->label(fn () => $serverVariable->variable->name)
                                    ->default(fn () => $serverVariable->variable_value ?? $serverVariable->variable->default_value)
                                    ->hintIconTooltip(fn () => $serverVariable->variable->rules)
                                    ->prefix(fn () => '{{' . $serverVariable->variable->env_variable . '}}')
                                    ->helperText(fn () => empty($serverVariable->variable->description) ? '—' : $serverVariable->variable->description);
                            }

                            $variableComponents = array_merge($variableComponents, $components);
                        }

                        return $variableComponents;
                    }),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->keyBindings('mod+s')
                ->action(function () {
                    // TODO
                }),
        ];
    }

    protected function authorizeAccess(): void
    {
        abort_unless(!auth()->user()->can(Permission::ACTION_STARTUP_READ), 403);
    }

    private function shouldHideComponent(ServerVariable $serverVariable, Component $component): bool
    {
        $containsRuleIn = str($serverVariable->variable->rules)->explode('|')->reduce(
            fn ($result, $value) => $result === true && !str($value)->startsWith('in:'), true
        );

        if ($component instanceof Select) {
            return $containsRuleIn;
        }

        if ($component instanceof TextInput) {
            return !$containsRuleIn;
        }

        throw new \Exception('Component type not supported: ' . $component::class);
    }

    private function getSelectOptionsFromRules(ServerVariable $serverVariable): array
    {
        $inRule = str($serverVariable->variable->rules)->explode('|')->reduce(
            fn ($result, $value) => str($value)->startsWith('in:') ? $value : $result, ''
        );

        return str($inRule)
            ->after('in:')
            ->explode(',')
            ->each(fn ($value) => str($value)->trim())
            ->mapWithKeys(fn ($value) => [$value => $value])
            ->all();
    }
}
