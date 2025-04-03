<?php

namespace App\Filament\Admin\Widgets;

use Filament\Actions\CreateAction;
use Filament\Widgets\Widget;

class SupportWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.support-widget';

    protected static bool $isLazy = false;

    protected static ?int $sort = 3;

    public function getViewData(): array
    {
        return [
            'actions' => [
                CreateAction::make()
                    ->label(trans('admin/dashboard.sections.intro-support.button_donate'))
                    ->icon('tabler-cash')
                    ->url('https://pelican.dev/donate', true)
                    ->color('success'),
            ],
        ];
    }
}
