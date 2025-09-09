import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Switch } from "@/components/ui/switch";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";

interface ServerEnvironment {
    unlimitedCpu: boolean;
    cpu: number;
    unlimitedMemory: boolean;
    memory: number;
    unlimitedDisk: boolean;
    disk: number;
    allocationLimit: number;
    databaseLimit: number;
    backupLimit: number;
    dockerImage: string;
}

interface ServerEditEnvironmentTabProps {
    settings: ServerEnvironment;
    onSettingsChange: (newSettings: Partial<ServerEnvironment>) => void;
}

export const ServerEditEnvironmentTab = ({ settings, onSettingsChange }: ServerEditEnvironmentTabProps) => {
    return (
        <div className="space-y-6">
            <Card>
                <CardHeader>
                    <CardTitle>Resource Limits</CardTitle>
                </CardHeader>
                <CardContent className="space-y-4">
                    <div className="flex items-center gap-4">
                        <Label className="w-24">CPU</Label>
                        <div className="flex-1 grid grid-cols-2 gap-4">
                            <Input type="number" placeholder="CPU Limit (%)" value={settings.cpu} onChange={(e) => onSettingsChange({ cpu: parseInt(e.target.value) })} disabled={settings.unlimitedCpu} />
                            <div className="flex items-center space-x-2">
                                <Switch id="unlimited-cpu" checked={settings.unlimitedCpu} onCheckedChange={(c) => onSettingsChange({ unlimitedCpu: c })} />
                                <Label htmlFor="unlimited-cpu">Unlimited</Label>
                            </div>
                        </div>
                    </div>
                    <div className="flex items-center gap-4">
                        <Label className="w-24">Memory</Label>
                        <div className="flex-1 grid grid-cols-2 gap-4">
                            <Input type="number" placeholder="Memory Limit (MB)" value={settings.memory} onChange={(e) => onSettingsChange({ memory: parseInt(e.target.value) })} disabled={settings.unlimitedMemory} />
                            <div className="flex items-center space-x-2">
                                <Switch id="unlimited-memory" checked={settings.unlimitedMemory} onCheckedChange={(c) => onSettingsChange({ unlimitedMemory: c })} />
                                <Label htmlFor="unlimited-memory">Unlimited</Label>
                            </div>
                        </div>
                    </div>
                    <div className="flex items-center gap-4">
                        <Label className="w-24">Disk</Label>
                        <div className="flex-1 grid grid-cols-2 gap-4">
                            <Input type="number" placeholder="Disk Limit (MB)" value={settings.disk} onChange={(e) => onSettingsChange({ disk: parseInt(e.target.value) })} disabled={settings.unlimitedDisk} />
                            <div className="flex items-center space-x-2">
                                <Switch id="unlimited-disk" checked={settings.unlimitedDisk} onCheckedChange={(c) => onSettingsChange({ unlimitedDisk: c })} />
                                <Label htmlFor="unlimited-disk">Unlimited</Label>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle>Feature Limits</CardTitle>
                </CardHeader>
                <CardContent className="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div className="space-y-2">
                        <Label htmlFor="allocation-limit">Allocation Limit</Label>
                        <Input id="allocation-limit" type="number" value={settings.allocationLimit} onChange={(e) => onSettingsChange({ allocationLimit: parseInt(e.target.value) })} />
                    </div>
                    <div className="space-y-2">
                        <Label htmlFor="database-limit">Database Limit</Label>
                        <Input id="database-limit" type="number" value={settings.databaseLimit} onChange={(e) => onSettingsChange({ databaseLimit: parseInt(e.target.value) })} />
                    </div>
                    <div className="space-y-2">
                        <Label htmlFor="backup-limit">Backup Limit</Label>
                        <Input id="backup-limit" type="number" value={settings.backupLimit} onChange={(e) => onSettingsChange({ backupLimit: parseInt(e.target.value) })} />
                    </div>
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle>Docker Settings</CardTitle>
                </CardHeader>
                <CardContent>
                    <div className="space-y-2">
                        <Label htmlFor="docker-image">Image</Label>
                        <Select value={settings.dockerImage} onValueChange={(v) => onSettingsChange({ dockerImage: v })}>
                            <SelectTrigger id="docker-image">
                                <SelectValue placeholder="Select an image" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="ghcr.io/pelican-eggs/yolks:debian">ghcr.io/pelican-eggs/yolks:debian</SelectItem>
                                <SelectItem value="custom">Custom Image</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </CardContent>
            </Card>
        </div>
    );
};
