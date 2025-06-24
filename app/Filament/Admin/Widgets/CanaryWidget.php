<?php

namespace App\Filament\Admin\Widgets;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;

class CanaryWidget extends FormWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return config('app.version') === 'canary';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(trans('admin/dashboard.sections.intro-developers.heading'))
                    ->icon('tabler-code')
                    ->iconColor('primary')
                    ->collapsible()
                    ->collapsed()
                    ->persistCollapsed()
                    ->schema([
                        Placeholder::make('')
                            ->content(trans('admin/dashboard.sections.intro-developers.content')),
                        Placeholder::make('')
                            ->content(trans('admin/dashboard.sections.intro-developers.extra_note')),
                    ])
                    ->headerActions([
                        Action::make('issues')
                            ->label(trans('admin/dashboard.sections.intro-developers.button_issues'))
                            ->icon('tabler-brand-github')
                            ->url('https://github.com/pelican-dev/panel/issues', true),
                    ]),
            ]);
    }
}
