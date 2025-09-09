'use client';

import { useState } from 'react';
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { IconEgg, IconServerCog, IconVariable, IconFileDownload } from '@tabler/icons-react';

import { EggConfigurationTab } from "@/components/admin/forms/EggConfigurationTab";
import { EggProcessManagementTab } from "@/components/admin/forms/EggProcessManagementTab";
import { EggVariablesTab, EggVariable } from "@/components/admin/forms/EggVariablesTab";
import { EggInstallScriptTab } from "@/components/admin/forms/EggInstallScriptTab";

const initialSettings = {
    name: '',
    author: '',
    description: '',
    startup: '',
    dockerImages: [{ key: 'Default', value: 'ghcr.io/pelican-eggs/yolks:debian' }],
    stopCommand: 'stop',
    startupConfig: '{\n    "done": "Done (",\n    "userInteraction": []\n}',
    configFiles: '{\n    "server.properties": {\n        "parser": "properties",\n        "find": {\n            "server-port": "{{server.port}}"\n        }\n    }\n}',
    logConfig: '{\n    "custom": true,\n    "custom_log_path": "logs/latest.log",\n    "strip_ansi": true\n}',
    variables: [] as EggVariable[],
    scriptContainer: 'ghcr.io/pelican-eggs/installers:debian',
    scriptEntry: 'bash',
    installScript: '#!/bin/bash\n# My install script...',
};

export default function CreateEggPage() {
    const [settings, setSettings] = useState(initialSettings);

    const handleSettingsChange = (newSettings: Partial<typeof initialSettings>) => {
        setSettings(prev => ({ ...prev, ...newSettings }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        console.log(settings);
        // Here you would typically make an API call to create the egg
    };

    return (
        <div>
            <h1 className="text-3xl font-bold mb-6">Create Egg</h1>
            <form onSubmit={handleSubmit}>
                <Tabs defaultValue="configuration" className="w-full">
                    <TabsList className="grid w-full grid-cols-4">
                        <TabsTrigger value="configuration" className="flex items-center gap-2"><IconEgg className="h-5 w-5"/> Configuration</TabsTrigger>
                        <TabsTrigger value="process_management" className="flex items-center gap-2"><IconServerCog className="h-5 w-5"/> Process Management</TabsTrigger>
                        <TabsTrigger value="egg_variables" className="flex items-center gap-2"><IconVariable className="h-5 w-5"/> Variables</TabsTrigger>
                        <TabsTrigger value="install_script" className="flex items-center gap-2"><IconFileDownload className="h-5 w-5"/> Install Script</TabsTrigger>
                    </TabsList>
                    <Card className="mt-6">
                        <CardContent className="pt-6">
                            <TabsContent value="configuration">
                                <EggConfigurationTab settings={settings} onSettingsChange={handleSettingsChange} />
                            </TabsContent>
                            <TabsContent value="process_management">
                                <EggProcessManagementTab settings={settings} onSettingsChange={handleSettingsChange} />
                            </TabsContent>
                            <TabsContent value="egg_variables">
                                <EggVariablesTab variables={settings.variables} onVariablesChange={(v) => handleSettingsChange({ variables: v })} />
                            </TabsContent>
                            <TabsContent value="install_script">
                                <EggInstallScriptTab settings={settings} onSettingsChange={handleSettingsChange} />
                            </TabsContent>
                        </CardContent>
                    </Card>
                    <div className="flex justify-end gap-2 mt-6">
                        <Button type="button" variant="outline">Cancel</Button>
                        <Button type="submit">Create Egg</Button>
                    </div>
                </Tabs>
            </form>
        </div>
    );
}
