<?php

namespace App\Filament\App\Pages;

use App\Models\Egg;
use App\Models\Permission;
use App\Models\Server;
use Closure;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;

class Startup extends SimplePage
{

    protected static ?string $navigationIcon = 'tabler-player-play';

    protected static string $view = 'filament.app.pages.startup';

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
                Textarea::make('startup')
                    ->label('Startup Command')
                    ->columnSpan([
                        'default' => 1,
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 4,
                    ])
                    ->formatStateUsing(function () use ($server) {
                        return $server->startup;
                    })
                    ->autosize()
                    ->readOnly(),
                Select::make('select_image') //TODO: Show Custom Image if Image !== $egg->docker_images
                    ->label('Docker Image')
                    ->afterStateUpdated(fn (Set $set, $state) => $set('image', $state))
                    ->options(function (Set $set) use ($server) {
                        $egg = Egg::query()->find($server->egg_id);
                        $images = $egg->docker_images ?? [];

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
                Section::make('Server Variables') //TODO: Make purtty, Make rules (test vs select) work.
                    ->columnSpanFull()
                    ->schema(function () {
                        $variableComponents = [];

                        foreach ($this->serverVariables() as $serverVariable) {
                            if (!$serverVariable->variable->user_viewable) {
                                continue;
                            }

                            $text = TextInput::make('var_'.$serverVariable->variable->name)
                                ->hidden($this->shouldHideComponent(...))
                                ->readOnlyOn($serverVariable->variable->user_editable)
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

                            $select = Select::make('var_'.$serverVariable->variable->name)
                                ->hidden($this->shouldHideComponent(...))
                                ->options($this->getSelectOptionsFromRules(...))
                                ->selectablePlaceholder(false);

                            $components = [$text, $select];

                            foreach ($components as &$component) {
                                $component = $component
                                    ->live(onBlur: true)
                                    ->hintIcon('tabler-code')
                                    ->label(fn () => $serverVariable->variable->name)
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
    protected function authorizeAccess(): void
    {
        abort_unless(!auth()->user()->can(Permission::ACTION_STARTUP_READ), 403);
    }

    private function shouldHideComponent(Get $get, Component $component): bool
    {
        $containsRuleIn = str($get('rules'))->explode('|')->reduce(
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

    private function getSelectOptionsFromRules(Get $get): array
    {
        $inRule = str($get('rules'))->explode('|')->reduce(
            fn ($result, $value) => str($value)->startsWith('in:') ? $value : $result, ''
        );

        return str($inRule)
            ->after('in:')
            ->explode(',')
            ->each(fn ($value) => str($value)->trim())
            ->mapWithKeys(fn ($value) => [$value => $value])
            ->all();
    }

    private function serverVariables(): Collection
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $server->serverVariables;
    }
}
