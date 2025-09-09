import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Textarea } from "@/components/ui/textarea";
import { Badge } from "@/components/ui/badge";

interface ServerInformation {
    name: string;
    owner: string;
    condition: string;
    description: string;
    uuid: string;
    uuid_short: string;
    external_id: string;
    node: string;
}

interface ServerEditInformationTabProps {
    settings: ServerInformation;
    onSettingsChange: (newSettings: Partial<ServerInformation>) => void;
}

export const ServerEditInformationTab = ({ settings, onSettingsChange }: ServerEditInformationTabProps) => {
    return (
        <div className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="space-y-2">
                    <Label htmlFor="name">Server Name</Label>
                    <Input id="name" value={settings.name} onChange={(e) => onSettingsChange({ name: e.target.value })} />
                </div>
                <div className="space-y-2">
                    <Label htmlFor="owner">Server Owner</Label>
                    <Select value={settings.owner} onValueChange={(v) => onSettingsChange({ owner: v })}>
                        <SelectTrigger id="owner">
                            <SelectValue placeholder="Select an owner" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="johndoe">johndoe</SelectItem>
                            <SelectItem value="janedoe">janedoe</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
            </div>
            <div className="space-y-2">
                <Label>Server Status</Label>
                <Badge variant={settings.condition === 'running' ? 'success' : 'destructive'} className="capitalize">{settings.condition}</Badge>
            </div>
            <div className="space-y-2">
                <Label htmlFor="description">Description</Label>
                <Textarea id="description" value={settings.description} onChange={(e) => onSettingsChange({ description: e.target.value })} />
            </div>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="space-y-2">
                    <Label htmlFor="uuid">UUID</Label>
                    <Input id="uuid" value={settings.uuid} readOnly />
                </div>
                <div className="space-y-2">
                    <Label htmlFor="uuid_short">Short UUID</Label>
                    <Input id="uuid_short" value={settings.uuid_short} readOnly />
                </div>
            </div>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="space-y-2">
                    <Label htmlFor="external_id">External ID</Label>
                    <Input id="external_id" value={settings.external_id} onChange={(e) => onSettingsChange({ external_id: e.target.value })} />
                </div>
                <div className="space-y-2">
                    <Label htmlFor="node">Node</Label>
                    <Input id="node" value={settings.node} readOnly />
                </div>
            </div>
        </div>
    );
};
