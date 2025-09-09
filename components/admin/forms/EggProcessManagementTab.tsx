import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { Input } from "@/components/ui/input";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { useEffect, useState } from "react";

interface EggProcessManagement {
    stopCommand: string;
    startupConfig: string;
    configFiles: string;
    logConfig: string;
}

interface EggProcessManagementTabProps {
    settings: EggProcessManagement;
    onSettingsChange: (newSettings: Partial<EggProcessManagement>) => void;
}

export const EggProcessManagementTab = ({ settings, onSettingsChange }: EggProcessManagementTabProps) => {
    const [eggs, setEggs] = useState<Array<{ id: number | null; uuid: string | null; name: string }>>([]);
    const [loadingEggs, setLoadingEggs] = useState(false);

    useEffect(() => {
        const run = async () => {
            try {
                setLoadingEggs(true);
                const res = await fetch(`/api/application/eggs?per_page=100`, { cache: 'no-store' });
                if (!res.ok) throw new Error('failed to load eggs');
                const j = await res.json();
                const items = (j?.data ?? []).map((d: any) => d.attributes as { id: number | null; uuid: string | null; name: string });
                setEggs(items);
            } catch (e) {
                console.error(e);
            } finally {
                setLoadingEggs(false);
            }
        };
        run();
    }, []);

    const handleCopyFrom = async (value: string) => {
        try {
            const res = await fetch(`/api/application/eggs/${value}`);
            if (!res.ok) throw new Error('failed to load egg');
            const j = await res.json();
            const attr = j.attributes as any;
            onSettingsChange({
                stopCommand: attr?.config?.stop || settings.stopCommand,
                startupConfig: JSON.stringify(attr?.config?.startup ?? {}, null, 2),
                configFiles: JSON.stringify(attr?.config?.files ?? {}, null, 2),
                logConfig: JSON.stringify(attr?.config?.logs ?? [], null, 2),
            });
        } catch (e) {
            console.error(e);
            // silent fail for now; could add toast
        }
    };

    return (
        <div className="space-y-4">
            {/* Top row: fixed order two-up */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Card>
                    <CardHeader className="py-3">
                        <CardTitle>Copy Settings From</CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-2">
                        <Label htmlFor="copy-from" className="sr-only">Copy Settings From</Label>
                        <Select onValueChange={handleCopyFrom} disabled={loadingEggs || eggs.length === 0}>
                            <SelectTrigger id="copy-from" className="bg-muted">
                                <SelectValue placeholder={loadingEggs ? 'Loading eggs...' : 'None'} />
                            </SelectTrigger>
                            <SelectContent>
                                {eggs.map((e) => (
                                    <SelectItem key={(e.uuid ?? e.id ?? e.name) as any} value={(e.uuid ?? String(e.id ?? '')) || ''}>
                                        {e.name}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        <p className="text-xs text-muted-foreground">Select another Egg to copy its process management configuration into this one.</p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader className="py-3">
                        <CardTitle>Stop Command</CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-2">
                        <Label htmlFor="stop-command" className="sr-only">Stop Command</Label>
                        <Input id="stop-command" placeholder="stop" value={settings.stopCommand} onChange={(e) => onSettingsChange({ stopCommand: e.target.value })} className="bg-muted" />
                        <p className="text-xs text-muted-foreground">The command sent to gracefully stop server processes.</p>
                    </CardContent>
                </Card>
            </div>

            {/* Remaining cards in masonry columns */}
            <div className="columns-1 md:columns-2 gap-4 [column-fill:_balance]">
                <Card className="mb-4 break-inside-avoid">
                    <CardHeader className="py-3">
                        <CardTitle>Startup Configuration</CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-2">
                        <Label htmlFor="startup-config" className="sr-only">Startup Configuration</Label>
                        <Textarea id="startup-config" rows={12} className="font-mono bg-muted" placeholder='{
    "done": "Done (",
    "userInteraction": []
}' value={settings.startupConfig} onChange={(e) => onSettingsChange({ startupConfig: e.target.value })} />
                        <p className="text-xs text-muted-foreground">Values the daemon looks for when booting a server to determine completion.</p>
                    </CardContent>
                </Card>

                <Card className="mb-4 break-inside-avoid">
                    <CardHeader className="py-3">
                        <CardTitle>Configuration Files</CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-2">
                        <Label htmlFor="config-files" className="sr-only">Configuration Files</Label>
                        <Textarea id="config-files" rows={12} className="font-mono bg-muted" placeholder='{
    "server.properties": {
        "parser": "properties",
        "find": {
            "server-port": "{{server.port}}"
        }
    }
}' value={settings.configFiles} onChange={(e) => onSettingsChange({ configFiles: e.target.value })} />
                        <p className="text-xs text-muted-foreground">JSON representation of files to modify and the parts that should change.</p>
                    </CardContent>
                </Card>

                <Card className="mb-4 break-inside-avoid">
                    <CardHeader className="py-3">
                        <CardTitle>Log Configuration</CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-2">
                        <Label htmlFor="log-config" className="sr-only">Log Configuration</Label>
                        <Textarea id="log-config" rows={12} className="font-mono bg-muted" placeholder='{
    "custom": true,
    "custom_log_path": "logs/latest.log",
    "strip_ansi": true
}' value={settings.logConfig} onChange={(e) => onSettingsChange({ logConfig: e.target.value })} />
                        <p className="text-xs text-muted-foreground">Where log files are stored and whether to create custom logs.</p>
                    </CardContent>
                </Card>
            </div>
        </div>
    );
};
