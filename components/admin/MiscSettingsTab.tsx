import { SettingsCard } from "@/components/admin/SettingsCard";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Switch } from "@/components/ui/switch";

export const MiscSettingsTab = () => {
    return (
        <div className="space-y-6">
            <SettingsCard title="Automatic Allocation Creation" description="Enable or disable automatic allocation creation for servers.">
                <div className="flex items-center space-x-2">
                    <Switch id="auto-allocation-enabled" />
                    <Label htmlFor="auto-allocation-enabled">Enable</Label>
                </div>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <Label htmlFor="allocation-start">Allocation Range Start</Label>
                        <Input id="allocation-start" type="number" placeholder="1024" />
                    </div>
                    <div>
                        <Label htmlFor="allocation-end">Allocation Range End</Label>
                        <Input id="allocation-end" type="number" placeholder="65535" />
                    </div>
                </div>
            </SettingsCard>
            <SettingsCard title="Mail Notifications" description="Configure which mail notifications are sent to users.">
                <div className="space-y-2">
                    <div className="flex items-center space-x-2">
                        <Switch id="server-installed-notif" />
                        <Label htmlFor="server-installed-notif">Server Installed</Label>
                    </div>
                    <div className="flex items-center space-x-2">
                        <Switch id="server-reinstalled-notif" />
                        <Label htmlFor="server-reinstalled-notif">Server Reinstalled</Label>
                    </div>
                </div>
            </SettingsCard>
            <SettingsCard title="Connection Timeouts" description="Configure the timeouts for connections to nodes.">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <Label htmlFor="request-timeout">Request Timeout (seconds)</Label>
                        <Input id="request-timeout" type="number" defaultValue={15} />
                    </div>
                    <div>
                        <Label htmlFor="connection-timeout">Connection Timeout (seconds)</Label>
                        <Input id="connection-timeout" type="number" defaultValue={5} />
                    </div>
                </div>
            </SettingsCard>
        </div>
    );
};
