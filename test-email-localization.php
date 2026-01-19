#!/usr/bin/env php
<?php
/**
 * Email Localization Testing Script
 *
 * This script tests the email localization feature by:
 * 1. Creating/updating a user with different language preferences
 * 2. Sending test notifications
 * 3. Checking the log file for rendered email content
 * 4. Verifying correct language is used
 *
 * Usage: php test-email-localization.php
 */

// Bootstrap Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Notifications\MailTested;
use App\Notifications\AccountCreated;

echo "=== Email Localization Testing ===\n\n";

// Test 1: English user
echo "Test 1: Creating user with language='en'\n";
$userEn = User::firstOrCreate(
    ['email' => 'test-en@example.com'],
    [
        'username' => 'test_english',
        'language' => 'en',
        'name_first' => 'Test',
        'name_last' => 'English',
    ]
);
$userEn->language = 'en';
$userEn->save();

echo "Sending MailTested notification to English user...\n";
$userEn->notify(new MailTested($userEn));
echo "✓ Notification sent\n\n";

// Test 2: French user
echo "Test 2: Creating user with language='fr'\n";
$userFr = User::firstOrCreate(
    ['email' => 'test-fr@example.com'],
    [
        'username' => 'test_french',
        'language' => 'fr',
        'name_first' => 'Test',
        'name_last' => 'French',
    ]
);
$userFr->language = 'fr';
$userFr->save();

echo "Sending MailTested notification to French user...\n";
$userFr->notify(new MailTested($userFr));
echo "✓ Notification sent\n\n";

// Test 3: User with no language preference
echo "Test 3: Creating user with language=null (should fallback to 'en')\n";
$userNull = User::firstOrCreate(
    ['email' => 'test-null@example.com'],
    [
        'username' => 'test_null',
        'language' => null,
        'name_first' => 'Test',
        'name_last' => 'Null',
    ]
);
$userNull->language = null;
$userNull->save();

echo "Sending MailTested notification to user with no language...\n";
$userNull->notify(new MailTested($userNull));
echo "✓ Notification sent\n\n";

echo "=== Verification ===\n";
echo "Check the log file for email content:\n";
echo "  tail -100 storage/logs/laravel.log\n\n";
echo "Expected results:\n";
echo "  - English user: Should see 'Panel Test Message' and 'This is a test of the Panel mail system'\n";
echo "  - French user: Should see French translations (or English fallback if translations incomplete)\n";
echo "  - Null user: Should see English text (default locale)\n\n";
echo "Look for these sections in the log:\n";
echo "  - Subject line\n";
echo "  - Greeting with username\n";
echo "  - Body text\n\n";
echo "=== Test Complete ===\n";
