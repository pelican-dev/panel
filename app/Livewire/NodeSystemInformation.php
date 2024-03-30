<?php

namespace App\Livewire;

use App\Models\Node;
use Livewire\Component;

class NodeSystemInformation extends Component
{
    public Node $node;
    public string $sizeClasses;

    public function render()
    {
        return view('livewire.node-system-information');
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <x-filament::icon
                :icon="'tabler-heart-question'"
                @class(['fi-ta-icon-item', $sizeClasses, 'fi-color-custom text-custom-500 dark:text-custom-400', 'fi-color-warning'])
                @style([\Filament\Support\get_color_css_variables('warning', shades: [400, 500], alias: 'tables::columns.icon-column.item')])
            />
        </div>
        HTML;
    }
}
