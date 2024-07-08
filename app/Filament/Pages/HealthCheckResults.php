<?php
 
namespace App\Filament\Pages;
 
use ShuvroRoy\FilamentSpatieLaravelHealth\Pages\HealthCheckResults as BaseHealthCheckResults;
 
class HealthCheckResults extends BaseHealthCheckResults
{
    protected static ?string $slug = 'health';

    public static function getNavigationGroup(): ?string
    {
        return 'Advanced';
    }
}