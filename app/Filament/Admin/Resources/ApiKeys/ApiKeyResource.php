<?php

namespace App\Filament\Admin\Resources\ApiKeys;

use App\Filament\Admin\Resources\ApiKeys\Pages\CreateApiKey;
use App\Filament\Admin\Resources\ApiKeys\Pages\ListApiKeys;
use App\Filament\Admin\Resources\Users\Pages\EditUser;
use App\Filament\Components\Tables\Columns\DateTimeColumn;
use App\Models\ApiKey;
use App\Traits\Filament\CanCustomizePages;
use App\Traits\Filament\CanCustomizeRelations;
use App\Traits\Filament\CanModifyForm;
use App\Traits\Filament\CanModifyTable;
use Exception;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ApiKeyResource extends Resource
{
    use CanCustomizePages;
    use CanCustomizeRelations;
    use CanModifyForm;
    use CanModifyTable;

    protected static ?string $model = ApiKey::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-key';

    public static function getNavigationLabel(): string
    {
        return trans('admin/apikey.nav_title');
    }

    public static function getModelLabel(): string
    {
        return trans('admin/apikey.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('admin/apikey.model_label_plural');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->count() ?: null;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        return $query->where('key_type', ApiKey::TYPE_APPLICATION);
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('admin/dashboard.advanced');
    }

    /**
     * @throws Exception
     */
    public static function defaultTable(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label(trans('admin/apikey.table.key'))
                    ->icon('tabler-clipboard-text')
                    ->state(fn (ApiKey $key) => $key->identifier . $key->token)
                    ->copyable(),
                TextColumn::make('memo')
                    ->label(trans('admin/apikey.table.description'))
                    ->wrap()
                    ->limit(50),
                DateTimeColumn::make('last_used_at')
                    ->label(trans('admin/apikey.table.last_used'))
                    ->placeholder(trans('admin/apikey.table.never_used'))
                    ->sortable(),
                DateTimeColumn::make('created_at')
                    ->label(trans('admin/apikey.table.created'))
                    ->sortable(),
                TextColumn::make('user.username')
                    ->label(trans('admin/apikey.table.created_by'))
                    ->url(fn (ApiKey $apiKey) => user()?->can('update', $apiKey->user) ? EditUser::getUrl(['record' => $apiKey->user]) : null),
            ])
            ->recordActions([
                DeleteAction::make()
                    ->iconButton()->iconSize(IconSize::ExtraLarge),
            ])
            ->emptyStateIcon('tabler-key')
            ->emptyStateDescription('')
            ->emptyStateHeading(trans('admin/apikey.empty'));
    }

    /**
     * @throws Exception
     */
    public static function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Permissions')
                    ->columnSpanFull()
                    ->schema(
                        collect(ApiKey::getPermissionList())->map(fn ($resource) => ToggleButtons::make('permissions_' . $resource)
                            ->label(str($resource)->replace('_', ' ')->title())->inline()
                            ->options([
                                0 => trans('admin/apikey.permissions.none'),
                                1 => trans('admin/apikey.permissions.read'),
                                3 => trans('admin/apikey.permissions.read_write'),
                            ])
                            ->icons([
                                0 => 'tabler-book-off',
                                1 => 'tabler-book',
                                3 => 'tabler-writing',
                            ])
                            ->colors([
                                0 => 'success',
                                1 => 'warning',
                                3 => 'danger',
                            ])
                            ->required()
                            ->columnSpan([
                                'default' => 1,
                                'sm' => 1,
                                'md' => 1,
                            ])
                            ->default(0),
                        )->all(),
                    ),
                TagsInput::make('allowed_ips')
                    ->placeholder(trans('admin/apikey.whitelist_placeholder'))
                    ->label(trans('admin/apikey.whitelist'))
                    ->helperText(trans('admin/apikey.whitelist_help'))
                    ->columnSpanFull(),
                Textarea::make('memo')
                    ->required()
                    ->label(trans('admin/apikey.description'))
                    ->helperText(trans('admin/apikey.description_help'))
                    ->columnSpanFull(),
            ]);
    }

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [
            'index' => ListApiKeys::route('/'),
            'create' => CreateApiKey::route('/create'),
        ];
    }
}
