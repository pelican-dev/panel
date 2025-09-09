import { HealthCheckCard } from "@/components/admin/HealthCheckCard";
import { Button } from "@/components/ui/button";
import { IconRefresh } from "@tabler/icons-react";

type HealthStatus = 'ok' | 'warning' | 'skipped' | 'failed' | 'crashed' | 'unknown';

interface HealthCheck {
    name: string;
    status: HealthStatus;
    message: string;
}

const healthChecks: HealthCheck[] = [
    {
        name: 'Database',
        status: 'ok',
        message: 'The database is reachable.',
    },
    {
        name: 'Redis',
        status: 'ok',
        message: 'The Redis server is reachable.',
    },
    {
        name: 'Wings',
        status: 'warning',
        message: 'The Wings daemon is running an outdated version.',
    },
    {
        name: 'Mail',
        status: 'failed',
        message: 'Could not connect to the mail server.',
    },
    {
        name: 'Scheduler',
        status: 'skipped',
        message: 'The scheduler is not enabled.',
    },
];

export default function AdminHealth() {
  return (
    <div>
      <div className="flex items-center justify-between mb-6">
        <h1 className="text-3xl font-bold">Health</h1>
        <Button variant="outline" className="flex items-center gap-2">
            <IconRefresh className="h-4 w-4" />
            Refresh
        </Button>
      </div>
      <div className="space-y-4">
        {healthChecks.map((check) => (
            <HealthCheckCard key={check.name} name={check.name} status={check.status} message={check.message} />
        ))}
      </div>
    </div>
  );
}
