# Email Localization Testing Guide

This document provides comprehensive testing instructions for the email localization feature.

## Overview

The email localization feature allows emails to be sent in the recipient's preferred language. This is achieved through:
- User language preference stored in the `language` field
- Translation keys using Laravel's `__()` helper
- Locale detection in notification classes

## Prerequisites

- Laravel application running
- `MAIL_MAILER=log` in `.env` (emails logged to `storage/logs/laravel.log`)
- Database with users table

## Quick Test Using Tinker

### Method 1: Simple Test (Recommended)

```bash
php artisan tinker
```

```php
// Test 1: Create/update user with English
$user = \App\Models\User::first();
if (!$user) {
    echo "No users found. Create a user first.\n";
    exit;
}
$user->language = 'en';
$user->save();

// Send test notification
$user->notify(new \App\Notifications\MailTested($user));

echo "English email sent. Check storage/logs/laravel.log\n";

// Test 2: Update user to French
$user->language = 'fr';
$user->save();
$user->notify(new \App\Notifications\MailTested($user));

echo "French email sent. Check storage/logs/laravel.log\n";

// Test 3: Null language (should fallback to 'en')
$user->language = null;
$user->save();
$user->notify(new \App\Notifications\MailTested($user));

echo "Fallback email sent. Check storage/logs/laravel.log\n";
```

### Method 2: Create Test Users

```bash
php artisan tinker
```

```php
// Create English user
$enUser = \App\Models\User::create([
    'username' => 'test_en_' . time(),
    'email' => 'test-en-' . time() . '@example.com',
    'language' => 'en',
    'name_first' => 'Test',
    'name_last' => 'English',
    'password' => \Hash::make('password'),
]);
$enUser->notify(new \App\Notifications\MailTested($enUser));

// Create French user
$frUser = \App\Models\User::create([
    'username' => 'test_fr_' . time(),
    'email' => 'test-fr-' . time() . '@example.com',
    'language' => 'fr',
    'name_first' => 'Test',
    'name_last' => 'French',
    'password' => \Hash::make('password'),
]);
$frUser->notify(new \App\Notifications\MailTested($frUser));

echo "Test emails sent. Check storage/logs/laravel.log\n";
```

## Verify Email Content

Check the log file:

```bash
tail -100 storage/logs/laravel.log
```

Or watch in real-time:

```bash
tail -f storage/logs/laravel.log
```

## Expected Results

### English Email (language='en')
```
Subject: Panel Test Message
Greeting: Hello test_en!
Body: This is a test of the Panel mail system. You're good to go!
```

### French Email (language='fr')
```
Subject: Message de test du panel
Greeting: Bonjour test_fr !
Body: Ceci est un test du système de messagerie du panel. Tout fonctionne correctement !
```

### Null Language (language=null)
Should display English text (default fallback locale).

## Testing All Notifications

### AccountCreated Notification

```php
php artisan tinker
```

```php
$user = \App\Models\User::first();
$user->language = 'fr';
$user->save();
$user->notify(new \App\Notifications\AccountCreated('test-token-123'));
```

Expected French output:
- Greeting: "Bonjour {username} !"
- Body: "Vous recevez cet e-mail car un compte a été créé pour vous sur..."
- Action: "Configurer votre compte"

### ServerInstalled Notification

```php
php artisan tinker
```

```php
$user = \App\Models\User::first();
$server = \App\Models\Server::first();
$user->language = 'fr';
$user->save();
$user->notify(new \App\Notifications\ServerInstalled($server));
```

Expected French output:
- Greeting: "Bonjour {username}."
- Body: "Votre serveur a terminé l'installation et est maintenant prêt à être utilisé."
- Server Name: "Nom du serveur : {server_name}"
- Action: "Se connecter et commencer à utiliser"

## Automated Test Script

Run the provided test script:

```bash
php test-email-localization.php
```

This script:
1. Creates users with different language preferences
2. Sends test notifications to each user
3. Provides instructions for verifying the log output

## Troubleshooting

### No emails in log file
- Check `MAIL_MAILER=log` in `.env`
- Verify `storage/logs/laravel.log` exists and is writable
- Check Laravel permissions: `php artisan cache:clear`

### Translations not appearing
- Verify `lang/fr/notifications.php` exists
- Check translation keys match those used in notification classes
- Clear translation cache: `php artisan cache:clear`

### User language field doesn't exist
- Check database schema: `php artisan tinker` → `User::first()->language`
- Migration may be needed to add the `language` column

## Success Criteria

✓ Emails sent to users with `language='en'` show English text
✓ Emails sent to users with `language='fr'` show French text
✓ Emails sent to users with `language=null` show English text (fallback)
✓ All translation keys resolve correctly
✓ No errors in `storage/logs/laravel.log`
✓ Queued notifications preserve user locale

## Additional Languages

To test with other languages:

1. Check if translation file exists: `ls lang/es/notifications.php`
2. Copy the structure from `lang/fr/notifications.php`
3. Translate the strings to the target language
4. Test with a user having that language preference

## Code Review Checklist

✓ All notification classes have `locale()` method
✓ All notification classes use `__()` for translatable strings
✓ Translation files exist for all supported languages
✓ No hardcoded English strings in notification classes
✓ User model has `language` field accessible
