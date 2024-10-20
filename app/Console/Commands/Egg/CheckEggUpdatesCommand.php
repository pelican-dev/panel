<?php

namespace App\Console\Commands\Egg;

use App\Models\Egg;
use App\Services\Eggs\Sharing\EggExporterService;
use Exception;
use Illuminate\Console\Command;

class CheckEggUpdatesCommand extends Command
{
    protected $signature = 'p:egg:check-updates';

    public function handle(EggExporterService $exporterService): void
    {
        $eggs = Egg::all();
        foreach ($eggs as $egg) {
            try {
                if (is_null($egg->update_url)) {
                    $this->comment("{$egg->name}: Skipping (no update url set)");

                    continue;
                }

                $currentJson = json_decode($exporterService->handle($egg->id));
                unset($currentJson->exported_at);

                $updatedJson = json_decode(file_get_contents($egg->update_url));
                unset($updatedJson->exported_at);

                if (md5(json_encode($currentJson)) === md5(json_encode($updatedJson))) {
                    $this->info("{$egg->name}: Up-to-date");
                    cache()->put("eggs.{$egg->uuid}.update", false, now()->addHour());
                } else {
                    $this->warn("{$egg->name}: Found update");
                    cache()->put("eggs.{$egg->uuid}.update", true, now()->addHour());
                }
            } catch (Exception $exception) {
                $this->error("{$egg->name}: Error ({$exception->getMessage()})");
            }
        }
    }
}
