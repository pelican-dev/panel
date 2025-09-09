import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import {
    IconServer,
    IconDotsVertical,
    IconPlayerPlay,
    IconRefresh,
    IconPlayerStop,
    IconAlertTriangle,
    IconCpu,
    IconDeviceDesktopAnalytics,
    IconDeviceSdCard,
    IconNetwork
} from '@tabler/icons-react';

export const ServerCard = () => {
  const server = {
    name: "My Awesome Server",
    status: "running",
    uptime: "24 days",
    cpuUsage: "15.5%",
    cpuLimit: "4 Cores",
    memoryUsage: "4.2 GB",
    memoryLimit: "8 GB",
    diskUsage: "50 GB",
    diskLimit: "100 GB",
    networkAddress: "192.168.1.100",
  };

  const getStatusColor = (status: string) => {
    switch (status) {
      case "running":
        return "bg-green-500";
      case "stopped":
        return "bg-red-500";
      default:
        return "bg-gray-500";
    }
  };

  return (
    <Card className="relative cursor-pointer w-full overflow-hidden rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
      <div className={`absolute left-0 top-0 bottom-0 w-1 ${getStatusColor(server.status)}`}></div>
      <div className="flex-1 bg-card text-card-foreground p-4 pl-5">
        <div className="flex items-center justify-between mb-4">
          <div className="flex items-center gap-3">
            <IconServer className={`h-8 w-8 text-green-500`} />
            <div>
              <h2 className="text-xl font-bold">{server.name}</h2>
              <p className="text-sm text-muted-foreground">Uptime: {server.uptime}</p>
            </div>
          </div>
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="ghost" size="icon">
                <IconDotsVertical className="h-5 w-5" />
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
              <DropdownMenuItem className="flex items-center gap-2"><IconPlayerPlay className="h-4 w-4 text-green-500"/> Start</DropdownMenuItem>
              <DropdownMenuItem className="flex items-center gap-2"><IconRefresh className="h-4 w-4 text-yellow-500"/> Restart</DropdownMenuItem>
              <DropdownMenuItem className="flex items-center gap-2"><IconPlayerStop className="h-4 w-4 text-red-500"/> Stop</DropdownMenuItem>
              <DropdownMenuItem className="flex items-center gap-2"><IconAlertTriangle className="h-4 w-4 text-red-700"/> Kill</DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>

        <div className="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
          <div className="flex flex-col items-center">
            <p className="text-sm text-muted-foreground">CPU</p>
            <IconCpu className="h-6 w-6 my-1" />
            <p className="text-md font-semibold">{server.cpuUsage}</p>
            <hr className="w-full my-1 border" />
            <p className="text-xs text-muted-foreground">{server.cpuLimit}</p>
          </div>
          <div className="flex flex-col items-center">
            <p className="text-sm text-muted-foreground">Memory</p>
            <IconDeviceDesktopAnalytics className="h-6 w-6 my-1" />
            <p className="text-md font-semibold">{server.memoryUsage}</p>
            <hr className="w-full my-1 border" />
            <p className="text-xs text-muted-foreground">{server.memoryLimit}</p>
          </div>
          <div className="flex flex-col items-center">
            <p className="text-sm text-muted-foreground">Disk</p>
            <IconDeviceSdCard className="h-6 w-6 my-1" />
            <p className="text-md font-semibold">{server.diskUsage}</p>
            <hr className="w-full my-1 border" />
            <p className="text-xs text-muted-foreground">{server.diskLimit}</p>
          </div>
          <div className="flex flex-col items-center">
            <p className="text-sm text-muted-foreground">Network</p>
            <IconNetwork className="h-6 w-6 my-1" />
            <hr className="w-full my-1 border" />
            <p className="text-md font-semibold">{server.networkAddress}</p>
          </div>
        </div>
      </div>
    </Card>
  );
};
