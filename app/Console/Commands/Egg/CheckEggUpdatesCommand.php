<?php

namespace App\Console\Commands\Egg;

use App\Enums\EggFormat;
use App\Models\Egg;
use App\Services\Eggs\Sharing\EggExporterService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use JsonException;
use Symfony\Component\Yaml\Yaml;

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

    /**
     * @throws JsonException
     */
    private function check(Egg $egg, EggExporterService $exporterService): void
    {
        if (is_null($egg->update_url)) {
            $this->comment("$egg->name: Skipping (no update url set)");

            return;
        }

        $ext = strtolower(pathinfo(parse_url($egg->update_url, PHP_URL_PATH), PATHINFO_EXTENSION));
        $isYaml = in_array($ext, ['yaml', 'yml']);

        $local = $isYaml
            ? Yaml::parse($exporterService->handle($egg->id, EggFormat::YAML))
            : json_decode($exporterService->handle($egg->id, EggFormat::JSON), true);

        $remote = Http::timeout(5)->connectTimeout(1)->get($egg->update_url)->throw()->body();
        $remote = $isYaml ? Yaml::parse($remote) : json_decode($remote, true);

        unset($local['exported_at'], $remote['exported_at']);

        $localHash = md5(json_encode($local, JSON_THROW_ON_ERROR));
        $remoteHash = md5(json_encode($remote, JSON_THROW_ON_ERROR));

        $status = $localHash === $remoteHash ? 'Up-to-date' : 'Found update';
        $this->{($localHash === $remoteHash) ? 'info' : 'warn'}("$egg->name: $status");

        cache()->put("eggs.$egg->uuid.update", $localHash !== $remoteHash, now()->addHour());
    }
}
