import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";

interface NodeBasicSettings {
    fqdn: string;
    name: string;
    connectionType: 'http' | 'https' | 'https_proxy';
    port: number;
    listenPort: number;
}

interface NodeBasicSettingsTabProps {
    settings: NodeBasicSettings;
    onSettingsChange: (newSettings: Partial<NodeBasicSettings>) => void;
}

export const NodeBasicSettingsTab = ({ settings, onSettingsChange }: NodeBasicSettingsTabProps) => {
    return (
        <div className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="space-y-2">
                    <Label htmlFor="fqdn">FQDN</Label>
                    <Input id="fqdn" placeholder="node.example.com" value={settings.fqdn} onChange={(e) => onSettingsChange({ fqdn: e.target.value })} />
                </div>
                <div className="space-y-2">
                    <Label htmlFor="name">Display Name</Label>
                    <Input id="name" placeholder="My Awesome Node" value={settings.name} onChange={(e) => onSettingsChange({ name: e.target.value })} />
                </div>
            </div>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="space-y-2">
                    <Label>Connection Type</Label>
                    <RadioGroup value={settings.connectionType} onValueChange={(v) => onSettingsChange({ connectionType: v as 'http' | 'https' | 'https_proxy' })} className="flex space-x-4">
                        <div className="flex items-center space-x-2">
                            <RadioGroupItem value="http" id="http" />
                            <Label htmlFor="http">HTTP</Label>
                        </div>
                        <div className="flex items-center space-x-2">
                            <RadioGroupItem value="https" id="https" />
                            <Label htmlFor="https">HTTPS</Label>
                        </div>
                        <div className="flex items-center space-x-2">
                            <RadioGroupItem value="https_proxy" id="https_proxy" />
                            <Label htmlFor="https_proxy">HTTPS with Proxy</Label>
                        </div>
                    </RadioGroup>
                </div>
                <div className="space-y-2">
                    <Label htmlFor="port">Port</Label>
                    <Input id="port" type="number" value={settings.port} onChange={(e) => onSettingsChange({ port: parseInt(e.target.value) })} />
                </div>
            </div>
            {settings.connectionType === 'https_proxy' && (
                <div className="space-y-2">
                    <Label htmlFor="listen-port">Listen Port</Label>
                    <Input id="listen-port" type="number" value={settings.listenPort} onChange={(e) => onSettingsChange({ listenPort: parseInt(e.target.value) })} />
                </div>
            )}
        </div>
    );
};
