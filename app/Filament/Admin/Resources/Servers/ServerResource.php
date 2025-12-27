<?php

namespace App\Filament\Admin\Resources\Servers;

use App\Enums\CustomizationKey;
use App\Filament\Admin\Resources\Servers\Pages\CreateServer;
use App\Filament\Admin\Resources\Servers\Pages\EditServer;
use App\Filament\Admin\Resources\Servers\Pages\ListServers;
use App\Filament\Admin\Resources\Servers\RelationManagers\AllocationsRelationManager;
use App\Filament\Admin\Resources\Servers\RelationManagers\DatabasesRelationManager;
use App\Models\Mount;
use App\Models\Server;
use App\Traits\Filament\CanCustomizePages;
use App\Traits\Filament\CanCustomizeRelations;
use Exception;
use Filament\Forms\Components\CheckboxList;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Database\Eloquent\Builder;

class ServerResource extends Resource
{
    use CanCustomizePages;
    use CanCustomizeRelations;

    protected static ?string $model = Server::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-brand-docker';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return trans('admin/server.nav_title');
    }

    public static function getModelLabel(): string
    {
        return trans('admin/server.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('admin/server.model_label_plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return user()?->getCustomization(CustomizationKey::TopNavigation) ? false : trans('admin/dashboard.server');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->count() ?: null;
    }

    /**
     * @throws Exception
     */
    public static function getMountCheckboxList(Get $get): CheckboxList
    {
        $allowedMounts = Mount::all();
        $node = $get('node_id');
        $egg = $get('egg_id');

        if ($node && $egg) {
            $allowedMounts = $allowedMounts->filter(fn (Mount $mount) => ($mount->nodes->isEmpty() || $mount->nodes->contains($node)) &&
                ($mount->eggs->isEmpty() || $mount->eggs->contains($egg))
            );
        }

        return CheckboxList::make('mounts')
            ->hiddenLabel()
            ->relationship('mounts')
            ->live()
            ->options(fn () => $allowedMounts->mapWithKeys(fn ($mount) => [$mount->id => $mount->name]))
            ->descriptions(fn () => $allowedMounts->mapWithKeys(fn ($mount) => [$mount->id => "$mount->source -> $mount->target"]))
            ->helperText(fn () => $allowedMounts->isEmpty() ? trans('admin/server.no_mounts') : null)
            ->bulkToggleable()
            ->columnSpanFull();
    }

    /** @return class-string<RelationManager>[] */
    public static function getDefaultRelations(): array
    {
        return [
            AllocationsRelationManager::class,
            DatabasesRelationManager::class,
        ];
    }

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [
            'index' => ListServers::route('/'),
            'create' => CreateServer::route('/create'),
            'edit' => EditServer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        return $query->whereIn('node_id', user()?->accessibleNodes()->pluck('id'));
    }
}
