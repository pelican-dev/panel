<?php

namespace App\Livewire;

use App\Models\Node;
use Filament\Support\Enums\IconSize;
use Filament\Tables\View\Components\Columns\IconColumnComponent\IconComponent;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Attributes\Locked;
use Livewire\Component;

use function Filament\Support\generate_icon_html;

class NodeSystemInformation extends Component
{
    #[Locked]
    public Node $node;

    public function render(): string
    {
        $systemInformation = $this->node->systemInformation();
        $exception = $systemInformation['exception'] ?? null;
        $version = $systemInformation['version'] ?? null;

        if ($exception) {
            $this->js('console.error("' . $exception . '");');
        }

        $tooltip = $exception ? 'Error connecting to node!<br>Check browser console for details.' : $version;

        $icon = 'tabler-heart' . ($exception ? '-off' : 'beat');
        $color = $exception ? 'danger' : 'success';

        return generate_icon_html($icon, attributes: (new ComponentAttributeBag())
            ->merge([
                'x-tooltip' => '{
                    content: "' . $tooltip . '",
                    theme: $store.theme,
                    allowHTML: true,
                    placement: "bottom",
                }',
            ], escape: false)
            ->color(IconComponent::class, $color), size: IconSize::Large)
            ->toHtml();
    }

    public function placeholder(): string
    {
        return generate_icon_html('tabler-heart-question', attributes: (new ComponentAttributeBag())
            ->color(IconComponent::class, 'warning'), size: IconSize::Large)
            ->toHtml();
    }
}
