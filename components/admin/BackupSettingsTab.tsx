'use client';

import { useState } from 'react';
import { SettingsCard } from "@/components/admin/SettingsCard";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import { Switch } from "@/components/ui/switch";

export const BackupSettingsTab = () => {
    const [driver, setDriver] = useState('wings');

    return (
        <div className="space-y-6">
            <SettingsCard title="Backup Driver" description="Select the driver for storing server backups.">
                <RadioGroup defaultValue={driver} onValueChange={setDriver} className="flex space-x-4">
                    <div className="flex items-center space-x-2">
                        <RadioGroupItem value="wings" id="wings" />
                        <Label htmlFor="wings">Wings</Label>
                    </div>
                    <div className="flex items-center space-x-2">
                        <RadioGroupItem value="s3" id="s3" />
                        <Label htmlFor="s3">S3</Label>
                    </div>
                </RadioGroup>
            </SettingsCard>

            <SettingsCard title="Throttle Limits" description="Configure the throttle limits for backup creation.">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <Label htmlFor="throttle-limit">Limit</Label>
                        <Input id="throttle-limit" type="number" defaultValue={5} />
                    </div>
                    <div>
                        <Label htmlFor="throttle-period">Period (seconds)</Label>
                        <Input id="throttle-period" type="number" defaultValue={60} />
                    </div>
                </div>
            </SettingsCard>

            {driver === 's3' && (
                <SettingsCard title="S3 Settings" description="Configure your S3 bucket details.">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <Label htmlFor="s3-region">Default Region</Label>
                            <Input id="s3-region" placeholder="us-east-1" />
                        </div>
                        <div>
                            <Label htmlFor="s3-access-key">Access Key</Label>
                            <Input id="s3-access-key" />
                        </div>
                        <div>
                            <Label htmlFor="s3-secret-key">Secret Key</Label>
                            <Input id="s3-secret-key" type="password" />
                        </div>
                        <div>
                            <Label htmlFor="s3-bucket">Bucket</Label>
                            <Input id="s3-bucket" />
                        </div>
                        <div>
                            <Label htmlFor="s3-endpoint">Endpoint</Label>
                            <Input id="s3-endpoint" />
                        </div>
                        <div className="flex items-center space-x-2">
                            <Switch id="s3-path-style" />
                            <Label htmlFor="s3-path-style">Use Path Style Endpoint</Label>
                        </div>
                    </div>
                </SettingsCard>
            )}
        </div>
    );
};
