<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $mappings = [
            // Forge Minecraft
            'ed072427-f209-4603-875c-f540c6dd5a65' => [
                'new_uuid' => 'd6018085-eecc-42bf-bf8c-51ea45a69ace',
                'new_update_url' => 'https://raw.githubusercontent.com/pelican-eggs/minecraft/refs/heads/main/java/forge/egg-forge-minecraft.yaml',
            ],

            // Paper
            '5da37ef6-58da-4169-90a6-e683e1721247' => [
                'new_uuid' => '150956be-4164-4086-9057-631ae95505e9',
                'new_update_url' => 'https://raw.githubusercontent.com/pelican-eggs/minecraft/refs/heads/main/java/paper/egg-paper.yaml',
            ],

            // Garrys Mod
            '60ef81d4-30a2-4d98-ab64-f59c69e2f915' => [
                'new_uuid' => 'c0b2f96a-f753-4d82-a73e-6e5be2bbadd5',
                'new_update_url' => 'https://raw.githubusercontent.com/pelican-eggs/games-steamcmd/refs/heads/main/gmod/egg-garrys-mod.yaml',
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
