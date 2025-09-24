<?php

namespace App\Console\Commands\Schedule;

use App\Models\Schedule;
use App\Services\Schedules\ProcessScheduleService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

class ProcessRunnableCommand extends Command
{
    protected $signature = 'p:schedule:process';

    protected $description = 'Process schedules in the database and determine which are ready to run.';

    public function handle(ProcessScheduleService $processScheduleService): int
    {
        $schedules = Schedule::query()
            ->with('tasks')
            ->whereRelation('server', fn (Builder $builder) => $builder->whereNull('status'))
            ->where('is_active', true)
            ->where('is_processing', false)
            ->where('next_run_at', '<=', now('UTC')->toDateTimeString())
            ->get();

        if ($schedules->count() < 1) {
            $this->line(trans('commands.schedule.process.no_tasks'));

            return 0;
        }

        $bar = $this->output->createProgressBar(count($schedules));
        foreach ($schedules as $schedule) {
            $bar->clear();
            $this->processSchedule($processScheduleService, $schedule);
            $bar->advance();
            $bar->display();
        }

        $this->line('');

        return 0;
    }

    /**
     * Processes a given schedule and logs and errors encountered the console output. This should
     * never throw an exception out, otherwise you'll end up killing the entire run group causing
     * any other schedules to not process correctly.
     */
    protected function processSchedule(ProcessScheduleService $processScheduleService, Schedule $schedule): void
    {
        if ($schedule->tasks->isEmpty()) {
            return;
        }

        try {
            $processScheduleService->handle($schedule);

            $this->line(trans('command/messages.schedule.output_line', [
                'schedule' => $schedule->name,
                'id' => $schedule->id,
            ]));
        } catch (Throwable $exception) {
            logger()->error($exception, ['schedule_id' => $schedule->id]);

            $this->error(trans('commands.schedule.process.error_message', ['schedules' => " #$schedule->id: " . $exception->getMessage()]));
        }
    }
}
