<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RoleResource\Pages;
use App\Models\Role;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Spatie\Permission\Contracts\Permission;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-users-group';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return trans('admin/role.nav_title');
    }

    public static function getModelLabel(): string
    {
        return trans('admin/role.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('admin/role.model_label_plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return config('panel.filament.top-navigation', false) ? trans('admin/dashboard.advanced') : trans('admin/dashboard.user');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(trans('admin/role.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('permissions_count')
                    ->label(trans('admin/role.permissions'))
                    ->badge()
                    ->counts('permissions')
                    ->formatStateUsing(fn (Role $role, $state) => $role->isRootAdmin() ? trans('admin/role.all') : $state),
                TextColumn::make('users_count')
                    ->label(trans('admin/role.users'))
                    ->counts('users')
                    ->icon('tabler-users'),
            ])
            ->actions([
                ViewAction::make()
                    ->hidden(fn ($record) => static::canEdit($record)),
                EditAction::make(),
            ])
            ->checkIfRecordIsSelectableUsing(fn (Role $role) => !$role->isRootAdmin() && $role->users_count <= 0)
            ->groupedBulkActions([
                DeleteBulkAction::make(),
            ])
            ->emptyStateIcon('tabler-users-group')
            ->emptyStateDescription('')
            ->emptyStateHeading(trans('admin/role.no_roles'))
            ->emptyStateActions([
                CreateAction::make(),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function form(Form|Schema $schema): Schema
    {
        $permissionSections = [];

        foreach (Role::getPermissionList() as $model => $permissions) {
            $options = [];

            foreach ($permissions as $permission) {
                $options[$permission . ' ' . strtolower($model)] = Str::headline($permission);
            }

            $permissionSections[] = self::makeSection($model, $options);
        }

        return $schema
            ->columns(1)
            ->schema([
                TextInput::make('name')
                    ->label(trans('admin/role.name'))
                    ->required()
                    ->disabled(fn (Get $get) => $get('name') === Role::ROOT_ADMIN),
                TextInput::make('guard_name')
                    ->label('Guard Name')
                    ->default(Role::DEFAULT_GUARD_NAME)
                    ->nullable()
                    ->hidden(),
                Fieldset::make(trans('admin/role.permissions'))
                    ->columns(3)
                    ->schema($permissionSections)
                    ->hidden(fn (Get $get) => $get('name') === Role::ROOT_ADMIN),
                TextEntry::make('permissions')
                    ->label(trans('admin/role.permissions'))
                    ->state(trans('admin/role.root_admin', ['role' => Role::ROOT_ADMIN]))
                    ->visible(fn (Get $get) => $get('name') === Role::ROOT_ADMIN),
            ]);
    }

    /**
     * @param string[]|int[]|Permission[]|\BackedEnum[] $options
     * @throws Exception
     */
    private static function makeSection(string $model, array $options): Section
    {
        $icon = null;

        if (class_exists('\App\Filament\Admin\Resources\\' . $model . 'Resource')) {
            $icon = ('\App\Filament\Admin\Resources\\' . $model . 'Resource')::getNavigationIcon();
        } elseif (class_exists('\App\Filament\Admin\Pages\\' . $model)) {
            $icon = ('\App\Filament\Admin\Pages\\' . $model)::getNavigationIcon();
        } elseif (class_exists('\App\Filament\Server\Resources\\' . $model . 'Resource')) {
            $icon = ('\App\Filament\Server\Resources\\' . $model . 'Resource')::getNavigationIcon();
        }

        return Section::make(Str::headline($model))
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
            'view' => Pages\ViewRole::route('/{record}'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
