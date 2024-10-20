<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class AssetComposer
{
    public function compose(View $view): void
    {
        $view->with('siteConfiguration', [
            'name' => config('app.name', 'Panel'),
            'locale' => config('app.locale') ?? 'en',
            'recaptcha' => [
                'enabled' => config('recaptcha.enabled', false),
                'siteKey' => config('recaptcha.website_key') ?? '',
            ],
            'usesSyncDriver' => config('queue.default') === 'sync',
            'serverDescriptionsEditable' => config('panel.editable_server_descriptions'),
        ]);
    }
}
