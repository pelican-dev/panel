<?php
 
namespace App\Filament\Pages;
 
use ShuvroRoy\FilamentSpatieLaravelHealth\Pages\HealthCheckResults as BaseHealthCheckResults;
 
class HealthCheckResults extends BaseHealthCheckResults
{
    public static function getNavigationGroup(): ?string
    {
        return 'Advanced';
    }
}