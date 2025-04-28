<?php

namespace App\Http\Controllers\Api\Remote;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Server;
use App\Models\ActivityLog;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Remote\ActivityEventRequest;

class ActivityProcessingController extends Controller
{
    public function __invoke(ActivityEventRequest $request): void
    {
        /** @var \App\Models\Node $node */
        $node = $request->attributes->get('node');

        $servers = $node->servers()->whereIn('uuid', $request->servers())->get()->keyBy('uuid');
        $users = User::query()->whereIn('uuid', $request->users())->get()->keyBy('uuid');

        $logs = [];
        foreach ($request->input('data') as $datum) {
            /** @var \App\Models\Server|null $server */
            $server = $servers->get($datum['server']);
            if (is_null($server) || !Str::startsWith($datum['event'], 'server:')) {
                continue;
            }

            try {
                $when = Carbon::createFromFormat(
                    \DateTimeInterface::RFC3339,
                    preg_replace('/(\.\d+)Z$/', 'Z', $datum['timestamp']),
                    'UTC'
                );
            } catch (\Exception $exception) {
                logger()->warning($exception, ['timestamp' => $datum['timestamp']]);

                // If we cannot parse the value for some reason don't blow up this request, just go ahead
                // and log the event with the current time, and set the metadata value to have the original
                // timestamp that was provided.
                $when = Carbon::now();
                $datum['metadata'] = array_merge($datum['metadata'] ?? [], ['original_timestamp' => $datum['timestamp']]);
            }

            $log = [
                'ip' => empty($datum['ip']) ? '127.0.0.1' : $datum['ip'],
                'event' => $datum['event'],
                'properties' => $datum['metadata'] ?? [],
                'timestamp' => $when,
            ];

            if ($user = $users->get($datum['user'])) {
                $log['actor_id'] = $user->id;
                $log['actor_type'] = $user->getMorphClass();
            }

            if (!isset($logs[$datum['server']])) {
                $logs[$datum['server']] = [];
            }

            $logs[$datum['server']][] = $log;
        }

        foreach ($logs as $key => $data) {
            $server = $servers->get($key);
            assert($server instanceof Server);

            foreach ($data as $datum) {
                /** @var ActivityLog $activityLog */
                $activityLog = ActivityLog::forceCreate($datum);
                $activityLog->subjects()->create([
                    'subject_id' => $server->id,
                    'subject_type' => $server->getMorphClass(),
                ]);
            }
        }
    }
}
