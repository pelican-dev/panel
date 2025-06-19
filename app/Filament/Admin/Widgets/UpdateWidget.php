<?php

namespace App\Filament\Admin\Widgets;

use App\Services\Helpers\SoftwareVersionService;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;

class UpdateWidget extends FormWidget
{
    protected static ?int $sort = 0;

    private SoftwareVersionService $softwareVersionService;

    public function mount(SoftwareVersionService $softwareVersionService): void
    {
        $this->softwareVersionService = $softwareVersionService;
    }

    public function form(Form $form): Form
    {
        $isLatest = $this->softwareVersionService->isLatestPanel();

        return $form
            ->schema([
                $isLatest
                ? Section::make(trans('admin/dashboard.sections.intro-no-update.heading'))
                    ->icon('tabler-checkbox')
                    ->iconColor('success')
                    ->schema([
                        Placeholder::make('')
                            ->content(trans('admin/dashboard.sections.intro-no-update.content', ['version' => $this->softwareVersionService->currentPanelVersion()])),
                    ])
                : Section::make(trans('admin/dashboard.sections.intro-update-available.heading'))
                    ->icon('tabler-info-circle')
                    ->iconColor('warning')
                    ->schema([
                        Placeholder::make('')
                            ->content(trans('admin/dashboard.sections.intro-update-available.content', ['latestVersion' => $this->softwareVersionService->latestPanelVersion()])),
                    ])
                    ->headerActions([
                        Action::make('update')
                            ->label(trans('admin/dashboard.sections.intro-update-available.heading'))
                            ->icon('tabler-clipboard-text')
                            ->url('https://pelican.dev/docs/panel/update', true)
                            ->color('warning'),
                    ]),
            ]);
    }
}
