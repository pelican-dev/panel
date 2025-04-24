<?php

namespace App\Filament\Admin\Widgets;

use App\Services\Helpers\SoftwareVersionService;
use Filament\Actions\CreateAction;
use Filament\Widgets\Widget;

class UpdateWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.update-widget';

    protected static bool $isLazy = false;

    protected static ?int $sort = 0;

    private SoftwareVersionService $softwareVersionService;

    public function mount(SoftwareVersionService $softwareVersionService): void
    {
        $this->softwareVersionService = $softwareVersionService;
    }

    public function getViewData(): array
    {
        return [
            'version' => $this->softwareVersionService->currentPanelVersion(),
            'latestVersion' => $this->softwareVersionService->latestPanelVersion(),
            'isLatest' => $this->softwareVersionService->isLatestPanel(),
            'actions' => [
                CreateAction::make()
                    ->label(trans('admin/dashboard.sections.intro-update-available.heading'))
                    ->icon('tabler-clipboard-text')
                    ->url('https://pelican.dev/docs/panel/update', true)
                    ->color('warning'),
            ],
        ];
    }
}
