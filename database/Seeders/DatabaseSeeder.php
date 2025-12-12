<?php

namespace Database\Seeders;

use App\Models\Plugin;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(EggSeeder::class);

        Role::firstOrCreate(['name' => Role::ROOT_ADMIN]);

        $plugins = Plugin::query()->orderBy('load_order')->get();
        foreach ($plugins as $plugin) {
            if (!$plugin->shouldLoad()) {
                continue;
            }

            $name = Str::studly($plugin->name);
            $seeder = "\Database\Seeders\\{$name}Seeder";

            if (class_exists($seeder)) {
                $this->call($seeder);
            }
        }
    }
}
