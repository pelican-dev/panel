<?php

namespace App\Exceptions;

use Exception;
use App\Exceptions\Solutions\ManifestDoesNotExistSolution;
use Spatie\Ignition\Contracts\Solution;
use Spatie\Ignition\Contracts\ProvidesSolution;

class ManifestDoesNotExistException extends Exception implements ProvidesSolution
{
    public function getSolution(): Solution
    {
        return new ManifestDoesNotExistSolution();
    }
}
