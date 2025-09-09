import { Label } from "@/components/ui/label";
import { Input } from "@/components/ui/input";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import Editor from '@monaco-editor/react';

interface EggInstallScript {
    scriptContainer: string;
    scriptEntry: string;
    installScript: string;
}

interface EggInstallScriptTabProps {
    settings: EggInstallScript;
    onSettingsChange: (newSettings: Partial<EggInstallScript>) => void;
}

export const EggInstallScriptTab = ({ settings, onSettingsChange }: EggInstallScriptTabProps) => {
    return (
        <div className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="space-y-2">
                    <Label htmlFor="script-container">Script Container</Label>
                    <Input id="script-container" placeholder="ghcr.io/pelican-eggs/installers:debian" value={settings.scriptContainer} onChange={(e) => onSettingsChange({ scriptContainer: e.target.value })} />
                </div>
                <div className="space-y-2">
                    <Label htmlFor="script-entry">Script Entrypoint</Label>
                    <Select value={settings.scriptEntry} onValueChange={(v) => onSettingsChange({ scriptEntry: v })}>
                        <SelectTrigger id="script-entry">
                            <SelectValue placeholder="Select an entrypoint" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="bash">bash</SelectItem>
                            <SelectItem value="ash">ash</SelectItem>
                            <SelectItem value="/bin/bash">/bin/bash</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
            </div>
            <div className="space-y-2">
                <Label htmlFor="install-script">Install Script</Label>
                <Editor
                    height="400px"
                    defaultLanguage="shell"
                    theme="vs-dark"
                    value={settings.installScript}
                    onChange={(value: string | undefined) => onSettingsChange({ installScript: value || '' })}
                    options={{
                        minimap: { enabled: false },
                        fontSize: 14,
                        lineNumbers: 'on',
                        roundedSelection: false,
                        scrollBeyondLastLine: false,
                        automaticLayout: true,
                        folding: true,
                        matchBrackets: 'always',
                        autoClosingBrackets: 'always',
                        wordWrap: 'on',
                    }}
                />
            </div>
        </div>
    );
};
