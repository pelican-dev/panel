<?php

namespace App\Filament\Admin\Widgets;

use Filament\Actions\Action;
use Filament\Widgets\Widget;

class SupportWidget extends Widget
{
    protected string $view = 'filament.admin.widgets.support-widget';

    protected static bool $isLazy = false;

    protected static ?int $sort = 3;

    public function getViewData(): array
    {
        return [
            'action' => Action::make('donate')
                ->label(trans('admin/dashboard.sections.intro-support.button_donate'))
                ->icon('tabler-cash')
                ->url('https://pelican.dev/donate', true)
                ->color('success')
                ->toHtmlString(),
        ];
    }
}
