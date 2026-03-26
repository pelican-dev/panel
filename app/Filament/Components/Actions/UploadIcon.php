<?php

namespace App\Filament\Components\Actions;

use App\Enums\TablerIcon;
use App\Models\Traits\HasIcon;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Facades\Http;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UploadIcon extends Action
{
    /** @var string[] */
    protected ?array $iconFormats = null;

    public static function getDefaultName(): ?string
    {
        return 'upload_icon';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->hiddenLabel();

        $this->tooltip(trans('admin/egg.import.import_icon'));

        $this->icon(TablerIcon::PhotoUp);

        $this->modal();

        $this->modalHeading('');

        $this->modalSubmitActionLabel(trans('admin/egg.import.import_icon'));

        $this->schema([
            Tabs::make()
                ->contained(false)
                ->tabs([
                    Tab::make(trans('admin/egg.import.url'))
                        ->schema([
                            TextInput::make('icon_url')
                                ->label(trans('admin/egg.import.icon_url'))
                                ->reactive()
                                ->autocomplete(false)
                                ->debounce(500)
                                ->afterStateUpdated(function ($state, Set $set) {
                                    if (!$state) {
                                        $set('icon_url_error', null);

                                        return;
                                    }

                                    try {
                                        $this->validateIconUrl($state);

                                        $set('icon_url_error', null);
                                    } catch (Exception $exception) {
                                        $set('icon_url_error', $exception->getMessage());
                                    }
                                }),
                            TextEntry::make('icon_url_error')
                                ->hiddenLabel()
                                ->visible(fn (Get $get) => $get('icon_url_error') !== null)
                                ->afterStateHydrated(fn (Get $get) => $get('icon_url_error')),
                            Image::make(fn (Get $get) => $get('icon_url'), '')
                                ->imageSize(150)
                                ->visible(fn (Get $get) => $get('icon_url') && !$get('icon_url_error'))
                                ->alignCenter(),
                        ]),
                    Tab::make(trans('admin/egg.import.file'))
                        ->schema([
                            FileUpload::make('icon')
                                ->hiddenLabel()
                                ->previewable()
                                ->openable(false)
                                ->downloadable(false)
                                ->maxSize(256)
                                ->maxFiles(1)
                                ->columnSpanFull()
                                ->alignCenter()
                                ->imageEditor()
                                ->image()
                                ->acceptedFileTypes(fn () => $this->getIconFormats())
                                ->saveUploadedFileUsing(fn (TemporaryUploadedFile $file, $record) => $record->writeIcon($file->getClientOriginalExtension(), $file->getContent())),
                        ]),
                ]),
        ]);

        $this->action(function (array $data, $record) {
            if (!empty($data['icon_url'])) {
                $this->validateIconUrl($data['icon_url']);

                $content = Http::timeout(5)->connectTimeout(1)->withoutRedirecting()->get($data['icon_url'])->body();

                if (empty($content)) {
                    throw new Exception(trans('admin/egg.import.invalid_url'));
                }

                $extension = strtolower(pathinfo(parse_url($data['icon_url'], PHP_URL_PATH), PATHINFO_EXTENSION));

                $record->writeIcon($extension, $content);

                Notification::make()
                    ->title(trans('admin/egg.import.icon_updated'))
                    ->success()
                    ->send();
            } elseif (!empty($data['icon'])) {
                Notification::make()
                    ->title(trans('admin/egg.import.icon_updated'))
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title(trans('admin/egg.import.no_icon'))
                    ->warning()
                    ->send();
            }
        });
    }

    protected function validateIconUrl(string $url): void
    {
        if (!in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true)) {
            throw new Exception(trans('admin/egg.import.invalid_url'));
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception(trans('admin/egg.import.invalid_url'));
        }

        $host = parse_url($url, PHP_URL_HOST);
        $ip = gethostbyname($host);

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            throw new Exception(trans('admin/egg.import.no_local_ip'));
        }
    }

    /** @param string[] $iconFormats */
    public function iconFormats(?array $iconFormats): static
    {
        $this->iconFormats = $iconFormats;

        return $this;
    }

    /** @return string[] */
    public function getIconFormats(): array
    {
        return $this->iconFormats ?? array_values(HasIcon::$iconFormats);
    }
}
