<?php

namespace App\Filament\Admin\Widgets;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;

class HelpWidget extends FormWidget
{
    protected static ?int $sort = 4;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(trans('admin/dashboard.sections.intro-help.heading'))
                    ->icon('tabler-question-mark')
                    ->iconColor('info')
                    ->collapsible()
                    ->persistCollapsed()
                    ->schema([
                        Placeholder::make('')
                            ->content(trans('admin/dashboard.sections.intro-help.content')),
                    ])
                    ->headerActions([
                        Action::make('docs')
                            ->label(trans('admin/dashboard.sections.intro-help.button_docs'))
                            ->icon('tabler-speedboat')
                            ->url('https://pelican.dev/docs', true),
                    ]),
            ]);
    }
}
