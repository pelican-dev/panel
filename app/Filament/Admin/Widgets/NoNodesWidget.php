<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\NodeResource\Pages\CreateNode;
use App\Models\Node;
use Filament\Actions\CreateAction;
use Filament\Widgets\Widget;

class NoNodesWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.no-nodes-widget';

    protected static bool $isLazy = false;

    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return Node::count() <= 0;
    }

    public function getViewData(): array
    {
        return [
            'actions' => [
                CreateAction::make()
                    ->label(trans('admin/dashboard.sections.intro-first-node.button_label'))
                    ->icon('tabler-server-2')
                    ->url(CreateNode::getUrl()),
            ],
        ];
    }
}
