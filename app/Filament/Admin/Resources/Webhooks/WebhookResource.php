<?php

namespace App\Filament\Admin\Resources\Webhooks;

use App\Enums\WebhookType;
use App\Filament\Admin\Resources\Webhooks\Pages\CreateWebhookConfiguration;
use App\Filament\Admin\Resources\Webhooks\Pages\EditWebhookConfiguration;
use App\Filament\Admin\Resources\Webhooks\Pages\ListWebhookConfigurations;
use App\Filament\Admin\Resources\Webhooks\Pages\ViewWebhookConfiguration;
use App\Livewire\AlertBanner;
use App\Models\WebhookConfiguration;
use App\Traits\Filament\CanCustomizePages;
use App\Traits\Filament\CanCustomizeRelations;
use App\Traits\Filament\CanModifyForm;
use App\Traits\Filament\CanModifyTable;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Components\Component;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Component as Livewire;
use Livewire\Features\SupportEvents\HandlesEvents;

class WebhookResource extends Resource
{
    use CanCustomizePages;
    use CanCustomizeRelations;
    use CanModifyForm;
    use CanModifyTable;
    use HandlesEvents;

    protected static ?string $model = WebhookConfiguration::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-webhook';

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
        return ($count = static::getModel()::count()) > 0 ? (string) $count : null;
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('admin/dashboard.advanced');
    }

    public static function defaultTable(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('type'),
                TextColumn::make('endpoint')
                    ->label(trans('admin/webhook.table.endpoint'))
                    ->formatStateUsing(fn (string $state) => str($state)->after('://'))
                    ->limit(60)
                    ->wrap(),
                TextColumn::make('description')
                    ->label(trans('admin/webhook.table.description')),
                TextColumn::make('endpoint')
                    ->label(trans('admin/webhook.table.endpoint')),
            ])
            ->recordActions([
                ViewAction::make()
                    ->hidden(fn (WebhookConfiguration $record) => static::getEditAuthorizationResponse($record)->allowed()),
                EditAction::make(),
                ReplicateAction::make()
                    ->iconButton()
                    ->tooltip(trans('filament-actions::replicate.single.label'))
                    ->modal(false)
                    ->excludeAttributes(['created_at', 'updated_at'])
                    ->beforeReplicaSaved(fn (WebhookConfiguration $replica) => $replica->description .= ' Copy ' . now()->format('Y-m-d H:i:s'))
                    ->successRedirectUrl(fn (WebhookConfiguration $replica) => EditWebhookConfiguration::getUrl(['record' => $replica])),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make(),
            ])
            ->emptyStateIcon('tabler-webhook')
            ->emptyStateDescription('')
            ->emptyStateHeading(trans('admin/webhook.no_webhooks'))
            ->persistFiltersInSession()
            ->filters([
                SelectFilter::make('type')
                    ->options(WebhookType::class)
                    ->attribute('type'),
            ]);
    }

    public static function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                ToggleButtons::make('type')
                    ->live()
                    ->inline()
                    ->options(WebhookType::class)
                    ->default(WebhookType::Regular),
                TextInput::make('description')
                    ->label(trans('admin/webhook.description'))
                    ->required(),
                TextInput::make('endpoint')
                    ->label(trans('admin/webhook.endpoint'))
                    ->required()
                    ->columnSpanFull()
                    ->afterStateUpdated(fn (string $state, Set $set) => $set('type', str($state)->contains('discord.com') ? WebhookType::Discord : WebhookType::Regular)),
                Section::make(trans('admin/webhook.regular'))
                    ->hidden(fn (Get $get) => $get('type') === WebhookType::Discord)
                    ->schema(fn () => self::getRegularFields())
                    ->headerActions([
                        Action::make('reset_headers')
                            ->label(trans('admin/webhook.reset_headers'))
                            ->color('danger')
                            ->icon('heroicon-o-trash')
                            ->action(fn (Get $get, Set $set) => $set('headers', [
                                'X-Webhook-Event' => '{{event}}',
                            ])),
                    ])
                    ->formBefore(),
                Section::make(trans('admin/webhook.discord'))
                    ->hidden(fn (Get $get) => $get('type') === WebhookType::Regular)
                    ->afterStateUpdated(fn (Livewire $livewire) => $livewire->dispatch('refresh-widget'))
                    ->schema(fn () => self::getDiscordFields())
                    ->view('filament.components.webhooksection')
                    ->aside()
                    ->formBefore()
                    ->columnSpanFull(),
                Section::make(trans('admin/webhook.events'))
                    ->schema([
                        CheckboxList::make('events')
                            ->live()
                            ->options(fn () => WebhookConfiguration::filamentCheckboxList())
                            ->searchable()
                            ->bulkToggleable()
                            ->columns(3)
                            ->columnSpanFull()
                            ->required(),
                    ]),
            ]);
    }

    /** @return Component[]
     * @throws Exception
     */
    private static function getRegularFields(): array
    {
        return [
            KeyValue::make('headers')
                ->label(trans('admin/webhook.headers'))
                ->default(fn () => [
                    'X-Webhook-Event' => '{{event}}',
                ]),
        ];
    }

    /** @return Component[]
     * @throws Exception
     */
    private static function getDiscordFields(): array
    {
        return [
            Section::make(trans('admin/webhook.discord_message.profile'))
                ->collapsible()
                ->schema([
                    TextInput::make('username')
                        ->live(debounce: 500)
                        ->label(trans('admin/webhook.discord_message.username')),
                    TextInput::make('avatar_url')
                        ->live(debounce: 500)
                        ->label(trans('admin/webhook.discord_message.avatar_url')),
                ]),
            Section::make(trans('admin/webhook.discord_message.message'))
                ->collapsible()
                ->schema([
                    TextInput::make('content')
                        ->label(trans('admin/webhook.discord_message.message'))
                        ->live(debounce: 500)
                        ->required(fn (Get $get) => empty($get('embeds'))),
                    TextInput::make('thread_name')
                        ->label(trans('admin/webhook.discord_message.forum_thread')),
                    CheckboxList::make('flags')
                        ->label(trans('admin/webhook.discord_embed.flags'))
                        ->options([
                            (1 << 2) => trans('admin/webhook.discord_message.supress_embeds'),
                            (1 << 12) => trans('admin/webhook.discord_message.supress_notifications'),
                        ])
                        ->descriptions([
                            (1 << 2) => trans('admin/webhook.discord_message.supress_embeds_text'),
                            (1 << 12) => trans('admin/webhook.discord_message.supress_notifications_text'),
                        ]),
                    CheckboxList::make('allowed_mentions')
                        ->label(trans('admin/webhook.discord_embed.allowed_mentions'))
                        ->options([
                            'roles' => trans('admin/webhook.discord_embed.roles'),
                            'users' => trans('admin/webhook.discord_embed.users'),
                            'everyone' => trans('admin/webhook.discord_embed.everyone'),
                        ]),
                ]),
            Repeater::make('embeds')
                ->live(debounce: 500)
                ->itemLabel(fn (array $state) => $state['title'])
                ->addActionLabel(trans('admin/webhook.discord_embed.add_embed'))
                ->required(fn (Get $get) => empty($get('content')))
                ->reorderable()
                ->collapsible()
                ->maxItems(10)
                ->schema([
                    Section::make(trans('admin/webhook.discord_embed.author'))
                        ->collapsible()
                        ->collapsed()
                        ->schema([
                            TextInput::make('author.name')
                                ->live(debounce: 500)
                                ->label(trans('admin/webhook.discord_embed.author'))
                                ->required(fn (Get $get) => filled($get('author.url')) || filled($get('author.icon_url'))),
                            TextInput::make('author.url')
                                ->live(debounce: 500)
                                ->label(trans('admin/webhook.discord_embed.author_url')),
                            TextInput::make('author.icon_url')
                                ->live(debounce: 500)
                                ->label(trans('admin/webhook.discord_embed.author_icon_url')),
                        ]),
                    Section::make(trans('admin/webhook.discord_embed.body'))
                        ->collapsible()
                        ->collapsed()
                        ->schema([
                            TextInput::make('title')
                                ->live(debounce: 500)
                                ->label(trans('admin/webhook.discord_embed.title'))
                                ->required(fn (Get $get) => $get('description') === null),
                            Textarea::make('description')
                                ->live(debounce: 500)
                                ->label(trans('admin/webhook.discord_embed.body'))
                                ->required(fn (Get $get) => $get('title') === null),
                            ColorPicker::make('color')
                                ->live(debounce: 500)
                                ->label(trans('admin/webhook.discord_embed.color'))
                                ->hex(),
                            TextInput::make('url')
                                ->live(debounce: 500)
                                ->label(trans('admin/webhook.discord_embed.url')),
                        ]),
                    Section::make(trans('admin/webhook.discord_embed.images'))
                        ->collapsible()
                        ->collapsed()
                        ->schema([
                            TextInput::make('image.url')
                                ->live(debounce: 500)
                                ->label(trans('admin/webhook.discord_embed.image_url')),
                            TextInput::make('thumbnail.url')
                                ->live(debounce: 500)
                                ->label(trans('admin/webhook.discord_embed.image_thumbnail')),
                        ]),
                    Section::make(trans('admin/webhook.discord_embed.footer'))
                        ->collapsible()
                        ->collapsed()
                        ->schema([
                            TextInput::make('footer.text')
                                ->live(debounce: 500)
                                ->label(trans('admin/webhook.discord_embed.footer')),
                            Checkbox::make('has_timestamp')
                                ->live(debounce: 500)
                                ->label(trans('admin/webhook.discord_embed.has_timestamp')),
                            TextInput::make('footer.icon_url')
                                ->live(debounce: 500)
                                ->label(trans('admin/webhook.discord_embed.footer_icon_url')),
                        ]),
                    Section::make(trans('admin/webhook.discord_embed.fields'))
                        ->collapsible()->collapsed()
                        ->schema([
                            Repeater::make('fields')
                                ->reorderable()
                                ->addActionLabel(trans('admin/webhook.discord_embed.add_field'))
                                ->collapsible()
                                ->schema([
                                    TextInput::make('name')
                                        ->live(debounce: 500)
                                        ->label(trans('admin/webhook.discord_embed.field_name'))
                                        ->required(),
                                    Textarea::make('value')
                                        ->live(debounce: 500)
                                        ->label(trans('admin/webhook.discord_embed.field_value'))
                                        ->rows(4)
                                        ->required(),
                                    Checkbox::make('inline')
                                        ->live(debounce: 500)
                                        ->label(trans('admin/webhook.discord_embed.inline_field')),
                                ]),
                        ]),
                ]),
        ];
    }

    public static function sendHelpBanner(): void
    {
        AlertBanner::make('discord_webhook_help')
            ->title(trans('admin/webhook.help'))
            ->body(trans('admin/webhook.help_text'))
            ->icon('tabler-question-mark')
            ->info()
            ->send();
    }

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [
            'index' => ListWebhookConfigurations::route('/'),
            'create' => CreateWebhookConfiguration::route('/create'),
            'view' => ViewWebhookConfiguration::route('/{record}'),
            'edit' => EditWebhookConfiguration::route('/{record}/edit'),
        ];
    }
}
