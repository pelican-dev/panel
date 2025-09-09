'use client';

import { useState } from 'react';
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Textarea } from "@/components/ui/textarea";
import { Switch } from "@/components/ui/switch";

const eggs = {
    minecraft: {
        startup: 'java -Xms128M -Xmx{{SERVER_MEMORY}}M -jar {{SERVER_JARFILE}}',
        variables: [
            { name: 'Server JAR File', env_variable: 'SERVER_JARFILE', default_value: 'server.jar' },
            { name: 'Server Port', env_variable: 'SERVER_PORT', default_value: '25565' },
        ],
    },
    terraria: {
        startup: './TerrariaServer.bin.x86_64 -port {{SERVER_PORT}} -world {{WORLD_NAME}}.wld',
        variables: [
            { name: 'World Name', env_variable: 'WORLD_NAME', default_value: 'world' },
        ],
    },
};

export const ServerEggConfigurationStep = () => {
    const [selectedEgg, setSelectedEgg] = useState<keyof typeof eggs>('minecraft');

    return (
        <div className="space-y-6">
            <div className="space-y-2">
                <Label htmlFor="egg">Egg</Label>
                <Select value={selectedEgg} onValueChange={(v) => setSelectedEgg(v as keyof typeof eggs)}>
                    <SelectTrigger id="egg">
                        <SelectValue placeholder="Select an egg" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="minecraft">Minecraft</SelectItem>
                        <SelectItem value="terraria">Terraria</SelectItem>
                    </SelectContent>
                </Select>
            </div>
            <div className="space-y-2">
                <Label htmlFor="startup">Startup Command</Label>
                <Textarea id="startup" value={eggs[selectedEgg].startup} className="font-mono" readOnly />
            </div>
            <div className="space-y-4">
                <Label>Environment Variables</Label>
                {eggs[selectedEgg].variables.map((variable) => (
                    <div key={variable.env_variable} className="space-y-2">
                        <Label htmlFor={variable.env_variable}>{variable.name}</Label>
                        <Input id={variable.env_variable} defaultValue={variable.default_value} />
                    </div>
                ))}
            </div>
            <div className="flex items-center space-x-2">
                <Switch id="skip-scripts" />
                <Label htmlFor="skip-scripts">Skip Install Script</Label>
            </div>
        </div>
    );
};
