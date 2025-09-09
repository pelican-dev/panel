import { Card, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import {
    IconCircleCheck,
    IconAlertCircle,
    IconCircleChevronRight,
    IconCircleX,
    IconHelpCircle
} from "@tabler/icons-react";

type HealthStatus = 'ok' | 'warning' | 'skipped' | 'failed' | 'crashed' | 'unknown';

interface HealthCheckCardProps {
    name: string;
    status: HealthStatus;
    message: string;
}

const statusConfig: { [key in HealthStatus]: { icon: React.ComponentType<{ className?: string }>, variant: 'success' | 'warning' | 'destructive' | 'secondary', label: string } } = {
    ok: { icon: IconCircleCheck, variant: 'success', label: 'OK' },
    warning: { icon: IconAlertCircle, variant: 'warning', label: 'Warning' },
    skipped: { icon: IconCircleChevronRight, variant: 'secondary', label: 'Skipped' },
    failed: { icon: IconCircleX, variant: 'destructive', label: 'Failed' },
    crashed: { icon: IconCircleX, variant: 'destructive', label: 'Crashed' },
    unknown: { icon: IconHelpCircle, variant: 'secondary', label: 'Unknown' },
};

export const HealthCheckCard = ({ name, status, message }: HealthCheckCardProps) => {
    const config = statusConfig[status];
    const Icon = config.icon;

    const getIconColor = () => {
        switch (status) {
            case 'ok': return 'text-green-500';
            case 'warning': return 'text-yellow-500';
            case 'failed':
            case 'crashed': return 'text-red-500';
            default: return 'text-gray-500';
        }
    };

    return (
        <Card>
            <CardHeader className="flex flex-row items-center justify-between">
                <div className="flex items-center gap-3">
                    <Icon className={`h-8 w-8 ${getIconColor()}`} />
                    <div>
                        <CardTitle>{name}</CardTitle>
                        <CardDescription>{message}</CardDescription>
                    </div>
                </div>
                <Badge variant={config.variant} className="capitalize">{config.label}</Badge>
            </CardHeader>
        </Card>
    );
};
