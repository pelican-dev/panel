<?php

namespace App\Console\Commands\Dev;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateTablerIconsEnum extends Command
{
    protected $signature = 'dev:generate-tabler-icons-enum';

    protected $description = 'Generate an enum for tabler icons based on the secondnetwork/blade-tabler-icons svgs';

    public function handle(): void
    {
        $files = File::files(base_path('vendor/secondnetwork/blade-tabler-icons/resources/svg'));
        $files = array_filter($files, fn ($file) => $file->getExtension() === 'svg');

        $enumContent = "<?php\n\n";
        $enumContent .= "namespace App\\Enums;\n\n";
        $enumContent .= "enum TablerIcon: string\n{\n";

        foreach ($files as $file) {
            $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);

            // Letter V is duplicate, as "letter-v" and "letter-letter-v"
            if (str($filename)->contains('letter-letter')) {
                continue;
            }

            // Filled icons exist with "-f" and "-filled", we only want the later
            if (str($filename)->endsWith('-f') && file_exists(base_path("vendor/secondnetwork/blade-tabler-icons/resources/svg/{$filename}illed.svg"))) {
                continue;
            }

            $caseName = str($filename)->title()->replace('-', '');
            $value = str($filename)->slug()->prepend('tabler-');

            $enumContent .= "    case $caseName = '$value';\n";
        }

        $enumContent .= "}\n";

        File::put(base_path('app/Enums/TablerIcon.php'), $enumContent);

        $this->info('Enum generated');
    }
}
