"use client";

import { useState, useRef } from "react";
import { useSearchParams, useRouter } from "next/navigation";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { IconEgg, IconServerCog, IconVariable, IconFileDownload } from "@tabler/icons-react";
import { EggConfigurationTab } from "@/components/admin/forms/EggConfigurationTab";
import { EggProcessManagementTab } from "@/components/admin/forms/EggProcessManagementTab";
import { EggVariablesTab, EggVariable } from "@/components/admin/forms/EggVariablesTab";
import { EggInstallScriptTab } from "@/components/admin/forms/EggInstallScriptTab";
import { PageHeader } from "@/components/layout/PageHeader";

export type EggEditState = {
  id?: number;
  uuid?: string;
  name: string;
  author: string;
  description: string;
  startup: string;
  tags: string[];
  dockerImages: { key: string; value: string }[];
  // other tabs
  stopCommand: string;
  startupConfig: string; // JSON string
  configFiles: string; // JSON string
  logConfig: string; // JSON string or array
  variables: EggVariable[];
  scriptContainer: string;
  scriptEntry: string;
  installScript: string;
};

export default function EditEggClient({ initial }: { initial: EggEditState }) {
  const [settings, setSettings] = useState<EggEditState>(initial);
  const fileInputRef = useRef<HTMLInputElement>(null);
  const searchParams = useSearchParams();
  const router = useRouter();

  // Get current tab from URL or default to configuration
  const currentTab = searchParams.get('tab') || 'configuration';

  const handleTabChange = (newTab: string) => {
    const params = new URLSearchParams(searchParams);
    params.set('tab', newTab);
    router.replace(`?${params.toString()}`);
  };

  const handleSettingsChange = (newSettings: Partial<EggEditState>) => {
    setSettings((prev) => ({ ...prev, ...newSettings }));
  };

  const handleDelete = async () => {
    console.log('Delete button clicked');
    try {
      const id = settings.uuid ?? String(settings.id ?? '');
      console.log('Deleting egg with id:', id);
      const res = await fetch(`/api/application/eggs/${id}`, { method: 'DELETE' });
      console.log('Delete response status:', res.status);
      if (!res.ok) {
        const errorText = await res.text();
        console.error('Delete failed with error:', errorText);
        throw new Error(`Delete failed: ${res.status}`);
      }
      alert('Egg deleted successfully');
      // Redirect to eggs list
      window.location.href = '/admin/eggs';
    } catch (err) {
      console.error('Delete error:', err);
      alert('Failed to delete egg: ' + (err as Error).message);
    }
  };

  const handleExport = async () => {
    try {
      const id = settings.uuid ?? String(settings.id ?? '');
      const res = await fetch(`/api/application/eggs/${id}`);
      if (!res.ok) throw new Error('Export failed');
      const j = await res.json();
      const blob = new Blob([JSON.stringify(j.attributes, null, 2)], { type: 'application/json' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `${settings.name.replace(/\s+/g, '_').toLowerCase()}_egg.json`;
      a.click();
      URL.revokeObjectURL(url);
    } catch (err) {
      console.error(err);
      alert('Failed to export egg');
    }
  };

  const handleImportClick = () => fileInputRef.current?.click();
  const handleImport = async (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;
    try {
      const text = await file.text();
      const data = JSON.parse(text);
      // Minimal merge: support name/uuid/description/startup/docker_images/tags/script
      const di = data.docker_images ? Object.entries(data.docker_images as Record<string, string>) : [];
      setSettings((prev) => ({
        ...prev,
        name: data.name ?? prev.name,
        uuid: data.uuid ?? prev.uuid,
        description: data.description ?? prev.description,
        startup: data.startup ?? prev.startup,
        dockerImages: di.length ? di.map(([key, value]) => ({ key, value })) : prev.dockerImages,
        tags: Array.isArray(data.tags) ? data.tags : prev.tags,
        scriptContainer: data.script?.container ?? prev.scriptContainer,
        scriptEntry: data.script?.entry ?? prev.scriptEntry,
        installScript: data.script?.install ?? prev.installScript,
      }));
      alert('Imported. Review changes and click Save Changes to persist.');
      e.target.value = '';
    } catch (err) {
      console.error(err);
      alert('Invalid import file. Must be JSON matching egg attributes.');
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const target = settings.uuid ?? String(settings.id ?? '');
    const attributes = {
      name: settings.name,
      uuid: settings.uuid,
      description: settings.description,
      startup: settings.startup,
      docker_images: Object.fromEntries(settings.dockerImages.map((d) => [d.key, d.value]).filter(([k, v]) => k && v)),
      // tags/features UI not built yet, but include empty arrays for shape
      tags: settings.tags ?? [],
      features: [],
      script: {
        container: settings.scriptContainer,
        entry: settings.scriptEntry,
        install: settings.installScript,
      },
    };
    try {
      const res = await fetch(`/api/application/eggs/${target}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ attributes }),
      });
      if (!res.ok) throw new Error(`Save failed (${res.status})`);
      console.log('Egg saved');
      // naive feedback
      alert('Egg saved');
    } catch (err) {
      console.error(err);
      alert('Failed to save egg');
    }
  };

  return (
    <div>
      <PageHeader
        title={`Edit Egg: ${settings.name}`}
        right={(
          <div className="flex items-center gap-2">
            <Button type="button" variant="destructive" onClick={handleDelete}>Delete</Button>
            <Button type="button" variant="secondary" onClick={handleExport}>Export</Button>
            <input ref={fileInputRef} type="file" accept="application/json" className="hidden" onChange={handleImport} />
            <Button type="button" variant="outline" onClick={handleImportClick}>Import</Button>
          </div>
        )}
      />
      <div className="mb-4 text-sm text-muted-foreground">Egg UUID: {settings.uuid ?? "(none)"}</div>
      <form onSubmit={handleSubmit}>
        <Tabs value={currentTab} onValueChange={handleTabChange} className="w-full">
          <TabsList className="grid w-full grid-cols-4">
            <TabsTrigger value="configuration" className="flex items-center gap-2">
              <IconEgg className="h-5 w-5" /> Configuration
            </TabsTrigger>
            <TabsTrigger value="process_management" className="flex items-center gap-2">
              <IconServerCog className="h-5 w-5" /> Process Management
            </TabsTrigger>
            <TabsTrigger value="egg_variables" className="flex items-center gap-2">
              <IconVariable className="h-5 w-5" /> Variables
            </TabsTrigger>
            <TabsTrigger value="install_script" className="flex items-center gap-2">
              <IconFileDownload className="h-5 w-5" /> Install Script
            </TabsTrigger>
          </TabsList>
          <Card className="mt-6">
            <CardContent className="pt-6">
              <TabsContent value="configuration">
                <EggConfigurationTab settings={settings} onSettingsChange={handleSettingsChange} />
              </TabsContent>
              <TabsContent value="process_management">
                <EggProcessManagementTab settings={settings} onSettingsChange={handleSettingsChange} />
              </TabsContent>
              <TabsContent value="egg_variables">
                <EggVariablesTab
                  variables={settings.variables}
                  onVariablesChange={(v) => handleSettingsChange({ variables: v })}
                />
              </TabsContent>
              <TabsContent value="install_script">
                <EggInstallScriptTab settings={settings} onSettingsChange={handleSettingsChange} />
              </TabsContent>
            </CardContent>
          </Card>
          <div className="flex justify-end gap-2 mt-6">
            <Button type="button" variant="outline">
              Cancel
            </Button>
            <Button type="submit">Save Changes</Button>
          </div>
        </Tabs>
      </form>
    </div>
  );
}
