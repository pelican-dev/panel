<?php

use App\Enums\ServerUserSettingKey;
use App\Enums\SubuserPermission;
use App\Events\Server\BackupCompleted;
use App\Listeners\Server\BackupCompletedListener;
use App\Models\Backup;
use App\Models\BackupHost;
use App\Models\Server;
use App\Models\ServerUserSettings;
use App\Models\User;
use App\Notifications\BackupCompleted as BackupCompletedNotification;
use Filament\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Notification;

function runBackupCompletedListener(Server $server): void
{
    /** @var Backup $backup */
    $backup = Backup::factory()->create([
        'server_id' => $server->id,
        'backup_host_id' => BackupHost::factory()->create()->id,
    ]);

    (new BackupCompletedListener())->handle(new BackupCompleted($backup));
}

it('notifies the server owner by default', function () {
    Notification::fake();

    [$owner, $server] = generateTestAccount();

    runBackupCompletedListener($server);

    Notification::assertSentTo($owner, DatabaseNotification::class);
    Notification::assertSentTo($owner, BackupCompletedNotification::class);
});

it('does not notify subusers by default', function () {
    Notification::fake();

    [$subuser, $server] = generateTestAccount([SubuserPermission::WebsocketConnect]);

    runBackupCompletedListener($server);

    Notification::assertNotSentTo($subuser, DatabaseNotification::class);
    Notification::assertNotSentTo($subuser, BackupCompletedNotification::class);
    Notification::assertSentTo($server->user, DatabaseNotification::class);
});

it('notifies subusers who opted in', function () {
    Notification::fake();

    [$subuser, $server] = generateTestAccount([SubuserPermission::WebsocketConnect]);

    $subuser->updateServerSetting($server, ServerUserSettingKey::BackupNotifications, true);

    runBackupCompletedListener($server);

    Notification::assertSentTo($subuser, DatabaseNotification::class);
    Notification::assertSentTo($subuser, BackupCompletedNotification::class);
});

it('does not notify owners who opted out', function () {
    Notification::fake();

    [$owner, $server] = generateTestAccount();

    $owner->updateServerSetting($server, ServerUserSettingKey::BackupNotifications, false);

    runBackupCompletedListener($server);

    Notification::assertNotSentTo($owner, DatabaseNotification::class);
    Notification::assertNotSentTo($owner, BackupCompletedNotification::class);
});

it('sends no email when the global config is disabled', function () {
    Notification::fake();

    config()->set('panel.email.send_backup_completed_notification', false);

    [$owner, $server] = generateTestAccount();

    runBackupCompletedListener($server);

    Notification::assertSentTo($owner, DatabaseNotification::class);
    Notification::assertNotSentTo($owner, BackupCompletedNotification::class);
});

it('defaults backup notifications on for owners and off for subusers', function () {
    [$owner, $server] = generateTestAccount();

    /** @var User $subuser */
    $subuser = User::factory()->create();

    expect($owner->getServerSetting($server, ServerUserSettingKey::BackupNotifications))->toBeTrue()
        ->and($subuser->getServerSetting($server, ServerUserSettingKey::BackupNotifications))->toBeFalse();
});

it('updates a single settings row per user and server', function () {
    [$owner, $server] = generateTestAccount();

    $owner->updateServerSetting($server, ServerUserSettingKey::BackupNotifications, false);
    $owner->updateServerSetting($server, ServerUserSettingKey::BackupNotifications, true);

    expect(ServerUserSettings::query()->where('user_id', $owner->id)->where('server_id', $server->id)->count())->toBe(1)
        ->and($owner->getServerSetting($server, ServerUserSettingKey::BackupNotifications))->toBeTrue();
});

it('drops unknown keys when updating settings', function () {
    [$owner, $server] = generateTestAccount();

    ServerUserSettings::factory()->create([
        'user_id' => $owner->id,
        'server_id' => $server->id,
        'settings' => ['unknown_key' => 'value'],
    ]);

    $owner->updateServerSetting($server, ServerUserSettingKey::BackupNotifications, true);

    $settings = ServerUserSettings::query()->where('user_id', $owner->id)->where('server_id', $server->id)->value('settings');

    expect($settings)->toBe([ServerUserSettingKey::BackupNotifications->value => true]);
});
