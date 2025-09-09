'use client';

import { useState } from 'react';
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { IconChartAreaLine, IconServer, IconServerCog, IconCode } from '@tabler/icons-react';

import { NodeBasicSettingsTab } from "@/components/admin/forms/NodeBasicSettingsTab";
import { NodeAdvancedSettingsTab } from "@/components/admin/forms/NodeAdvancedSettingsTab";
import { NodeConfigFileTab } from "@/components/admin/forms/NodeConfigFileTab";

// Placeholder component for tab content.
const NodeOverviewTab = () => <p>Overview content will go here.</p>;

// This is a mock node. In a real application, you would fetch this data based on the id.
const mockNode = {
    fqdn: 'node.example.com',
    name: 'Node 1',
    connectionType: 'https' as 'http' | 'https' | 'https_proxy',
    port: 8080,
    listenPort: 8080,
    uploadLimit: 100,
    sftpPort: 2022,
    public: true,
    maintenanceMode: false,
    unlimitedMemory: false,
    memory: 4096,
    memoryOverallocate: 0,
    unlimitedDisk: false,
    disk: 10240,
    diskOverallocate: 0,
    unlimitedCpu: false,
    cpu: 100,
    cpuOverallocate: 0,
};

export default function EditNodePage() {
    const [settings, setSettings] = useState(mockNode);

    const handleSettingsChange = (newSettings: Partial<typeof mockNode>) => {
        setSettings(prev => ({ ...prev, ...newSettings }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        console.log(settings);
        // Here you would typically make an API call to update the node
    };

    return (
        <div>
            <h1 className="text-3xl font-bold mb-6">Edit Node: {settings.name}</h1>
            <form onSubmit={handleSubmit}>
                <Tabs defaultValue="overview" className="w-full">
                    <TabsList className="grid w-full grid-cols-4">
                        <TabsTrigger value="overview" className="flex items-center gap-2"><IconChartAreaLine className="h-5 w-5"/> Overview</TabsTrigger>
                        <TabsTrigger value="basic_settings" className="flex items-center gap-2"><IconServer className="h-5 w-5"/> Basic Settings</TabsTrigger>
                        <TabsTrigger value="advanced_settings" className="flex items-center gap-2"><IconServerCog className="h-5 w-5"/> Advanced</TabsTrigger>
                        <TabsTrigger value="config_file" className="flex items-center gap-2"><IconCode className="h-5 w-5"/> Config File</TabsTrigger>
                    </TabsList>
                    <Card className="mt-6">
                        <CardContent className="pt-6">
                            <TabsContent value="overview">
                                <NodeOverviewTab />
                            </TabsContent>
                            <TabsContent value="basic_settings">
                                <NodeBasicSettingsTab settings={settings} onSettingsChange={handleSettingsChange} />
                            </TabsContent>
                            <TabsContent value="advanced_settings">
                                <NodeAdvancedSettingsTab settings={settings} onSettingsChange={handleSettingsChange} />
                            </TabsContent>
                            <TabsContent value="config_file">
                                <NodeConfigFileTab />
                            </TabsContent>
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
