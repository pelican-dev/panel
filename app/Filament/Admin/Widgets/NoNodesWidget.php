<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\NodeResource\Pages\CreateNode;
use App\Models\Node;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;

class NoNodesWidget extends FormWidget
{
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return Node::count() <= 0;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(trans('admin/dashboard.sections.intro-first-node.heading'))
                    ->icon('tabler-server-2')
                    ->iconColor('primary')
                    ->collapsible()
                    ->persistCollapsed()
                    ->schema([
                        Placeholder::make('')
                            ->content(trans('admin/dashboard.sections.intro-first-node.content')),
                    ])
                    ->headerActions([
                        Action::make('create-node')
                            ->label(trans('admin/dashboard.sections.intro-first-node.button_label'))
                            ->icon('tabler-server-2')
                            ->url(CreateNode::getUrl()),
                    ]),
            ]);
    }
}
