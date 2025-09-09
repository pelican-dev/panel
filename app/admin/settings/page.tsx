import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { GeneralSettingsTab } from "@/components/admin/GeneralSettingsTab";
import { CaptchaSettingsTab } from "@/components/admin/CaptchaSettingsTab";
import { MailSettingsTab } from "@/components/admin/MailSettingsTab";
import { BackupSettingsTab } from "@/components/admin/BackupSettingsTab";
import { OAuthSettingsTab } from "@/components/admin/OAuthSettingsTab";
import { MiscSettingsTab } from "@/components/admin/MiscSettingsTab";
import { IconHome, IconShield, IconMail, IconBox, IconBrandOauth, IconTool } from "@tabler/icons-react";

export default function AdminSettings() {
  return (
    <div>
      <h1 className="text-3xl font-bold mb-6">Settings</h1>
      <Tabs defaultValue="general" className="w-full">
        <TabsList className="grid w-full grid-cols-6">
          <TabsTrigger value="general" className="flex items-center gap-2"><IconHome className="h-5 w-5"/> General</TabsTrigger>
          <TabsTrigger value="captcha" className="flex items-center gap-2"><IconShield className="h-5 w-5"/> Captcha</TabsTrigger>
          <TabsTrigger value="mail" className="flex items-center gap-2"><IconMail className="h-5 w-5"/> Mail</TabsTrigger>
          <TabsTrigger value="backup" className="flex items-center gap-2"><IconBox className="h-5 w-5"/> Backup</TabsTrigger>
          <TabsTrigger value="oauth" className="flex items-center gap-2"><IconBrandOauth className="h-5 w-5"/> OAuth</TabsTrigger>
          <TabsTrigger value="misc" className="flex items-center gap-2"><IconTool className="h-5 w-5"/> Misc</TabsTrigger>
        </TabsList>
        <TabsContent value="general" className="mt-6">
          <GeneralSettingsTab />
        </TabsContent>
        <TabsContent value="captcha" className="mt-6"><CaptchaSettingsTab /></TabsContent>
        <TabsContent value="mail" className="mt-6"><MailSettingsTab /></TabsContent>
        <TabsContent value="backup" className="mt-6"><BackupSettingsTab /></TabsContent>
        <TabsContent value="oauth" className="mt-6"><OAuthSettingsTab /></TabsContent>
        <TabsContent value="misc" className="mt-6"><MiscSettingsTab /></TabsContent>
      </Tabs>
    </div>
  );
}
