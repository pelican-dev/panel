<?php

namespace App\Services\Pwa;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions as SchemaActions;
use Filament\Schemas\Components\Group;

class PwaActions
{
    public static function make(): Group
    {
        return Group::make()
            ->columns(['default' => 1, 'lg' => 5])
            ->extraAttributes(['class' => 'gap-4'])
            ->schema([
                SchemaActions::make([
                    Action::make('install')
                        ->label(trans('pwa.actions.install'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->action(fn () => Notification::make()->title(trans('pwa.errors.unsupported'))->warning()->send())
                        ->extraAttributes([
                            'onclick' => "if(!window.triggerPwaInstall()){ \$wire.call('mountAction', 'install'); } return false;",
                        ]),
                ])->fullWidth(),

                SchemaActions::make([
                    Action::make('notifications')
                        ->label(trans('pwa.actions.request_notifications'))
                        ->icon('heroicon-o-bell-snooze')
                        ->color('info')
                        ->extraAttributes(['onclick' => 'window.pwaRequestNotifications?.(); return false;']),
                ])->fullWidth(),

                SchemaActions::make([
                    Action::make('subscribe')
                        ->label(trans('pwa.actions.subscribe'))
                        ->icon('heroicon-o-check-circle')
                        ->color('primary')
                        ->extraAttributes(['onclick' => 'window.pwaRegisterPush?.(); return false;']),
                ])->fullWidth(),

                SchemaActions::make([
                    Action::make('unsubscribe')
                        ->label(trans('pwa.actions.unsubscribe'))
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->extraAttributes(['onclick' => 'window.pwaUnregisterPush?.(); return false;']),
                ])->fullWidth(),

                SchemaActions::make([
                    Action::make('test')
                        ->label(trans('pwa.actions.test_push'))
                        ->icon('heroicon-o-paper-airplane')
                        ->color('warning')
                        ->visible(fn () => (bool) app(PwaSettingsRepository::class)->get('push_enabled', false)) // @phpstan-ignore myCustomRules.forbiddenGlobalFunctions
                        ->action(fn () => Notification::make()->title(trans('pwa.notifications.test_sent'))->success()->send())
                        ->extraAttributes(['onclick' => <<<JS
                            const btn = event.target;
                            btn.disabled = true;
                            fetch(window.pwaConfig.routes.test, { 
                                method: 'POST', 
                                headers: { 
                                    'X-CSRF-TOKEN': pwaCsrfToken(),
                                    'Accept': 'application/json'
                                } 
                            }).then(res => {
                                btn.disabled = false;
                                if(res.ok) {
                                    \$wire.call('mountAction', 'test');
                                }
                            }).catch(() => {
                                btn.disabled = false;
                            });
                            return false;
                        JS]),
                ])->fullWidth(),
            ]);
    }
}
