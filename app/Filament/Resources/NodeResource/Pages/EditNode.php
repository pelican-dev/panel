<?php

namespace App\Filament\Resources\NodeResource\Pages;

use App\Filament\Resources\NodeResource;
use App\Models\Node;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\HtmlString;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class EditNode extends EditRecord
{
    protected static string $resource = NodeResource::class;

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Forms\Components\Wizard\Step::make('Basic')
                        ->description('')
                        ->schema((new CreateNode())->form($form)->getComponents()),
                    Forms\Components\Wizard\Step::make('Configuration')
                        ->description('')
                        ->schema([
                            Forms\Components\Placeholder::make('instructions')
                                ->columnSpanFull()
                                ->content(new HtmlString('
                                  This file should be placed in your daemon\'s root directory (usually <code>/etc/pelican</code>) in a file called <code>config.yml</code>.
                            ')),
                            Forms\Components\Textarea::make('config')
                                ->label('Configuration File')
                                ->disabled()
                                ->rows(19)
                                ->hintAction(CopyAction::make())
                                ->columnSpanFull(),
                        ]),
                ])
                    ->columns(4)
                    ->persistStepInQueryString()
                    ->columnSpanFull()
                    // ->startOnStep($this->getStartStep())
                    // ->cancelAction($this->getCancelFormAction())
                    // ->submitAction($this->getSubmitFormAction())
                    // ->skippable($this->hasSkippableSteps()),
            ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $node = Node::findOrFail($data['id']);

        $data['config'] = $node->getYamlConfiguration();

        return $data;
    }

    protected function getSteps(): array
    {
        return [
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
