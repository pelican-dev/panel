<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\TablerIcon;
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
                    ->icon(TablerIcon::HeartFilled)
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
                        Action::make('db_donate')
                            ->label(trans('admin/dashboard.sections.intro-support.button_donate'))
                            ->icon(TablerIcon::Cash)
                            ->url('https://pelican.dev/donate', true)
                            ->color('success'),
                    ]),
            ]);
    }
}
