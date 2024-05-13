<?php

namespace App\Filament\Resources\NodeResource\Pages;

use App\Filament\Resources\NodeResource;
use App\Filament\Resources\NodeResource\Widgets\NodeMemoryChart;
use App\Filament\Resources\NodeResource\Widgets\NodeStorageChart;
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
                        ->icon('tabler-server-cog')
                        ->schema([
                            Forms\Components\TextInput::make('id')
                                ->label('Node ID')
                                ->disabled(),
                            Forms\Components\TextInput::make('uuid')
                                ->label('Node UUID')
                                ->hintAction(CopyAction::make())
                                ->columnSpan(2)
                                ->disabled(),
                            Forms\Components\TagsInput::make('tags')
                                ->label('Tags')
                                ->disabled()
                                ->placeholder('Not Implemented')
                                ->hintIcon('tabler-question-mark')
                                ->hintIconTooltip('Not Implemented')
                                ->columnSpan(1),
                            Forms\Components\ToggleButtons::make('public')
                                ->label('Automatic Allocation')->inline()
                                ->columnSpan(1)
                                ->options([
                                    true => 'Yes',
                                    false => 'No',
                                ])
                                ->colors([
                                    true => 'success',
                                    false => 'danger',
                                ]),
                            Forms\Components\ToggleButtons::make('maintenance_mode')
                                ->label('Maintenance Mode')->inline()
                                ->columnSpan(1)
                                ->hinticon('tabler-question-mark')
                                ->hintIconTooltip("If the node is marked 'Under Maintenance' users won't be able to access servers that are on this node.")
                                ->options([
                                    true => 'Enable',
                                    false => 'Disable',
                                ])
                                ->colors([
                                    true => 'danger',
                                    false => 'success',
                                ]),
                            Forms\Components\TextInput::make('upload_size')
                                ->label('Upload Limit')
                                ->hintIcon('tabler-question-mark')
                                ->hintIconTooltip('Enter the maximum size of files that can be uploaded through the web-based file manager.')
                                ->columnStart(4)->columnSpan(1)
                                ->numeric()->required()
                                ->minValue(1)
                                ->maxValue(1024)
                                ->suffix('MiB'),
                            Forms\Components\Grid::make()
                                ->columns(6)
                                ->columnSpanFull()
                                ->schema([
                                    Forms\Components\ToggleButtons::make('unlimited_mem')
                                        ->label('Memory')->inlineLabel()->inline()
                                        ->afterStateUpdated(fn (Forms\Set $set) => $set('memory', 0))
                                        ->afterStateUpdated(fn (Forms\Set $set) => $set('memory_overallocate', -1))
                                        ->formatStateUsing(fn (Forms\Get $get) => $get('memory') == 0)
                                        ->live()
                                        ->options([
                                            true => 'Unlimited',
                                            false => 'Limited',
                                        ])
                                        ->colors([
                                            true => 'primary',
                                            false => 'warning',
                                        ])
                                        ->columnSpan(2),
                                    Forms\Components\TextInput::make('memory')
                                        ->dehydratedWhenHidden()
                                        ->hidden(fn (Forms\Get $get) => $get('unlimited_mem'))
                                        ->label('Memory Limit')->inlineLabel()
                                        ->suffix('MiB')
                                        ->required()
                                        ->columnSpan(2)
                                        ->numeric(),
                                    Forms\Components\TextInput::make('memory_overallocate')
                                        ->dehydratedWhenHidden()
                                        ->label('Overallocate')->inlineLabel()
                                        ->required()
                                        ->hidden(fn (Forms\Get $get) => $get('unlimited_mem'))
                                        ->hintIcon('tabler-question-mark')
                                        ->hintIconTooltip('The % allowable to go over the set limit.')
                                        ->columnSpan(2)
                                        ->numeric()
                                        ->maxValue(100)
                                        ->suffix('%'),
                                ]),
                            Forms\Components\Grid::make()
                                ->columns(6)
                                ->columnSpanFull()
                                ->schema([
                                    Forms\Components\ToggleButtons::make('unlimited_disk')
                                        ->label('Disk')->inlineLabel()->inline()
                                        ->live()
                                        ->afterStateUpdated(fn (Forms\Set $set) => $set('disk', 0))
                                        ->afterStateUpdated(fn (Forms\Set $set) => $set('disk_overallocate', -1))
                                        ->formatStateUsing(fn (Forms\Get $get) => $get('disk') == 0)
                                        ->options([
                                            true => 'Unlimited',
                                            false => 'Limited',
                                        ])
                                        ->colors([
                                            true => 'primary',
                                            false => 'warning',
                                        ])
                                        ->columnSpan(2),
                                    Forms\Components\TextInput::make('disk')
                                        ->dehydratedWhenHidden()
                                        ->hidden(fn (Forms\Get $get) => $get('unlimited_disk'))
                                        ->label('Disk Limit')->inlineLabel()
                                        ->suffix('MB')
                                        ->required()
                                        ->columnSpan(2)
                                        ->numeric(),
                                    Forms\Components\TextInput::make('disk_overallocate')
                                        ->dehydratedWhenHidden()
                                        ->hidden(fn (Forms\Get $get) => $get('unlimited_disk'))
                                        ->label('Overallocate')->inlineLabel()
                                        ->hintIcon('tabler-question-mark')
                                        ->hintIconTooltip('The % allowable to go over the set limit.')
                                        ->columnSpan(2)
                                        ->required()
                                        ->numeric()
                                        ->maxValue(100)
                                        ->suffix('%'),
                                ]),
                        ]),
                    Tabs\Tab::make('Configuration File')
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
                ]),
        ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $node = Node::findOrFail($data['id']);

        $data['config'] = $node->getYamlConfiguration();

        return $data;
    }

    protected function getFormActions(): array
    {
        return [];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->disabled(fn (Node $node) => $node->servers()->count() > 0)
                ->label(fn (Node $node) => $node->servers()->count() > 0 ? 'Node Has Servers' : 'Delete'),
            $this->getSaveFormAction()->formId('form'),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            NodeStorageChart::class,
            NodeMemoryChart::class,
        ];
    }
}
