'use client';

import { useState } from 'react';
import { SettingsCard } from "@/components/admin/SettingsCard";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import { Button } from "@/components/ui/button";
import { IconSend } from '@tabler/icons-react';

export const MailSettingsTab = () => {
    const [mailer, setMailer] = useState('smtp');

    return (
        <div className="space-y-6">
            <SettingsCard title="Mail Driver" description="Select the driver you want to use for sending emails.">
                <div className="flex justify-between items-center">
                    <RadioGroup defaultValue={mailer} onValueChange={setMailer} className="flex flex-wrap gap-4">
                        {['log', 'smtp', 'mailgun', 'mandrill', 'postmark', 'sendmail'].map((driver) => (
                            <div key={driver} className="flex items-center space-x-2">
                                <RadioGroupItem value={driver} id={driver} />
                                <Label htmlFor={driver} className="capitalize">{driver}</Label>
                            </div>
                        ))}
                    </RadioGroup>
                    <Button variant="outline" className="flex items-center gap-2">
                        <IconSend className="h-4 w-4" />
                        Send Test Mail
                    </Button>
                </div>
            </SettingsCard>

            <SettingsCard title="From Settings" description="Configure the sender address for outgoing emails.">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <Label htmlFor="from-address">From Address</Label>
                        <Input id="from-address" placeholder="noreply@example.com" />
                    </div>
                    <div>
                        <Label htmlFor="from-name">From Name</Label>
                        <Input id="from-name" placeholder="Pelican Panel" />
                    </div>
                </div>
            </SettingsCard>

            {mailer === 'smtp' && (
                <SettingsCard title="SMTP Settings" description="Configure your SMTP server details.">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <Label htmlFor="smtp-host">Host</Label>
                            <Input id="smtp-host" placeholder="smtp.mailgun.org" />
                        </div>
                        <div>
                            <Label htmlFor="smtp-port">Port</Label>
                            <Input id="smtp-port" placeholder="587" type="number" />
                        </div>
                        <div>
                            <Label htmlFor="smtp-username">Username</Label>
                            <Input id="smtp-username" placeholder="your-username" />
                        </div>
                        <div>
                            <Label htmlFor="smtp-password">Password</Label>
                            <Input id="smtp-password" type="password" />
                        </div>
                        <div>
                            <Label>Encryption</Label>
                            <RadioGroup defaultValue="tls" className="flex space-x-4 mt-2">
                                <div className="flex items-center space-x-2">
                                    <RadioGroupItem value="tls" id="tls" />
                                    <Label htmlFor="tls">TLS</Label>
                                </div>
                                <div className="flex items-center space-x-2">
                                    <RadioGroupItem value="ssl" id="ssl" />
                                    <Label htmlFor="ssl">SSL</Label>
                                </div>
                            </RadioGroup>
                        </div>
                    </div>
                </SettingsCard>
            )}

            {mailer === 'mailgun' && (
                <SettingsCard title="Mailgun Settings" description="Configure your Mailgun API details.">
                    <div className="space-y-4">
                        <div>
                            <Label htmlFor="mailgun-domain">Domain</Label>
                            <Input id="mailgun-domain" placeholder="your-domain.com" />
                        </div>
                        <div>
                            <Label htmlFor="mailgun-secret">Secret</Label>
                            <Input id="mailgun-secret" type="password" />
                        </div>
                        <div>
                            <Label htmlFor="mailgun-endpoint">Endpoint</Label>
                            <Input id="mailgun-endpoint" defaultValue="api.mailgun.net" />
                        </div>
                    </div>
                </SettingsCard>
            )}
        </div>
    );
};
