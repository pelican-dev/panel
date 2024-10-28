<?php

return [
    // The number of days elapsed before old webhook entries are deleted.
    'prune_days' => env('APP_WEBHOOK_PRUNE_DAYS', 30),
];
