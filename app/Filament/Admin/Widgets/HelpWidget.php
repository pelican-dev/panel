<?php

namespace App\Filament\Admin\Widgets;

use Exception;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HelpWidget extends FormWidget
{
    protected static ?int $sort = 4;

    /**
     * @throws Exception
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(trans('admin/dashboard.sections.intro-help.heading'))
                    ->icon('tabler-question-mark')
                    ->iconColor('info')
                    ->collapsible()
                    ->persistCollapsed()
                    ->schema([
                        TextEntry::make('info')
                            ->hiddenLabel()
                            ->state(trans('admin/dashboard.sections.intro-help.content')),
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
