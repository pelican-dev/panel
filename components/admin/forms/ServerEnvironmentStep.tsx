'use client';

import { useState } from 'react';
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Switch } from "@/components/ui/switch";

export const ServerEnvironmentStep = () => {
    const [unlimitedCpu, setUnlimitedCpu] = useState(false);
    const [unlimitedMemory, setUnlimitedMemory] = useState(false);
    const [unlimitedDisk, setUnlimitedDisk] = useState(false);

    return (
        <div className="space-y-6">
            <div className="space-y-4">
                <div className="flex items-center gap-4">
                    <Label className="w-24">CPU</Label>
                    <div className="flex-1 grid grid-cols-2 gap-4">
                        <Input type="number" placeholder="CPU Limit (%)" disabled={unlimitedCpu} />
                        <div className="flex items-center space-x-2">
                            <Switch id="unlimited-cpu" checked={unlimitedCpu} onCheckedChange={setUnlimitedCpu} />
                            <Label htmlFor="unlimited-cpu">Unlimited</Label>
                        </div>
                    </div>
                </div>
                <div className="flex items-center gap-4">
                    <Label className="w-24">Memory</Label>
                    <div className="flex-1 grid grid-cols-2 gap-4">
                        <Input type="number" placeholder="Memory Limit (MB)" disabled={unlimitedMemory} />
                        <div className="flex items-center space-x-2">
                            <Switch id="unlimited-memory" checked={unlimitedMemory} onCheckedChange={setUnlimitedMemory} />
                            <Label htmlFor="unlimited-memory">Unlimited</Label>
                        </div>
                    </div>
                </div>
                <div className="flex items-center gap-4">
                    <Label className="w-24">Disk</Label>
                    <div className="flex-1 grid grid-cols-2 gap-4">
                        <Input type="number" placeholder="Disk Limit (MB)" disabled={unlimitedDisk} />
                        <div className="flex items-center space-x-2">
                            <Switch id="unlimited-disk" checked={unlimitedDisk} onCheckedChange={setUnlimitedDisk} />
                            <Label htmlFor="unlimited-disk">Unlimited</Label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};
