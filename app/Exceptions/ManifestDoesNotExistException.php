<?php

namespace App\Exceptions;

use App\Exceptions\Solutions\ManifestDoesNotExistSolution;
use Exception;
use Spatie\Ignition\Contracts\ProvidesSolution;
use Spatie\Ignition\Contracts\Solution;

class ManifestDoesNotExistException extends Exception implements ProvidesSolution
{
    public function getSolution(): Solution
    {
        return new ManifestDoesNotExistSolution();
    }
}
