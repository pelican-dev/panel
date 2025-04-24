<?php

namespace App\Filament\Admin\Widgets;

use Filament\Actions\CreateAction;
use Filament\Widgets\Widget;

class HelpWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.help-widget';

    protected static bool $isLazy = false;

    protected static ?int $sort = 4;

    public function getViewData(): array
    {
        return [
            'actions' => [
                CreateAction::make()
                    ->label(trans('admin/dashboard.sections.intro-help.button_docs'))
                    ->icon('tabler-speedboat')
                    ->url('https://pelican.dev/docs', true),
            ],
        ];
    }
}
