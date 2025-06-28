<?php

namespace App\Filament\Admin\Widgets;

use Exception;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupportWidget extends FormWidget
{
    protected static ?int $sort = 3;

    /**
     * @throws Exception
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(trans('admin/dashboard.sections.intro-support.heading'))
                    ->icon('tabler-heart-filled')
                    ->iconColor('danger')
                    ->collapsible()
                    ->persistCollapsed()
                    ->schema([
                        TextEntry::make('info')
                            ->hiddenLabel()
                            ->state(trans('admin/dashboard.sections.intro-support.content')),
                        TextEntry::make('extra')
                            ->hiddenLabel()
                            ->state(trans('admin/dashboard.sections.intro-support.extra_note')),
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
