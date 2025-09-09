import { SettingsCard } from "@/components/admin/SettingsCard";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Switch } from "@/components/ui/switch";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";

export const GeneralSettingsTab = () => {
    return (
        <div className="space-y-6">
            <SettingsCard title="Application Name" description="This is the name of your application.">
                <Input defaultValue="Pelican Panel" />
            </SettingsCard>
            <SettingsCard title="Application Logo" description="This is the logo of your application. It must be a valid URL.">
                <Input defaultValue="/pelican.svg" />
            </SettingsCard>
            <SettingsCard title="Application Favicon" description="This is the favicon of your application. It must be a valid URL.">
                <Input defaultValue="/pelican.ico" />
            </SettingsCard>
            <SettingsCard title="Debug Mode" description="Enable or disable debug mode.">
                <div className="flex items-center space-x-2">
                    <Switch id="debug-mode" defaultChecked />
                    <Label htmlFor="debug-mode">Enable</Label>
                </div>
            </SettingsCard>
            <SettingsCard title="Avatar Provider" description="Choose the provider for user avatars.">
                <Select defaultValue="gravatar">
                    <SelectTrigger>
                        <SelectValue placeholder="Select a provider" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="gravatar">Gravatar</SelectItem>
                        <SelectItem value="ui-avatars">UI Avatars</SelectItem>
                    </SelectContent>
                </Select>
            </SettingsCard>
            <SettingsCard title="Unit Prefix" description="Choose the unit prefix for resource display.">
                <RadioGroup defaultValue="decimal" className="flex space-x-4">
                    <div className="flex items-center space-x-2">
                        <RadioGroupItem value="decimal" id="decimal" />
                        <Label htmlFor="decimal">Decimal (KB, MB, GB)</Label>
                    </div>
                    <div className="flex items-center space-x-2">
                        <RadioGroupItem value="binary" id="binary" />
                        <Label htmlFor="binary">Binary (KiB, MiB, GiB)</Label>
                    </div>
                </RadioGroup>
            </SettingsCard>
        </div>
    );
};
