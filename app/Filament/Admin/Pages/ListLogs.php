<?php

namespace App\Filament\Admin\Pages;

use Boquizo\FilamentLogViewer\Actions\DeleteAction;
use Boquizo\FilamentLogViewer\Actions\DownloadAction;
use Boquizo\FilamentLogViewer\Actions\ViewLogAction;
use Boquizo\FilamentLogViewer\Pages\ListLogs as BaseListLogs;
use Boquizo\FilamentLogViewer\Tables\Columns\LevelColumn;
use Boquizo\FilamentLogViewer\Tables\Columns\NameColumn;
use Boquizo\FilamentLogViewer\Utils\Level;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;

class ListLogs extends BaseListLogs
{
    protected string $view = 'filament.components.list-logs';

    public function getHeading(): string|null|\Illuminate\Contracts\Support\Htmlable
    {
        return trans('admin/log.navigation.panel_logs');
    }

    public static function table(Table $table): Table
    {
        return parent::table($table)
            ->emptyStateHeading(trans('admin/log.empty_table'))
            ->emptyStateIcon('tabler-check')
            ->columns([
                NameColumn::make('date'),
                LevelColumn::make(Level::ALL)
                    ->tooltip(trans('admin/log.total_logs')),
                LevelColumn::make(Level::Error)
                    ->tooltip(trans('admin/log.error')),
                LevelColumn::make(Level::Warning)
                    ->tooltip(trans('admin/log.warning')),
                LevelColumn::make(Level::Notice)
                    ->tooltip(trans('admin/log.notice')),
                LevelColumn::make(Level::Info)
                    ->tooltip(trans('admin/log.info')),
                LevelColumn::make(Level::Debug)
                    ->tooltip(trans('admin/log.debug')),
            ])
            ->recordActions([
                ViewLogAction::make()
                    ->icon('tabler-file-description')->iconSize(IconSize::Large)->iconButton(),
                DownloadAction::make()
                    ->icon('tabler-file-download')->iconSize(IconSize::Large)->iconButton(),
                Action::make('uploadLogs')
                    ->hiddenLabel()
                    ->icon('tabler-world-upload')->iconSize(IconSize::Large)->iconButton()
                    ->requiresConfirmation()
                    ->modalHeading(trans('admin/log.actions.upload_logs'))
                    ->modalDescription(fn ($record) => trans('admin/log.actions.upload_logs_description', ['file' => $record['date'], 'url' => 'https://logs.pelican.dev']))
                    ->action(function ($record) {
                        $prefix = config('filament-log-viewer.pattern.prefix', 'laravel-');
                        $extension = config('filament-log-viewer.pattern.extension', '.log');
                        $logPath = storage_path('logs/' . $prefix . $record['date'] . $extension);

                        if (!file_exists($logPath)) {
                            Notification::make()
                                ->title(trans('admin/log.actions.log_not_found'))
                                ->body(trans('admin/log.actions.log_not_found_description', ['filename' => $record['date']]))
                                ->danger()
                                ->send();

                            return;
                        }

                        $lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        $totalLines = count($lines);
                        $uploadLines = $totalLines <= 1000 ? $lines : array_slice($lines, -1000);
                        $content = implode("\n", $uploadLines);

                        try {
                            $response = Http::timeout(10)
                                ->asMultipart()
                                ->attach('c', $content)
                                ->attach('e', '14d')
                                ->post('https://logs.pelican.dev');

                            if ($response->failed()) {
                                Notification::make()
                                    ->title(trans('admin/log.actions.failed_to_upload'))
                                    ->body(trans('admin/log.actions.failed_to_upload_description', ['status' => $response->status()]))
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $data = $response->json();
                            $url = $data['url'];

                            Notification::make()
                                ->title(trans('admin/log.actions.log_upload'))
                                ->body("{$url}")
                                ->success()
                                ->actions([
                                    Action::make('viewLogs')
                                        ->label(trans('admin/log.actions.view_logs'))
                                        ->url($url)
                                        ->openUrlInNewTab(true),
                                ])
                                ->persistent()
                                ->send();

                        } catch (\Exception $e) {
                            Notification::make()
                                ->title(trans('admin/log.actions.failed_to_upload'))
                                ->body($e->getMessage())
                                ->danger()
                                ->send();

                            return;
                        }
                    }),
                DeleteAction::make()
                    ->iconSize(IconSize::Medium)->iconButton(),
            ]);
    }
}
