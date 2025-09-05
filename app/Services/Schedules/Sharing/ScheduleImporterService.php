<?php

namespace App\Services\Schedules\Sharing;

use App\Exceptions\Service\InvalidFileUploadException;
use App\Helpers\Utilities;
use Illuminate\Support\Arr;
use Illuminate\Http\UploadedFile;
use App\Models\Schedule;
use App\Models\Server;
use App\Models\Task;
use Illuminate\Database\ConnectionInterface;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class ScheduleImporterService
{
    public function __construct(protected ConnectionInterface $connection) {}

    public function fromFile(UploadedFile $file, Server $server): Schedule
    {
        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new InvalidFileUploadException('The selected file was not uploaded successfully');
        }

        try {
            $parsed = json_decode($file->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new InvalidFileUploadException('Could not read JSON file: ' . $exception->getMessage());
        }

        return $this->connection->transaction(function () use ($server, $parsed) {
            $minute = Arr::get($parsed, 'cron_minute', '0');
            $hour = Arr::get($parsed, 'cron_hour', '0');
            $dayOfMonth = Arr::get($parsed, 'cron_day_of_month', '*');
            $month = Arr::get($parsed, 'cron_month', '*');
            $dayOfWeek = Arr::get($parsed, 'cron_day_of_week', '*');

            $schedule = Schedule::create([
                'server_id' => $server->id,
                'name' => Arr::get($parsed, 'name'),
                'is_active' => Arr::get($parsed, 'is_active'),
                'only_when_online' => Arr::get($parsed, 'only_when_online'),
                'cron_minute' => $minute,
                'cron_hour' => $hour,
                'cron_day_of_month' => $dayOfMonth,
                'cron_month' => $month,
                'cron_day_of_week' => $dayOfWeek,
                'next_run_at' => Utilities::getScheduleNextRunDate($minute, $hour, $dayOfMonth, $month, $dayOfWeek),
            ]);

            foreach (Arr::get($parsed, 'tasks', []) as $task) {
                Task::create([
                    'schedule_id' => $schedule->id,
                    'sequence_id' => Arr::get($task, 'sequence_id'),
                    'action' => Arr::get($task, 'action'),
                    'payload' => Arr::get($task, 'payload'),
                    'time_offset' => Arr::get($task, 'time_offset'),
                    'continue_on_failure' => Arr::get($task, 'continue_on_failure'),
                ]);
            }

            return $schedule;
        });
    }

    public function fromUrl(string $url, Server $server): Schedule
    {
        $info = pathinfo($url);
        $tmpDir = TemporaryDirectory::make()->deleteWhenDestroyed();
        $tmpPath = $tmpDir->path($info['basename']);

        if (!file_put_contents($tmpPath, file_get_contents($url))) {
            throw new InvalidFileUploadException('Could not write temporary file.');
        }

        return $this->fromFile(new UploadedFile($tmpPath, $info['basename'], 'application/json'), $server);
    }
}
