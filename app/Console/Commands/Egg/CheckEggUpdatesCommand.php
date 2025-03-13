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
                $this->check($egg, $exporterService);
            } catch (Exception $exception) {
                $this->error("{$egg->name}: Error ({$exception->getMessage()})");
            }
        }
    }

    private function check(Egg $egg, EggExporterService $exporterService): void
    {
        if (is_null($egg->update_url)) {
            $this->comment("$egg->name: Skipping (no update url set)");

            return;
        }

        $currentJson = json_decode($exporterService->handle($egg->id));
        unset($currentJson->exported_at);

        $updatedEgg = file_get_contents($egg->update_url);
        assert($updatedEgg !== false);
        $updatedJson = json_decode($updatedEgg);
        unset($updatedJson->exported_at);

        if (md5(json_encode($currentJson, JSON_THROW_ON_ERROR)) === md5(json_encode($updatedJson, JSON_THROW_ON_ERROR))) {
            $this->info("$egg->name: Up-to-date");
            cache()->put("eggs.$egg->uuid.update", false, now()->addHour());

            return;
        }

        $this->warn("$egg->name: Found update");
        cache()->put("eggs.$egg->uuid.update", true, now()->addHour());
    }
}
