<?php

namespace App\Filament\Components\Tables\Columns;

use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Support\Facades\Blade;

class NodeHealthColumn extends IconColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(trans('admin/node.table.health'));

        $this->alignCenter();
    }

    public function toEmbeddedHtml(): string
    {
        $alignment = $this->getAlignment();

        $attributes = $this->getExtraAttributeBag()
            ->class([
                'fi-ta-icon',
                'fi-inline' => $this->isInline(),
                'fi-ta-icon-has-line-breaks' => $this->isListWithLineBreaks(),
                'fi-wrapped' => $this->canWrap(),
                ($alignment instanceof Alignment) ? "fi-align-{$alignment->value}" : (is_string($alignment) ? $alignment : ''),
            ])
            ->toHtml();

        // TODO: poll every 10 secs
        return Blade::render(<<<'BLADE'
            <div <?= $attributes ?>>
                @livewire('node-system-information', ['node' => $record, 'lazy' => true])
            </div>
        BLADE, [
            'attributes' => $attributes,
            'record' => $this->getRecord(),
        ]);
    }
}
