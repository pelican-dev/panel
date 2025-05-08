<?php

namespace App\Filament\Admin\Widgets;

use Filament\Actions\Action;
use Filament\Widgets\Widget;

class CanaryWidget extends Widget
{
    protected string $view = 'filament.admin.widgets.canary-widget';

    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return config('app.version') === 'canary';
    }

    public function getViewData(): array
    {
        return [
            'action' => Action::make('github')
                ->label(trans('admin/dashboard.sections.intro-developers.button_issues'))
                ->icon('tabler-brand-github')
                ->url('https://github.com/pelican-dev/panel/issues', true)
                ->toHtmlString(),
        ];
    }
}
