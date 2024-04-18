<?php

namespace App\Filament\Resources\NodeResource\Pages;

use App\Filament\Resources\NodeResource;
use App\Models\Allocation;
use App\Models\Node;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\HtmlString;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class EditNode extends EditRecord
{
    protected static string $resource = NodeResource::class;

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Tabs::make('Tabs')
                ->columns([
                    'default' => 2,
                    'sm' => 3,
                    'md' => 3,
                    'lg' => 4,
                ])
                ->persistTabInQueryString()
                ->columnSpanFull()
                ->tabs([
                    Tabs\Tab::make('Basic Settings')
                        ->icon('tabler-server')
                        ->schema((new CreateNode())->form($form)->getComponents()),
                    Tabs\Tab::make('Advanced Settings')
                        ->icon('tabler-server-cog'),
                    Tabs\Tab::make('Configuration')
                        ->icon('tabler-code')
                        ->schema([
                            Forms\Components\Placeholder::make('instructions')
                                ->columnSpanFull()
                                ->content(new HtmlString('
                                  Save this file to your <span title="usually /etc/pelican/">daemon\'s root directory</span>, named <code>config.yml</code>
                            ')),
                            Forms\Components\Textarea::make('config')
                                ->label('/etc/pelican/config.yml')
                                ->disabled()
                                ->rows(19)
                                ->hintAction(CopyAction::make())
                                ->columnSpanFull(),
                        ]),
                    Tabs\Tab::make('Allocations')
                        ->icon('tabler-plug-connected')
                        ->columns(4)
                        ->schema([
                            Forms\Components\Repeater::make('allocations')
                                ->orderColumn('server_id')
                                ->columnSpan(1)
                                ->columns(4)
                                ->relationship()
                                ->addActionLabel('Create New Allocation')
                                ->addAction(fn ($action) => $action->color('info'))
                                ->schema([
                                    Forms\Components\TextInput::make('ip')
                                        ->label('IP Address'),
                                    Forms\Components\TextInput::make('ip_alias')
                                        ->label('Alias'),
                                    Forms\Components\TextInput::make('port')
                                        ->minValue(0)
                                        ->maxValue(65535)
                                        ->numeric(),
                                    Forms\Components\TextInput::make('server')
                                        ->formatStateUsing(fn (Allocation $allocation) => $allocation->server?->name)
                                        ->readOnly()
                                        ->placeholder('No Server'),
                                ]),
                            Forms\Components\Section::make('Assign New Allocations')
                                ->columnSpan(2)
                                ->inlineLabel()
                                ->headerActions([
                                    Forms\Components\Actions\Action::make('submit')
                                        ->color('success')
                                        ->action(function () {
                                            // ...
                                        }),
                                ])
                                ->schema([
                                    Forms\Components\TextInput::make('ip')
                                        ->label('IP Address')
                                        ->placeholder('0.0.0.0')
                                        ->helperText('IP address to assign ports to')
                                        ->columnSpanFull(),
                                    Forms\Components\TextInput::make('ip_alias')
                                        ->label('Alias')
                                        ->placeholder('minecraft')
                                        ->helperText('Display name to help you remember')
                                        ->columnSpanFull(),
                                    Forms\Components\TextInput::make('ports')
                                        ->label('Ports')
                                        ->placeholder('25565')
                                        ->helperText('Individual ports or port ranges here separated by commas or spaces')
                                        ->columnSpanFull(),
                                ]),
                        ]),
                ]),
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
