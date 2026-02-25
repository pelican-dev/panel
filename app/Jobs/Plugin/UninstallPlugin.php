<?php

namespace App\Jobs\Plugin;

use App\Filament\Admin\Resources\Plugins\Pages\ListPlugins;
use App\Models\Plugin;
use App\Models\User;
use App\Services\Helpers\PluginService;
use Exception;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UninstallPlugin implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public User $user, public Plugin $plugin) {}

    public function handle(PluginService $pluginService): void
    {
        try {
            $pluginService->uninstallPlugin($this->plugin);

            Notification::make()
                ->success()
                ->title(trans('admin/plugin.notifications.uninstalled'))
                ->body($this->plugin->name)
                ->actions([
                    Action::make('goto_plugins')
                        ->label(trans('admin/plugin.notifications.goto_plugins'))
                        ->url(ListPlugins::getUrl(panel: 'admin')),
                ])
                ->sendToDatabase($this->user);
        } catch (Exception $exception) {
            report($exception);

            Notification::make()
                ->danger()
                ->title(trans('admin/plugin.notifications.uninstall_failed'))
                ->body($exception->getMessage())
                ->sendToDatabase($this->user);
        }
    }

    public function uniqueId(): string
    {
        return 'plugin:uninstall:' . $this->plugin->id;
    }
}
