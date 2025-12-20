<?php

namespace App\Filament\Admin\Pages;

use App\Traits\ResolvesRecordDate;
use Boquizo\FilamentLogViewer\Actions\BackAction;
use Boquizo\FilamentLogViewer\Actions\DeleteAction;
use Boquizo\FilamentLogViewer\Actions\DownloadAction;
use Boquizo\FilamentLogViewer\Pages\ViewLog as BaseViewLog;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconSize;
use Illuminate\Support\Facades\Http;

class ViewLogs extends BaseViewLog
{
    use ResolvesRecordDate;

    public function getHeaderActions(): array
    {
        return [
            BackAction::make()
                ->icon('tabler-arrow-left')->iconSize(IconSize::ExtraLarge)->iconButton(),
            DeleteAction::make(withTooltip: true)
                ->iconSize(IconSize::ExtraLarge)->iconButton(),
            DownloadAction::make(withTooltip: true)
                ->icon('tabler-file-download')->iconSize(IconSize::ExtraLarge)->iconButton(),
            Action::make('uploadLogs')
                ->hiddenLabel()
                ->icon('tabler-world-upload')->iconSize(IconSize::ExtraLarge)->iconButton()
                ->requiresConfirmation()
                ->tooltip(trans('admin/log.actions.upload_tooltip', ['url' => 'logs.pelican.dev']))
                ->modalHeading(trans('admin/log.actions.upload_logs'))
                ->modalDescription(fn () => trans('admin/log.actions.upload_logs_description', ['file' => $this->resolveRecordDate(), 'url' => 'https://logs.pelican.dev']))
                ->action(function () {
                    $logPath = storage_path('logs/' . $this->resolveRecordDate());

                    if (!file_exists($logPath)) {
                        Notification::make()
                            ->title(trans('admin/log.actions.log_not_found'))
                            ->body(trans('admin/log.actions.log_not_found_description', ['filename' => $this->resolveRecordDate()]))
                            ->danger()
                            ->send();

                        return;
                    }

                    $lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    $totalLines = count($lines);
                    $uploadLines = $totalLines <= 1000 ? $lines : array_slice($lines, -1000);
                    $content = implode("\n", $uploadLines);

                    $logUrl = 'https://logs.pelican.dev';
                    try {
                        $response = Http::timeout(10)->asMultipart()->post($logUrl, [
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
        ];
    }
}
