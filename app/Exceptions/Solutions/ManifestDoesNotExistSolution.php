<?php

namespace App\Exceptions\Solutions;

use Spatie\Ignition\Contracts\Solution;

class ManifestDoesNotExistSolution implements Solution
{
    public function getSolutionTitle(): string
    {
        return "The manifest.json file hasn't been generated yet";
    }

    public function getSolutionDescription(): string
    {
        return 'Run yarn run build:production to build the frontend first.';
    }

    public function getDocumentationLinks(): array
    {
        return [
            'Docs' => 'https://github.com/pelican/panel/blob/master/package.json',
        ];
    }
}
