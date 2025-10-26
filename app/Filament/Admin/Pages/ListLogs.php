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
                LevelColumn::make(Level::ALL),
                LevelColumn::make(Level::Error),
                LevelColumn::make(Level::Warning),
                LevelColumn::make(Level::Notice),
                LevelColumn::make(Level::Info),
                LevelColumn::make(Level::Debug),
            ])
            ->recordActions([
                ViewLogAction::make()
                    ->icon('tabler-file-description')->iconSize(IconSize::Medium),
                DownloadAction::make()
                    ->icon('tabler-file-download')->iconSize(IconSize::Medium),
                Action::make('uploadLogs')
                    ->button()
                    ->hiddenLabel()
                    ->icon('tabler-world-upload')->iconSize(IconSize::Medium)
                    ->requiresConfirmation()
                    ->modalHeading(trans('admin/log.actions.upload_log'))
                    ->action(function ($record) {
                        $logPath = storage_path('logs/' . $record['date']);

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

                        $hbUrl = 'https://logs.pelican.dev';
                        try {
                            $response = Http::asMultipart()->post($hbUrl, [
                                [
                                    'name' => 'c',
                                    'contents' => $content,
                                ],
                                [
                                    'name' => 'e',
                                    'contents' => '14d',
                                ],
                            ]);

                            if ($response->failed()) {
                                Notification::make()
                                    ->title(trans('admin/log.actions.filed_to_upload'))
                                    ->body(trans('admin/log.actions.filed_to_upload', ['status' => $response->status()]))
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
                                ->title(trans('admin/log.actions.filed_to_upload'))
                                ->body($e->getMessage())
                                ->danger()
                                ->send();

                            return;
                        }
                    }),
                DeleteAction::make()
                    ->icon('tabler-trash')->iconSize(IconSize::Medium),
            ]);
    }
}
