<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $nameToUuidMapping = [
            'Bungeecord' => '9e6b409e-4028-4947-aea8-50a2c404c271',
            'Forge Minecraft' => 'ed072427-f209-4603-875c-f540c6dd5a65',
            'Paper' => '5da37ef6-58da-4169-90a6-e683e1721247',
            'Sponge (SpongeVanilla)' => 'f0d2f88f-1ff3-42a0-b03f-ac44c5571e6d',
            'Vanilla Minecraft' => '9ac39f3d-0c34-4d93-8174-c52ab9e6c57b',
            'Counter-Strike: Global Offensive' => '437c367d-06be-498f-a604-fdad135504d7',
            'Custom Source Engine Game' => '2a42d0c2-c0ba-4067-9a0a-9b95d77a3490',
            'Garrys Mod' => '60ef81d4-30a2-4d98-ab64-f59c69e2f915',
            'Insurgency' => 'a5702286-655b-4069-bf1e-925c7300b61a',
            'Team Fortress 2' => '7f8eb681-b2c8-4bf8-b9f4-d79ff70b6e5d',
            'Mumble Server' => '727ee758-7fb2-4979-972b-d3eba4e1e9f0',
            'Teamspeak3 Server' => '983b1fac-d322-4d5f-a636-436127326b37',
            'Rust' => 'bace2dfb-209c-452a-9459-7d6f340b07ae',
        ];

        foreach ($nameToUuidMapping as $name => $uuid) {
            DB::table('eggs')
                ->where('name', $name)
                ->update(['uuid' => $uuid]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
