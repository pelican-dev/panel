<?php

namespace App\Exceptions;

use Spatie\ErrorSolutions\Contracts\Solution;
use Spatie\ErrorSolutions\Contracts\ProvidesSolution;

class ManifestDoesNotExistException extends \Exception implements ProvidesSolution
{
    public function getSolution(): Solution
    {
        return new Solutions\ManifestDoesNotExistSolution();
    }
}
