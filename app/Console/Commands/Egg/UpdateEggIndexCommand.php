<?php

namespace App\Console\Commands\Egg;

use Exception;
use Illuminate\Console\Command;

class UpdateEggIndexCommand extends Command
{
    protected $signature = 'p:egg:update-index';

    public function handle(): int
    {
        try {
            $data = file_get_contents('https://raw.githubusercontent.com/pelican-eggs/pelican-eggs.github.io/refs/heads/main/content/pelican.json');
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $exception) {
            $this->error($exception->getMessage());

            return 1;
        }

        $index = [];
        foreach ($data['nests'] as $nest) {
            $nestName = $nest['nest_type'];

            $this->info("Nest: $nestName");

            $nestEggs = [];
            foreach ($nest['Eggs'] as $egg) {
                $eggName = $egg['egg']['name'];

                $this->comment("Egg: $eggName");

                $nestEggs[$egg['download_url']] = $eggName;
            }
            $index[$nestName] = $nestEggs;

            $this->info('');
        }

        cache()->forever('eggs.index', $index);

        return 0;
    }
}
