<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Models\Role;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Illuminate\Support\Str;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'tabler-users-group';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    private const PERMISSION_MODELS = [
        'ApiKey',
        'DatabaseHost',
        'Database',
        'Egg',
        'Mount',
        'Node',
        'Role',
        'Server',
        'User',
    ];

    private const PERMISSION_PREFIXES = [
        'viewAny',
        'view',
        'create',
        'update',
        'delete',
        'restore',
        'forceDelete',
    ];

    private const MODEL_SPECIFIC_PERMISSIONS = [
        'Egg' => [
            'import',
            'export',
        ],
    ];

    private const SPECIAL_PERMISSIONS = [
        'Settings' => [
            'view',
            'update',
        ],
    ];

    public static function form(Form $form): Form
    {
        $permissions = [];

        foreach (self::PERMISSION_MODELS as $model) {
            $options = [];

            foreach (self::PERMISSION_PREFIXES as $prefix) {
                $options[$prefix . ' ' . strtolower($model)] = Str::headline($prefix);
            }

            if (array_key_exists($model, self::MODEL_SPECIFIC_PERMISSIONS)) {
                foreach (self::MODEL_SPECIFIC_PERMISSIONS[$model] as $permission) {
                    $options[$permission . ' ' . strtolower($model)] = Str::headline($permission);
                }
            }

            $permissions[] = self::makeSection($model, $options);
        }

        foreach (self::SPECIAL_PERMISSIONS as $model => $prefixes) {
            $options = [];

            foreach ($prefixes as $prefix) {
                $options[$prefix . ' ' . strtolower($model)] = Str::headline($prefix);
            }

            $permissions[] = self::makeSection($model, $options);
        }

        return $form
            ->columns(1)
            ->schema([
                TextInput::make('name')
                    ->label('Role Name')
                    ->required(),
                TextInput::make('guard_name')
                    ->label('Guard Name')
                    ->default(Filament::getCurrentPanel()?->getAuthGuard() ?? '')
                    ->nullable()
                    ->hidden(),
                Fieldset::make('Permissions')
                    ->columns(3)
                    ->schema($permissions)
                    ->hidden(fn (Get $get) => $get('name') === Role::ROOT_ADMIN),
                Placeholder::make('permissions')
                    ->content('The Root Admin has all permissions.')
                    ->visible(fn (Get $get) => $get('name') === Role::ROOT_ADMIN),
            ]);
    }

    private static function makeSection(string $model, array $options): Section
    {
        $icon = null;

        if (class_exists('\App\Filament\Resources\\' . $model . 'Resource')) {
            $icon = ('\App\Filament\Resources\\' . $model . 'Resource')::getNavigationIcon();
        } elseif (class_exists('\App\Filament\Pages\\' . $model)) {
            $icon = ('\App\Filament\Pages\\' . $model)::getNavigationIcon();
        }

        return Section::make(Str::headline(Str::plural($model)))
            ->columnSpan(1)
            ->collapsible()
            ->collapsed()
            ->icon($icon)
            ->headerActions([
                Action::make('count')
                    ->label(fn (Get $get) => count($get(strtolower($model) . '_list')))
                    ->badge(),
            ])
            ->schema([
                CheckboxList::make(strtolower($model) . '_list')
                    ->label('')
                    ->options($options)
                    ->columns()
                    ->gridDirection('row')
                    ->bulkToggleable()
                    ->live()
                    ->afterStateHydrated(
                        function (Component $component, string $operation, ?Role $record) use ($options) {
                            if (in_array($operation, ['edit', 'view'])) {

                                if (blank($record)) {
                                    return;
                                }

                                if ($component->isVisible()) {
                                    $component->state(
                                        collect($options)
                                            ->filter(fn ($value, $key) => $record->checkPermissionTo($key))
                                            ->keys()
                                            ->toArray()
                                    );
                                }
                            }
                        }
                    )
                    ->dehydrated(fn ($state) => !blank($state)),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
