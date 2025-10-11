<?php

namespace App\Filament\Admin\Widgets;

use App\Services\Helpers\SoftwareVersionService;
use Exception;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UpdateWidget extends FormWidget
{
    protected static ?int $sort = 0;

    private SoftwareVersionService $softwareVersionService;

    public function mount(SoftwareVersionService $softwareVersionService): void
    {
        $this->softwareVersionService = $softwareVersionService;
    }

    /**
     * @throws Exception
     */
    public function form(Schema $schema): Schema
    {
        $isLatest = $this->softwareVersionService->isLatestPanel();

        return $schema
            ->components([
                $isLatest
                ? Section::make(trans('admin/dashboard.sections.intro-no-update.heading'))
                    ->icon('tabler-checkbox')
                    ->iconColor('success')
                    ->schema([
                        TextEntry::make('info')
                            ->hiddenLabel()
                            ->state(trans('admin/dashboard.sections.intro-no-update.content', ['version' => $this->softwareVersionService->currentPanelVersion()])),
                    ])
                : Section::make(trans('admin/dashboard.sections.intro-update-available.heading'))
                    ->icon('tabler-info-circle')
                    ->iconColor('warning')
                    ->schema([
                        TextEntry::make('info')
                            ->hiddenLabel()
                            ->state(trans('admin/dashboard.sections.intro-update-available.content', ['latestVersion' => $this->softwareVersionService->latestPanelVersion()])),
                        Section::make(trans('admin/dashboard.sections.intro-update-available.button_changelog'))
                            ->icon('tabler-script')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                TextEntry::make('changelog')
                                    ->hiddenLabel()
                                    ->state($this->softwareVersionService->latestPanelVersionChangelog())
                                    ->markdown(),
                            ]),
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
