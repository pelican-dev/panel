<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

$options = [
    '--service-name' => 'pelican-queue-test',
    '--user' => 'www-data',
    '--group' => 'www-data',
    '--overwrite' => true,
];

it('fails when the service can not be started', function () use ($options) {
    File::shouldReceive('put')->andReturn(true);
    Process::fake(['*' => Process::result(errorOutput: 'unit not found', exitCode: 1)]);

    $this->artisan('p:environment:queue-service', $options)->assertFailed();
});

it('fails when the service file can not be written', function () use ($options) {
    File::shouldReceive('put')->andReturn(false);
    Process::fake();

    $this->artisan('p:environment:queue-service', $options)->assertFailed();
})->skip(fn () => file_exists('/.dockerenv'), 'Docker restarts supervisor instead of writing a service file');

it('succeeds when the service starts', function () use ($options) {
    File::shouldReceive('put')->andReturn(true);
    Process::fake();

    $this->artisan('p:environment:queue-service', $options)->assertSuccessful();
});
