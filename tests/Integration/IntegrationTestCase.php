<?php

namespace App\Tests\Integration;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use App\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use App\Events\ActivityLogged;
use App\Tests\Assertions\AssertsActivityLogged;
use App\Tests\Traits\Integration\CreatesTestModels;
use App\Transformers\Api\Application\BaseTransformer;

abstract class IntegrationTestCase extends TestCase
{
    use AssertsActivityLogged;
    use CreatesTestModels;

    protected array $connectionsToTransact = ['mysql'];

    protected $defaultHeaders = [
        'Accept' => 'application/json',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake(ActivityLogged::class);
    }

    /**
     * Return an ISO-8601 formatted timestamp to use in the API response.
     */
    protected function formatTimestamp(string $timestamp): string
    {
        return CarbonImmutable::createFromFormat(CarbonInterface::DEFAULT_TO_STRING_FORMAT, $timestamp)
            ->setTimezone(BaseTransformer::RESPONSE_TIMEZONE)
            ->toAtomString();
    }
}
