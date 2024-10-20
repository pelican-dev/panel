<?php

namespace App\Filament\Pages\Installer\Steps;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Support\HtmlString;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class CompletedStep
{
    public static function make(): Step
    {
        return Step::make('complete')
            ->label('Setup complete')
            ->schema([
                Placeholder::make('')
                    ->content(new HtmlString('The setup is nearly complete!<br>As last step you need to create a new cronjob that runs every minute to process specific tasks, such as session cleanup and scheduled tasks, and also create a queue worker.')),
                TextInput::make('crontab')
                    ->label(new HtmlString('Run the following command to setup your crontab. Note that <code>www-data</code> is your webserver user. On some systems this username might be different!'))
                    ->disabled()
                    ->hintAction(CopyAction::make())
                    ->default('(crontab -l -u www-data 2>/dev/null; echo "* * * * * php ' . base_path() . '/artisan schedule:run >> /dev/null 2>&1") | crontab -u www-data -'),
                TextInput::make('queueService')
                    ->label(new HtmlString('To setup the queue worker service you simply have to run the following command.'))
                    ->disabled()
                    ->hintAction(CopyAction::make())
                    ->default('sudo php ' . base_path() . '/artisan p:environment:queue-service'),
                Placeholder::make('')
                    ->content('After you finished these two last tasks you can click on "Finish" and use your new panel! Have fun!'),
            ]);
    }
}
