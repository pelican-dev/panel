<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\WebhookResource\Pages;
use App\Filament\Admin\Resources\WebhookResource\Pages\EditWebhookConfiguration;
use App\Livewire\AlertBanner;
use App\Models\WebhookConfiguration;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Forms\Set;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Features\SupportEvents\HandlesEvents;
use App\Enums\WebhookType;

class WebhookResource extends Resource
{
    use HandlesEvents;

    protected static ?string $model = WebhookConfiguration::class;

    protected static ?string $navigationIcon = 'tabler-webhook';

    protected static ?string $recordTitleAttribute = 'description';

    public static function getNavigationLabel(): string
    {
        return trans('admin/webhook.nav_title');
    }

    public static function getModelLabel(): string
    {
        return trans('admin/webhook.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('admin/webhook.model_label_plural');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('admin/dashboard.advanced');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('type')
                    ->icon(fn ($state) => WebhookType::from($state)->icon())
                    ->color(Color::Hex(WebhookType::Discord->color())),
                TextColumn::make('endpoint')
                    ->label(trans('admin/webhook.table.endpoint'))
                    ->wrap()
                    ->formatStateUsing(fn ($state) => str($state)->after('://'))
                    ->limit(60),
                TextColumn::make('description')
                    ->label(trans('admin/webhook.table.description')),
            ])
            ->actions([
                ViewAction::make()
                    ->hidden(fn ($record) => static::canEdit($record)),
                EditAction::make(),
                ReplicateAction::make()
                    ->iconButton()
                    ->tooltip(trans('filament-actions::replicate.single.label'))
                    ->modal(false)
                    ->excludeAttributes(['created_at', 'updated_at'])
                    ->beforeReplicaSaved(function (WebhookConfiguration $record, WebhookConfiguration $replica) {
                        $replica->description = $record->description . ' Copy ' . now()->format('Y-m-d H:i:s');
                    })
                    ->successRedirectUrl(fn (WebhookConfiguration $replica) => EditWebhookConfiguration::getUrl(['record' => $replica])),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make(),
            ])
            ->emptyStateIcon('tabler-webhook')
            ->emptyStateDescription('')
            ->emptyStateHeading(trans('admin/webhook.no_webhooks'))
            ->emptyStateActions([
                CreateAction::make(),
            ])
            ->persistFiltersInSession()
            ->filters([
                SelectFilter::make('type')
                    ->options(WebhookType::class)
                    ->attribute('type'),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ToggleButtons::make('type')
                    ->live()
                    ->inline()
                    ->options(WebhookType::class)
                    ->default('standalone')
                    ->icons([
                        'standalone' => WebhookType::Standalone->icon(),
                        'discord' => WebhookType::Discord->icon(),
                    ])
                    ->colors([
                        'standalone' => null,
                        'discord' => Color::Hex(WebhookType::Discord->color()),
                    ])
                    ->afterStateHydrated(function ($state, Set $set, Get $get) {
                        if ($state === WebhookType::Discord->value) {
                            AlertBanner::make()
                                ->title('Help')
                                ->body('You have to wrap variable name in between {{ }} for example if you want to get the name from the api you can use {{name}}.<br>Shown variables on the preview aren\'t real, they are just examples.')
                                ->icon('tabler-question-mark')
                                ->info()
                                ->send();
                        }
                    })
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        if ($state === WebhookType::Discord->value) {
                            $payload = $get('payload');
                            if (is_array($payload)) {
                                foreach ($payload as $key => $value) {
                                    if ($key === 'allowed_mentions' && isset($value['parse'])) {
                                        $set('allowed_mentions.parse', $value['parse']);
                                    } elseif ($key === 'embeds') {
                                        $set('embeds', $value);
                                    } elseif ($get($key) === null) {
                                        $set($key, $value);
                                    }
                                }
                            }
                            AlertBanner::make()
                                ->title('Help')
                                ->body('You have to wrap variable name in between {{ }} for example if you want to get the name from the api you can use {{name}}.<br>Shown variables on the preview aren\'t real, they are just examples.')
                                ->icon('tabler-question-mark')
                                ->info()
                                ->send();
                        }
                    }),
                TextInput::make('description')
                    ->label(trans('admin/webhook.description'))
                    ->required(),
                TextInput::make('endpoint')
                    ->label(trans('admin/webhook.endpoint'))
                    ->activeUrl()
                    ->required()
                    ->columnSpanFull()
                    ->afterStateUpdated(fn ($state, Set $set) => $set('type', str($state)->contains('discord.com') ? WebhookType::Discord->value : WebhookType::Standalone->value)),
                Section::make('Discord')
                    ->hidden(fn (Get $get) => $get('type') === WebhookType::Standalone->value)
                    ->dehydratedWhenHidden()
                    ->afterStateUpdated(fn ($livewire) => $livewire->dispatch('refresh-widget'))
                    ->schema(fn () => self::getDiscordFields())
                    ->view('filament.components.webhooksection')
                    ->aside()
                    ->formBefore(),
                Section::make('Events')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        CheckboxList::make('events')
                            ->lazy()
                            ->options(fn () => WebhookConfiguration::filamentCheckboxList())
                            ->searchable()
                            ->bulkToggleable()
                            ->columns(3)
                            ->columnSpanFull()
                            ->gridDirection('row')
                            ->required(),
                    ]),
            ]);
    }

    /** @return array<array-key, mixed> */
    private static function getDiscordFields(): array
    {
        return [
            Section::make('Profile')
                ->collapsible()
                ->schema([
                    TextInput::make('username')
                        ->live()
                        ->label('Username'),
                    TextInput::make('avatar_url')
                        ->live(debounce: 500)
                        ->label('Avatar Url'),
                ]),
            Section::make('Message')
                ->collapsible()
                ->schema([
                    TextInput::make('content')
                        ->label('Message')
                        ->live()
                        ->required(fn (Get $get) => !filled($get('embeds'))),
                    TextInput::make('thread_name')
                        ->label('Forum Thread Name'),
                    CheckboxList::make('flags')
                        ->label('Flags')
                        ->options([
                            (1 << 2) => 'Suppress Embeds',
                            (1 << 12) => 'Suppress Notifications',
                        ])
                        ->descriptions([
                            (1 << 2) => 'Do not include any embeds when serializing this message',
                            (1 << 12) => 'This message will not trigger push and desktop notifications',
                        ]),
                    CheckboxList::make('allowed_mentions.parse')
                        ->label('Allowed Mentions')
                        ->options([
                            'roles' => 'Roles',
                            'users' => 'Users',
                            'everyone' => '@everyone & @here',
                        ]),
                ]),
            Repeater::make('embeds')
                ->live()
                ->itemLabel(fn (array $state) => $state['title'])
                ->addActionLabel('Add embed')
                ->required(fn (Get $get) => !filled($get('content')))
                ->reorderable()
                ->collapsible()
                ->maxItems(10)
                ->schema([
                    Section::make('Author')
                        ->collapsible()
                        ->collapsed()
                        ->schema([
                            TextInput::make('author.name')
                                ->live()
                                ->label('Author')
                                ->required(fn (Get $get) => filled($get('author.url')) || filled($get('author.icon_url'))),
                            TextInput::make('author.url')
                                ->live(debounce: 500)
                                ->label('Author URL'),
                            TextInput::make('author.icon_url')
                                ->live(debounce: 500)
                                ->label('Author Icon URL'),
                        ]),
                    Section::make('Body')
                        ->collapsible()
                        ->collapsed()
                        ->schema([
                            TextInput::make('title')
                                ->live()
                                ->label('Title')
                                ->required(fn (Get $get) => $get('description') === null),
                            Textarea::make('description')
                                ->live()
                                ->label('Description')
                                ->required(fn (Get $get) => $get('title') === null),
                            ColorPicker::make('color')
                                ->live()
                                ->label('Embed Color')
                                ->hex(),
                            TextInput::make('url')
                                ->live(debounce: 500)
                                ->label('URL'),
                        ]),
                    Section::make('Images')
                        ->collapsible()
                        ->collapsed()
                        ->schema([
                            TextInput::make('image.url')
                                ->live(debounce: 500)
                                ->label('Image URL'),
                            TextInput::make('thumbnail.url')
                                ->live(debounce: 500)
                                ->label('Thumbnail URL'),
                        ]),
                    Section::make('Footer')
                        ->collapsible()
                        ->collapsed()
                        ->schema([
                            TextInput::make('footer.text')
                                ->live()
                                ->label('Footer'),
                            Checkbox::make('has_timestamp')
                                ->live()
                                ->label('Has Timestamp'),
                            TextInput::make('footer.icon_url')
                                ->live(debounce: 500)
                                ->label('Footer Icon URL'),
                        ]),
                    Section::make('Fields')
                        ->collapsible()->collapsed()
                        ->schema([
                            Repeater::make('fields')
                                ->reorderable()
                                ->collapsible()
                                ->schema([
                                    TextInput::make('name')
                                        ->live()
                                        ->label('Field Name')
                                        ->required(),
                                    Textarea::make('value')
                                        ->live()
                                        ->label('Field Value')
                                        ->rows(4)
                                        ->required(),
                                    Checkbox::make('inline')
                                        ->live()
                                        ->label('Inline Field'),
                                ]),
                        ]),
                ]),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebhookConfigurations::route('/'),
            'create' => Pages\CreateWebhookConfiguration::route('/create'),
            'edit' => Pages\EditWebhookConfiguration::route('/{record}/edit'),
        ];
    }
}
