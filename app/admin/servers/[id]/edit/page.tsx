'use client';

import { useState } from 'react';
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { IconInfoCircle, IconBrandDocker, IconEgg, IconPlayerPlay, IconDatabase, IconGitFork, IconSettings } from '@tabler/icons-react';

import { ServerEditInformationTab } from "@/components/admin/forms/ServerEditInformationTab";
import { ServerEditEnvironmentTab } from "@/components/admin/forms/ServerEditEnvironmentTab";
import { ServerEditEggTab } from "@/components/admin/forms/ServerEditEggTab";
import { ServerEditStartupTab } from "@/components/admin/forms/ServerEditStartupTab";
import { ServerEditDatabaseTab } from "@/components/admin/forms/ServerEditDatabaseTab";
import { ServerEditMountsTab } from "@/components/admin/forms/ServerEditMountsTab";
import { ServerEditManageTab } from "@/components/admin/forms/ServerEditManageTab";

// This is a mock server. In a real application, you would fetch this data based on the id.
const mockServer = {
    id: '1',
    name: 'Minecraft Survival',
    owner: 'johndoe',
    condition: 'running',
    description: 'A vanilla Minecraft server.',
    uuid: 'f7e4a6d8-9c1b-4f8e-8e4a-1b2c3d4e5f6g',
    uuid_short: 'f7e4a6d8',
    external_id: 'mc-survival-123',
    node: 'Node 1',
    unlimitedCpu: false,
    cpu: 100,
    unlimitedMemory: false,
    memory: 4096,
    unlimitedDisk: false,
    disk: 10240,
    allocationLimit: 1,
    databaseLimit: 0,
    backupLimit: 0,
    dockerImage: 'ghcr.io/pelican-eggs/yolks:debian',
};

export default function EditServerPage() {
    const [settings, setSettings] = useState(mockServer);

    const handleSettingsChange = (newSettings: Partial<typeof mockServer>) => {
        setSettings(prev => ({ ...prev, ...newSettings }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        console.log(settings);
        // Here you would typically make an API call to update the server
    };

    return (
        <div>
            <h1 className="text-3xl font-bold mb-6">Edit Server: {settings.name}</h1>
            <form onSubmit={handleSubmit}>
                <Tabs defaultValue="information" className="w-full">
                    <TabsList className="grid w-full grid-cols-7">
                        <TabsTrigger value="information" className="flex items-center gap-2"><IconInfoCircle className="h-5 w-5"/> Information</TabsTrigger>
                        <TabsTrigger value="environment" className="flex items-center gap-2"><IconBrandDocker className="h-5 w-5"/> Environment</TabsTrigger>
                        <TabsTrigger value="egg" className="flex items-center gap-2"><IconEgg className="h-5 w-5"/> Egg</TabsTrigger>
                        <TabsTrigger value="startup" className="flex items-center gap-2"><IconPlayerPlay className="h-5 w-5"/> Startup</TabsTrigger>
                        <TabsTrigger value="database" className="flex items-center gap-2"><IconDatabase className="h-5 w-5"/> Database</TabsTrigger>
                        <TabsTrigger value="mounts" className="flex items-center gap-2"><IconGitFork className="h-5 w-5"/> Mounts</TabsTrigger>
                        <TabsTrigger value="manage" className="flex items-center gap-2"><IconSettings className="h-5 w-5"/> Manage</TabsTrigger>
                    </TabsList>
                    <Card className="mt-6">
                        <CardContent className="pt-6">
                            <TabsContent value="information"><ServerEditInformationTab settings={settings} onSettingsChange={handleSettingsChange} /></TabsContent>
                            <TabsContent value="environment"><ServerEditEnvironmentTab settings={settings} onSettingsChange={handleSettingsChange} /></TabsContent>
                            <TabsContent value="egg"><ServerEditEggTab /></TabsContent>
                            <TabsContent value="startup"><ServerEditStartupTab /></TabsContent>
                            <TabsContent value="database"><ServerEditDatabaseTab /></TabsContent>
                            <TabsContent value="mounts"><ServerEditMountsTab /></TabsContent>
                            <TabsContent value="manage"><ServerEditManageTab /></TabsContent>
                        </CardContent>
                    </Card>
                    <div className="flex justify-end gap-2 mt-6">
                        <Button type="button" variant="outline">Cancel</Button>
                        <Button type="submit">Save Changes</Button>
                    </div>
                </Tabs>
            </form>
        </div>
    );
}
