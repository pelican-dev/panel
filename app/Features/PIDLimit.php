<?php

namespace App\Features;

use Filament\Actions\Action;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class PIDLimit extends Feature
{
    /** @return array<string> */
    public function listeners(): array
    {
        return [
            'pthread_create failed',
            'failed to create thread',
            'unable to create thread',
            'unable to create native thread',
            'unable to create new native thread',
            'exception in thread "craft async scheduler management thread"',
        ];
    }

    public function featureName(): string
    {
        return 'pidlimit';
    }

    public function action(): Action
    {
        return Action::make($this->featureName())
            ->requiresConfirmation()
            ->icon('tabler-alert-triangle')
            ->modalHeading(fn () => auth()->user()->isAdmin() ? 'Memory or process limit reached...' : 'Possible resource limit reached...')
            ->modalDescription(new HtmlString(Blade::render(
                auth()->user()->isAdmin() ? <<<'HTML'
                    <p>
                        This server has reached the maximum process or memory limit.
                    </p>
                    <p class="mt-4">
                        Increasing <code>container_pid_limit</code> in the wings
                        configuration, <code>config.yml</code>, might help resolve
                        this issue.
                    </p>
                    <p class="mt-4">
                        <b>Note: Wings must be restarted for the configuration file changes to take effect</b>
                    </p>
                HTML
                :
                <<<'HTML'
                    <p>
                        This server is attempting to use more resources than allocated. Please contact the administrator
                        and give them the error below.
                    </p>
                    <p class="mt-4">
                        <code>
                            pthread_create failed, Possibly out of memory or process/resource limits reached
                        </code>
                    </p>
                HTML
            )))
            ->modalCancelActionLabel('Close')
            ->action(fn () => null);
    }
}
