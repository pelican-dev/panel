<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\WebhookResource\Pages;
use App\Filament\Admin\Resources\WebhookResource\Pages\EditWebhookConfiguration;
use App\Livewire\AlertBanner;
use App\Models\WebhookConfiguration;
use App\Traits\Filament\CanCustomizePages;
use App\Traits\Filament\CanCustomizeRelations;
use App\Traits\Filament\CanModifyForm;
use App\Traits\Filament\CanModifyTable;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Pages\PageRegistration;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Forms\Set;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Features\SupportEvents\HandlesEvents;
use App\Enums\WebhookType;

class WebhookResource extends Resource
{
    use CanCustomizePages;
    use CanCustomizeRelations;
    use CanModifyForm;
    use CanModifyTable;
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

    public static function defaultTable(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('type'),
                TextColumn::make('endpoint')
                    ->label(trans('admin/webhook.table.endpoint'))
                    ->wrap()
                    ->formatStateUsing(fn ($state) => str($state)->after('://'))
                    ->limit(60),
                TextColumn::make('description')
                    ->label(trans('admin/webhook.table.description')),
                TextColumn::make('endpoint')
                    ->label(trans('admin/webhook.table.endpoint')),
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

    public static function defaultForm(Form $form): Form
    {
        return $form
            ->schema([
                ToggleButtons::make('type')
                    ->live()
                    ->inline()
                    ->options(WebhookType::class)
                    ->default(WebhookType::Regular->value)
                    ->afterStateHydrated(function ($state) {
                        if ($state === WebhookType::Discord->value) {
                            self::sendHelpBanner();
                        }
                    })
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        if ($state === WebhookType::Discord->value) {
                            $payload = $get('payload');
                            self::sendHelpBanner();
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
                    ->afterStateUpdated(fn ($state, Set $set) => $set('type', str($state)->contains('discord.com') ? WebhookType::Discord->value : WebhookType::Regular->value)),
                Section::make(trans('admin/webhook.discord'))
                    ->hidden(fn (Get $get) => $get('type') === WebhookType::Regular->value)
                    ->dehydratedWhenHidden()
                    ->afterStateUpdated(fn ($livewire) => $livewire->dispatch('refresh-widget'))
                    ->schema(fn () => self::getDiscordFields())
                    ->view('filament.components.webhooksection')
                    ->aside()
                    ->formBefore(),
                Section::make(trans('admin/webhook.regular'))
                    ->hidden(fn (Get $get) => $get('type') === WebhookType::Discord->value)
                    ->dehydratedWhenHidden()
                    ->schema(fn () => self::getRegularFields())
                    ->formBefore(),
                Section::make(trans('admin/webhook.events'))
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
    private static function getRegularFields(): array
    {
        return [
            KeyValue::make('headers')
                ->label(trans('admin/webhook.headers'))
                ->visible(fn (Get $get) => $get('type') === WebhookType::Regular->value),
        ];
    }

    /** @return array<array-key, mixed> */
    private static function getDiscordFields(): array
    {
        return [
            Section::make(trans('admin/webhook.discordmessage.profile'))
                ->collapsible()
                ->schema([
                    TextInput::make('username')
                        ->live()
                        ->label(trans('admin/webhook.discordmessage.username')),
                    TextInput::make('avatar_url')
                        ->live(debounce: 500)
                        ->label(trans('admin/webhook.discordmessage.avatar_url')),
                ]),
            Section::make(trans('admin/webhook.discordmessage.message'))
                ->collapsible()
                ->schema([
                    TextInput::make('content')
                        ->label(trans('admin/webhook.discordmessage.message'))
                        ->live()
                        ->required(fn (Get $get) => !filled($get('embeds'))),
                    TextInput::make('thread_name')
                        ->label(trans('admin/webhook.discordmessage.forum_thread')),
                    CheckboxList::make('flags')
                        ->label('Flags')
                        ->options([
                            (1 << 2) => trans('admin/webhook.discordmessage.supress_embeds'),
                            (1 << 12) => trans('admin/webhook.discordmessage.supress_notifications'),
                        ])
                        ->descriptions([
                            (1 << 2) => trans('admin/webhook.discordmessage.supress_embeds_text'),
                            (1 << 12) => trans('admin/webhook.discordmessage.supress_notifications_text'),
                        ]),
                    CheckboxList::make('allowed_mentions.parse')
                        ->label(trans('admin/webhook.discordembedtable.allowed_mentions'))
                        ->options([
                            'roles' => trans('admin/webhook.discordembedtable.roles'),
                            'users' => trans('admin/webhook.discordembedtable.users'),
                            'everyone' => trans('admin/webhook.discordembedtable.everyone'),
                        ]),
                ]),
            Repeater::make('embeds')
                ->live()
                ->itemLabel(fn (array $state) => $state['title'])
                ->addActionLabel(trans('admin/webhook.discordembedtable.add_embed'))
                ->required(fn (Get $get) => !filled($get('content')))
                ->reorderable()
                ->collapsible()
                ->maxItems(10)
                ->schema([
                    Section::make(trans('admin/webhook.discordembedtable.author'))
                        ->collapsible()
                        ->collapsed()
                        ->schema([
                            TextInput::make('author.name')
                                ->live()
                                ->label(trans('admin/webhook.discordembedtable.author'))
                                ->required(fn (Get $get) => filled($get('author.url')) || filled($get('author.icon_url'))),
                            TextInput::make('author.url')
                                ->live(debounce: 500)
                                ->label(trans('admin/webhook.discordembedtable.author_url')),
                            TextInput::make('author.icon_url')
                                ->live(debounce: 500)
                                ->label(trans('admin/webhook.discordembedtable.author_icon_url')),
                        ]),
                    Section::make(trans('admin/webhook.discordembedtable.body'))
                        ->collapsible()
                        ->collapsed()
                        ->schema([
                            TextInput::make('title')
                                ->live()
                                ->label(trans('admin/webhook.discordembedtable.title'))
                                ->required(fn (Get $get) => $get('description') === null),
                            Textarea::make('description')
                                ->live()
                                ->label(trans('admin/webhook.discordembedtable.body'))
                                ->required(fn (Get $get) => $get('title') === null),
                            ColorPicker::make('color')
                                ->live()
                                ->label(trans('admin/webhook.discordembedtable.color'))
                                ->hex(),
                            TextInput::make('url')
                                ->live(debounce: 500)
                                ->label(trans('admin/webhook.discordembedtable.url')),
                        ]),
                    Section::make(trans('admin/webhook.discordembedtable.images'))
                        ->collapsible()
                        ->collapsed()
                        ->schema([
                            TextInput::make('image.url')
                                ->live(debounce: 500)
                                ->label(trans('admin/webhook.discordembedtable.image_url')),
                            TextInput::make('thumbnail.url')
                                ->live(debounce: 500)
                                ->label(trans('admin/webhook.discordembedtable.image_thumbnail')),
                        ]),
                    Section::make(trans('admin/webhook.discordembedtable.footer'))
                        ->collapsible()
                        ->collapsed()
                        ->schema([
                            TextInput::make('footer.text')
                                ->live()
                                ->label(trans('admin/webhook.discordembedtable.footer')),
                            Checkbox::make('has_timestamp')
                                ->live()
                                ->label(trans('admin/webhook.discordembedtable.has_timestamp')),
                            TextInput::make('footer.icon_url')
                                ->live(debounce: 500)
                                ->label(trans('admin/webhook.discordembedtable.footer_icon_url')),
                        ]),
                    Section::make(trans('admin/webhook.discordembedtable.fields'))
                        ->collapsible()->collapsed()
                        ->schema([
                            Repeater::make('fields')
                                ->reorderable()
                                ->addActionLabel(trans('admin/webhook.discordembedtable.add_field'))
                                ->collapsible()
                                ->schema([
                                    TextInput::make('name')
                                        ->live()
                                        ->label(trans('admin/webhook.discordembedtable.field_name'))
                                        ->required(),
                                    Textarea::make('value')
                                        ->live()
                                        ->label(trans('admin/webhook.discordembedtable.field_value'))
                                        ->rows(4)
                                        ->required(),
                                    Checkbox::make('inline')
                                        ->live()
                                        ->label(trans('admin/webhook.discordembedtable.inline_field')),
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
            'index' => Pages\ListWebhookConfigurations::route('/'),
            'create' => Pages\CreateWebhookConfiguration::route('/create'),
            'view' => Pages\ViewWebhookConfiguration::route('/{record}'),
            'edit' => Pages\EditWebhookConfiguration::route('/{record}/edit'),
        ];
    }
}
