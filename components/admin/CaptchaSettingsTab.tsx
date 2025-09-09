import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from "@/components/ui/accordion";
import { SettingsCard } from "@/components/admin/SettingsCard";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Switch } from "@/components/ui/switch";

export const CaptchaSettingsTab = () => {
    return (
        <Accordion type="single" collapsible className="w-full space-y-4">
            <AccordionItem value="hcaptcha">
                <AccordionTrigger className="text-lg font-semibold">hCaptcha</AccordionTrigger>
                <AccordionContent>
                    <div className="space-y-4 pt-4">
                        <SettingsCard title="Enable hCaptcha" description="Enable or disable hCaptcha for user verification.">
                            <div className="flex items-center space-x-2">
                                <Switch id="hcaptcha-enabled" />
                                <Label htmlFor="hcaptcha-enabled">Enable</Label>
                            </div>
                        </SettingsCard>
                        <SettingsCard title="Site Key" description="Your hCaptcha site key.">
                            <Input placeholder="Enter your site key" />
                        </SettingsCard>
                        <SettingsCard title="Secret Key" description="Your hCaptcha secret key.">
                            <Input placeholder="Enter your secret key" type="password" />
                        </SettingsCard>
                    </div>
                </AccordionContent>
            </AccordionItem>
            <AccordionItem value="recaptcha">
                <AccordionTrigger className="text-lg font-semibold">reCAPTCHA</AccordionTrigger>
                <AccordionContent>
                    <div className="space-y-4 pt-4">
                        <SettingsCard title="Enable reCAPTCHA" description="Enable or disable reCAPTCHA for user verification.">
                            <div className="flex items-center space-x-2">
                                <Switch id="recaptcha-enabled" />
                                <Label htmlFor="recaptcha-enabled">Enable</Label>
                            </div>
                        </SettingsCard>
                        <SettingsCard title="Site Key" description="Your reCAPTCHA site key.">
                            <Input placeholder="Enter your site key" />
                        </SettingsCard>
                        <SettingsCard title="Secret Key" description="Your reCAPTCHA secret key.">
                            <Input placeholder="Enter your secret key" type="password" />
                        </SettingsCard>
                    </div>
                </AccordionContent>
            </AccordionItem>
        </Accordion>
    );
};
