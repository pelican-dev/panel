import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from "@/components/ui/accordion";
import { SettingsCard } from "@/components/admin/SettingsCard";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Switch } from "@/components/ui/switch";

export const OAuthSettingsTab = () => {
    return (
        <Accordion type="single" collapsible className="w-full space-y-4">
            <AccordionItem value="discord">
                <AccordionTrigger className="text-lg font-semibold">Discord</AccordionTrigger>
                <AccordionContent>
                    <div className="space-y-4 pt-4">
                        <SettingsCard title="Enable Discord OAuth" description="Allow users to log in with their Discord account.">
                            <div className="flex items-center space-x-2">
                                <Switch id="discord-enabled" />
                                <Label htmlFor="discord-enabled">Enable</Label>
                            </div>
                        </SettingsCard>
                        <SettingsCard title="Client ID" description="Your Discord application's client ID.">
                            <Input placeholder="Enter your client ID" />
                        </SettingsCard>
                        <SettingsCard title="Client Secret" description="Your Discord application's client secret.">
                            <Input placeholder="Enter your client secret" type="password" />
                        </SettingsCard>
                    </div>
                </AccordionContent>
            </AccordionItem>
            <AccordionItem value="github">
                <AccordionTrigger className="text-lg font-semibold">GitHub</AccordionTrigger>
                <AccordionContent>
                    <div className="space-y-4 pt-4">
                        <SettingsCard title="Enable GitHub OAuth" description="Allow users to log in with their GitHub account.">
                            <div className="flex items-center space-x-2">
                                <Switch id="github-enabled" />
                                <Label htmlFor="github-enabled">Enable</Label>
                            </div>
                        </SettingsCard>
                        <SettingsCard title="Client ID" description="Your GitHub application's client ID.">
                            <Input placeholder="Enter your client ID" />
                        </SettingsCard>
                        <SettingsCard title="Client Secret" description="Your GitHub application's client secret.">
                            <Input placeholder="Enter your client secret" type="password" />
                        </SettingsCard>
                    </div>
                </AccordionContent>
            </AccordionItem>
            <AccordionItem value="google">
                <AccordionTrigger className="text-lg font-semibold">Google</AccordionTrigger>
                <AccordionContent>
                    <div className="space-y-4 pt-4">
                        <SettingsCard title="Enable Google OAuth" description="Allow users to log in with their Google account.">
                            <div className="flex items-center space-x-2">
                                <Switch id="google-enabled" />
                                <Label htmlFor="google-enabled">Enable</Label>
                            </div>
                        </SettingsCard>
                        <SettingsCard title="Client ID" description="Your Google application's client ID.">
                            <Input placeholder="Enter your client ID" />
                        </SettingsCard>
                        <SettingsCard title="Client Secret" description="Your Google application's client secret.">
                            <Input placeholder="Enter your client secret" type="password" />
                        </SettingsCard>
                    </div>
                </AccordionContent>
            </AccordionItem>
        </Accordion>
    );
};
