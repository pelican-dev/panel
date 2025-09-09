'use client';

import { useState } from 'react';
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Switch } from '@/components/ui/switch';

const eggs = {
    minecraft: {
        name: 'Minecraft',
        variables: [
            { name: 'Server JAR File', env_variable: 'SERVER_JARFILE', default_value: 'server.jar' },
            { name: 'Server Port', env_variable: 'SERVER_PORT', default_value: '25565' },
        ],
    },
    terraria: {
        name: 'Terraria',
        variables: [
            { name: 'World Name', env_variable: 'WORLD_NAME', default_value: 'world' },
        ],
    },
};

// This is a mock server. In a real application, you would pass this data in as props.
const mockServer = {
    egg: 'minecraft',
};

export const ServerEditEggTab = () => {
    const [selectedEgg, setSelectedEgg] = useState<keyof typeof eggs>(mockServer.egg as keyof typeof eggs);

    return (
        <div className="space-y-6">
            <Card>
                <CardHeader>
                    <CardTitle>Egg Configuration</CardTitle>
                </CardHeader>
                <CardContent className="space-y-4">
                    <div className="space-y-2">
                        <Label htmlFor="egg">Egg</Label>
                        <div className="flex items-center gap-2">
                            <Select value={selectedEgg} onValueChange={(v) => setSelectedEgg(v as keyof typeof eggs)}>
                                <SelectTrigger id="egg">
                                    <SelectValue placeholder="Select an egg" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="minecraft">Minecraft</SelectItem>
                                    <SelectItem value="terraria">Terraria</SelectItem>
                                </SelectContent>
                            </Select>
                            <Button>Change Egg</Button>
                        </div>
                    </div>
                    <div className="flex items-center space-x-2">
                        <Switch id="keep-variables" />
                        <Label htmlFor="keep-variables">Keep old variables</Label>
                    </div>
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle>Environment Variables</CardTitle>
                </CardHeader>
                <CardContent className="space-y-4">
                    {eggs[selectedEgg].variables.map((variable) => (
                        <div key={variable.env_variable} className="space-y-2">
                            <Label htmlFor={variable.env_variable}>{variable.name}</Label>
                            <Input id={variable.env_variable} defaultValue={variable.default_value} />
                        </div>
                    ))}
                </CardContent>
            </Card>
        </div>
    );
};
