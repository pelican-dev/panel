<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\VarDumper\VarDumper;
use App\Services\Telemetry\TelemetryCollectionService;

class TelemetryCommand extends Command
{
    protected $description = 'Displays all the data that would be sent to the Telemetry Service if telemetry collection is enabled.';

    protected $signature = 'p:telemetry';

    /**
     * TelemetryCommand constructor.
     */
    public function __construct(private TelemetryCollectionService $telemetryCollectionService)
    {
        parent::__construct();
    }

    /**
     * Handle execution of command.
     *
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function handle()
    {
        $this->output->info('Collecting telemetry data, this may take a while...');

        VarDumper::dump($this->telemetryCollectionService->collect());
    }
}
