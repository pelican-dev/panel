<?php

namespace App\Filament\Admin\Widgets;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;

class SupportWidget extends FormWidget
{
    protected static ?int $sort = 3;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(trans('admin/dashboard.sections.intro-support.heading'))
                    ->icon('tabler-heart-filled')
                    ->iconColor('danger')
                    ->collapsible()
                    ->persistCollapsed()
                    ->schema([
                        Placeholder::make('')
                            ->content(trans('admin/dashboard.sections.intro-support.content')),
                        Placeholder::make('')
                            ->content(trans('admin/dashboard.sections.intro-support.extra_note')),
                    ])
                    ->headerActions([
                        Action::make('donate')
                            ->label(trans('admin/dashboard.sections.intro-support.button_donate'))
                            ->icon('tabler-cash')
                            ->url('https://pelican.dev/donate', true)
                            ->color('success'),
                    ]),
            ]);
    }
}
