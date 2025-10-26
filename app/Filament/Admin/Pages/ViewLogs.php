<?php

namespace App\Filament\Admin\Pages;

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
    public function getHeaderActions(): array
    {
        return [
            DeleteAction::make(withTooltip: true)
                ->icon('tabler-trash')->iconSize(IconSize::Medium),
            DownloadAction::make(withTooltip: true)
                ->icon('tabler-file-download')->iconSize(IconSize::Medium),
            Action::make('uploadLogs')
                ->button()
                ->hiddenLabel()
                ->icon('tabler-world-upload')->iconSize(IconSize::Medium)
                ->requiresConfirmation()
                ->modalHeading('Upload Logs')
                ->action(function ($action) {
                    $logPath = storage_path('logs/' . $this->record->date);

                    if (!file_exists($logPath)) {
                        Notification::make()
                            ->title('Log file not found')
                            ->body("Could not find log for {$this->record->date}")
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
                                ->title('Failed to upload logs')
                                ->body("HTTP Status: {$response->status()}")
                                ->danger()
                                ->send();

                            return;
                        }

                        $data = $response->json();
                        $url = $data['url'];

                        redirect($url);

                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Failed to upload logs')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();

                        return;
                    }

                    // Show modal with URL
                    $action
                        ->modalHeading('Logs Uploaded')
                        ->modalSubmitAction(false);
                }),
            BackAction::make()
                ->icon('tabler-arrow-left')->iconSize(IconSize::Medium),
        ];
    }
}
