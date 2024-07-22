<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

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

    public static function form(Form $form): Form
    {
        $permissions = [];

        foreach (self::PERMISSION_MODELS as $model) {
            $permissions[] = self::generatePermissionChecklist($model);
        }

        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Role Name')
                    ->required(),
                TextInput::make('guard_name')
                    ->label('Guard Name')
                    ->default(Filament::getCurrentPanel()?->getAuthGuard() ?? '')
                    ->nullable(),
                Section::make('Permissions')
                    ->columns(3)
                    ->schema($permissions)
                    ->hidden(fn (Get $get) => $get('name') === 'Root Admin'),
                Placeholder::make('permissions')
                    ->content('The Root Admin has all permissions.')
                    ->visible(fn (Get $get) => $get('name') === 'Root Admin'),
            ]);
    }

    private const PERMISSION_PREFIXES = [
        'viewAny',
        'view',
        'create',
        'update',
        'delete',
        'restore',
        'forceDelete',
    ];

    private static function generatePermissionChecklist(string $model): CheckboxList
    {
        $options = [];

        foreach (self::PERMISSION_PREFIXES as $prefix) {
            $options[$prefix . ' ' . strtolower($model)] = studly_case($prefix);
        }

        return CheckboxList::make($model)
            ->label($model)
            ->options($options)
            ->bulkToggleable()
            ->afterStateHydrated(
                function (Component $component, string $operation, ?Model $record) use ($options) {
                    if (in_array($operation, ['edit', 'view'])) {

                        if (blank($record)) {
                            return;
                        }

                        if ($component->isVisible() && count($options) > 0) {
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
            ->dehydrated(fn ($state) => !blank($state));
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
