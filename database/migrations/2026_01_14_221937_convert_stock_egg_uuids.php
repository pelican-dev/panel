<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $mappings = [
            // Bungeecord
            '9e6b409e-4028-4947-aea8-50a2c404c271' => [
                'new_uuid' => '',
                'new_update_url' => '',
            ],

            // Forge Minecraft
            'ed072427-f209-4603-875c-f540c6dd5a65' => [
                'new_uuid' => '',
                'new_update_url' => '',
            ],

            // Paper
            '5da37ef6-58da-4169-90a6-e683e1721247' => [
                'new_uuid' => '',
                'new_update_url' => '',
            ],

            // Sponge
            'f0d2f88f-1ff3-42a0-b03f-ac44c5571e6d' => [
                'new_uuid' => '',
                'new_update_url' => '',
            ],

            // Vanilla Minecraft
            '9ac39f3d-0c34-4d93-8174-c52ab9e6c57b' => [
                'new_uuid' => '',
                'new_update_url' => '',
            ],

            // Rust
            'bace2dfb-209c-452a-9459-7d6f340b07ae' => [
                'new_uuid' => '',
                'new_update_url' => '',
            ],

            // Custom Source Engine Game
            '2a42d0c2-c0ba-4067-9a0a-9b95d77a3490' => [
                'new_uuid' => '',
                'new_update_url' => '',
            ],

            // Garrys Mod
            '60ef81d4-30a2-4d98-ab64-f59c69e2f915' => [
                'new_uuid' => '',
                'new_update_url' => '',
            ],

            // Insurgency
            'a5702286-655b-4069-bf1e-925c7300b61a' => [
                'new_uuid' => '',
                'new_update_url' => '',
            ],

            // Team Fortress 2
            '7f8eb681-b2c8-4bf8-b9f4-d79ff70b6e5d' => [
                'new_uuid' => '',
                'new_update_url' => '',
            ],

            // Mumble Server
            '727ee758-7fb2-4979-972b-d3eba4e1e9f0' => [
                'new_uuid' => '',
                'new_update_url' => '',
            ],

            // Teamspeak3 Server
            '983b1fac-d322-4d5f-a636-436127326b37' => [
                'new_uuid' => '',
                'new_update_url' => '',
            ],
        ];

        foreach ($mappings as $oldUuid => $newData) {
            DB::table('eggs')->where('uuid', $oldUuid)->update([
                'uuid' => $newData['new_uuid'],
                'update_url' => $newData['new_update_url'],
            ]);
        }
    }

    public function down(): void
    {
        // Not needed
    }
};
