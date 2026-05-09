<?php

namespace App\Filament\Admin\Resources\ActivityLogs;

use App\Enums\TablerIcon;
use App\Filament\Admin\Resources\ActivityLogs\Pages\ListActivityLogs;
use App\Models\ActivityLog;
use App\Traits\Filament\CanCustomizePages;
use BackedEnum;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;

class ActivityLogResource extends Resource
{
    use CanCustomizePages;

    protected static ?string $model = ActivityLog::class;

    protected static string|BackedEnum|null $navigationIcon = TablerIcon::ShieldSearch;

    protected static ?int $navigationSort = 10;

    public static function getNavigationLabel(): string
    {
        return trans('admin/log.navigation.admin_audit_log');
    }

    public static function getModelLabel(): string
    {
        return trans('admin/log.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('admin/log.model_label_plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('admin/dashboard.advanced');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canView($record): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        $user = user();
        if (!$user) {
            return false;
        }

        if ($user->isRootAdmin()) {
            return true;
        }

        return $user->can('view adminAuditLog') || $user->can('view panelLog');
    }

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
        ];
    }
}
