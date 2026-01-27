<?php

namespace App\Filament\Admin\Pages;

use App\Enums\TablerIcon;
use App\Services\Helpers\SoftwareVersionService;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = TablerIcon::LayoutDashboard;

    private SoftwareVersionService $softwareVersionService;

    public function mount(SoftwareVersionService $softwareVersionService): void
    {
        $this->softwareVersionService = $softwareVersionService;
    }

    public function getColumns(): int|array
    {
        return 1;
    }

    public function getHeading(): string
    {
        return trans('admin/dashboard.heading');
    }

    public function getSubheading(): string
    {
        return trans('admin/dashboard.version', ['version' => $this->softwareVersionService->currentPanelVersion()]);
    }
}
