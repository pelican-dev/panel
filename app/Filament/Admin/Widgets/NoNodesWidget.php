<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Nodes\Pages\CreateNode;
use App\Models\Node;
use Exception;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NoNodesWidget extends FormWidget
{
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return Node::count() <= 0;
    }

    /**
     * @throws Exception
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(trans('admin/dashboard.sections.intro-first-node.heading'))
                    ->icon('tabler-server-2')
                    ->iconColor('primary')
                    ->collapsible()
                    ->persistCollapsed()
                    ->schema([
                        TextEntry::make('info')
                            ->hiddenLabel()
                            ->state(trans('admin/dashboard.sections.intro-first-node.content')),
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
