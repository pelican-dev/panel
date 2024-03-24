<?php

namespace App\Filament\Resources\NodeResource\Pages;

use App\Filament\Resources\NodeResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Resources\Pages\EditRecord;

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
