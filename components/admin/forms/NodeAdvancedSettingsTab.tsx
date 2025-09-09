import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Switch } from "@/components/ui/switch";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";

interface NodeAdvancedSettings {
    uploadLimit: number;
    sftpPort: number;
    public: boolean;
    maintenanceMode: boolean;
    unlimitedMemory: boolean;
    memory: number;
    memoryOverallocate: number;
    unlimitedDisk: boolean;
    disk: number;
    diskOverallocate: number;
    unlimitedCpu: boolean;
    cpu: number;
    cpuOverallocate: number;
}

interface NodeAdvancedSettingsTabProps {
    settings: NodeAdvancedSettings;
    onSettingsChange: (newSettings: Partial<NodeAdvancedSettings>) => void;
}

export const NodeAdvancedSettingsTab = ({ settings, onSettingsChange }: NodeAdvancedSettingsTabProps) => {
    return (
        <div className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="space-y-2">
                    <Label htmlFor="upload-limit">Upload Limit (MB)</Label>
                    <Input id="upload-limit" type="number" value={settings.uploadLimit} onChange={(e) => onSettingsChange({ uploadLimit: parseInt(e.target.value) })} />
                </div>
                <div className="space-y-2">
                    <Label htmlFor="sftp-port">SFTP Port</Label>
                    <Input id="sftp-port" type="number" value={settings.sftpPort} onChange={(e) => onSettingsChange({ sftpPort: parseInt(e.target.value) })} />
                </div>
            </div>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="flex items-center space-x-2">
                    <Switch id="public" checked={settings.public} onCheckedChange={(c) => onSettingsChange({ public: c })} />
                    <Label htmlFor="public">Public</Label>
                </div>
                <div className="flex items-center space-x-2">
                    <Switch id="maintenance-mode" checked={settings.maintenanceMode} onCheckedChange={(c) => onSettingsChange({ maintenanceMode: c })} />
                    <Label htmlFor="maintenance-mode">Maintenance Mode</Label>
                </div>
            </div>
            <div className="space-y-4">
                <div className="flex items-center gap-4">
                    <Label className="w-24">Memory</Label>
                    <RadioGroup value={settings.unlimitedMemory ? 'unlimited' : 'limited'} onValueChange={(v) => onSettingsChange({ unlimitedMemory: v === 'unlimited' })} className="flex space-x-4">
                        <div className="flex items-center space-x-2">
                            <RadioGroupItem value="limited" id="mem-limited" />
                            <Label htmlFor="mem-limited">Limited</Label>
                        </div>
                        <div className="flex items-center space-x-2">
                            <RadioGroupItem value="unlimited" id="mem-unlimited" />
                            <Label htmlFor="mem-unlimited">Unlimited</Label>
                        </div>
                    </RadioGroup>
                    {!settings.unlimitedMemory && (
                        <div className="flex-1 grid grid-cols-2 gap-4">
                            <Input type="number" placeholder="Memory Limit (MB)" value={settings.memory} onChange={(e) => onSettingsChange({ memory: parseInt(e.target.value) })} />
                            <Input type="number" placeholder="Overallocate (%)" value={settings.memoryOverallocate} onChange={(e) => onSettingsChange({ memoryOverallocate: parseInt(e.target.value) })} />
                        </div>
                    )}
                </div>
                <div className="flex items-center gap-4">
                    <Label className="w-24">Disk</Label>
                    <RadioGroup value={settings.unlimitedDisk ? 'unlimited' : 'limited'} onValueChange={(v) => onSettingsChange({ unlimitedDisk: v === 'unlimited' })} className="flex space-x-4">
                        <div className="flex items-center space-x-2">
                            <RadioGroupItem value="limited" id="disk-limited" />
                            <Label htmlFor="disk-limited">Limited</Label>
                        </div>
                        <div className="flex items-center space-x-2">
                            <RadioGroupItem value="unlimited" id="disk-unlimited" />
                            <Label htmlFor="disk-unlimited">Unlimited</Label>
                        </div>
                    </RadioGroup>
                    {!settings.unlimitedDisk && (
                        <div className="flex-1 grid grid-cols-2 gap-4">
                            <Input type="number" placeholder="Disk Limit (MB)" value={settings.disk} onChange={(e) => onSettingsChange({ disk: parseInt(e.target.value) })} />
                            <Input type="number" placeholder="Overallocate (%)" value={settings.diskOverallocate} onChange={(e) => onSettingsChange({ diskOverallocate: parseInt(e.target.value) })} />
                        </div>
                    )}
                </div>
                <div className="flex items-center gap-4">
                    <Label className="w-24">CPU</Label>
                    <RadioGroup value={settings.unlimitedCpu ? 'unlimited' : 'limited'} onValueChange={(v) => onSettingsChange({ unlimitedCpu: v === 'unlimited' })} className="flex space-x-4">
                        <div className="flex items-center space-x-2">
                            <RadioGroupItem value="limited" id="cpu-limited" />
                            <Label htmlFor="cpu-limited">Limited</Label>
                        </div>
                        <div className="flex items-center space-x-2">
                            <RadioGroupItem value="unlimited" id="cpu-unlimited" />
                            <Label htmlFor="cpu-unlimited">Unlimited</Label>
                        </div>
                    </RadioGroup>
                    {!settings.unlimitedCpu && (
                        <div className="flex-1 grid grid-cols-2 gap-4">
                            <Input type="number" placeholder="CPU Limit (%)" value={settings.cpu} onChange={(e) => onSettingsChange({ cpu: parseInt(e.target.value) })} />
                            <Input type="number" placeholder="Overallocate (%)" value={settings.cpuOverallocate} onChange={(e) => onSettingsChange({ cpuOverallocate: parseInt(e.target.value) })} />
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
