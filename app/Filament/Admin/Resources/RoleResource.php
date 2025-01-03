<?php

namespace App\Filament\Admin\Resources;

use App\Enums\RolePermissionModels;
use App\Enums\RolePermissionPrefixes;
use App\Filament\Admin\Resources\RoleResource\Pages;
use App\Models\Role;
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

    protected static ?string $navigationGroup = 'Advanced';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function form(Form $form): Form
    {
        $permissions = [];

        foreach (RolePermissionModels::cases() as $model) {
            $options = [];

            foreach (RolePermissionPrefixes::cases() as $prefix) {
                $options[$prefix->value . ' ' . strtolower($model->value)] = Str::headline($prefix->value);
            }

            if (array_key_exists($model->value, Role::MODEL_SPECIFIC_PERMISSIONS)) {
                foreach (Role::MODEL_SPECIFIC_PERMISSIONS[$model->value] as $permission) {
                    $options[$permission . ' ' . strtolower($model->value)] = Str::headline($permission);
                }
            }

            $permissions[] = self::makeSection($model->value, $options);
        }

        foreach (Role::SPECIAL_PERMISSIONS as $model => $prefixes) {
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
                    ->required()
                    ->disabled(fn (Get $get) => $get('name') === Role::ROOT_ADMIN),
                TextInput::make('guard_name')
                    ->label('Guard Name')
                    ->default(Role::DEFAULT_GUARD_NAME)
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
        } elseif (class_exists('\App\Filament\Server\Resources\\' . $model . 'Resource')) {
            $icon = ('\App\Filament\Server\Resources\\' . $model . 'Resource')::getNavigationIcon();
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
