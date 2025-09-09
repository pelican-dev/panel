import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { Button } from "@/components/ui/button";
import { IconPlus, IconTrash } from "@tabler/icons-react";

interface DockerImage {
    key: string;
    value: string;
}

interface EggConfiguration {
    name: string;
    author: string;
    description: string;
    startup: string;
    tags: string[];
    dockerImages: DockerImage[];
}

interface EggConfigurationTabProps {
    settings: EggConfiguration;
    onSettingsChange: (newSettings: Partial<EggConfiguration>) => void;
}

export const EggConfigurationTab = ({ settings, onSettingsChange }: EggConfigurationTabProps) => {
    const addImage = () => {
        onSettingsChange({ dockerImages: [...settings.dockerImages, { key: '', value: '' }] });
    };

    const removeImage = (index: number) => {
        onSettingsChange({ dockerImages: settings.dockerImages.filter((_, i) => i !== index) });
    };

    const handleImageChange = (index: number, field: 'key' | 'value', value: string) => {
        const newImages = [...settings.dockerImages];
        newImages[index][field] = value;
        onSettingsChange({ dockerImages: newImages });
    };

    return (
        <div className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="space-y-2">
                    <Label htmlFor="name">Name</Label>
                    <Input id="name" placeholder="Minecraft" value={settings.name} onChange={(e) => onSettingsChange({ name: e.target.value })} className="bg-muted" />
                </div>
                <div className="space-y-2">
                    <Label htmlFor="author">Author</Label>
                    <Input id="author" placeholder="author@example.com" value={settings.author} onChange={(e) => onSettingsChange({ author: e.target.value })} className="bg-muted" />
                </div>
            </div>
            <div className="space-y-2">
                <Label htmlFor="description">Description</Label>
                <Textarea id="description" placeholder="A vanilla Minecraft server." value={settings.description} onChange={(e) => onSettingsChange({ description: e.target.value })} className="bg-muted" />
            </div>
            <div className="space-y-2">
                <Label htmlFor="startup">Startup Command</Label>
                <Textarea id="startup" placeholder="java -Xms128M -Xmx{{SERVER_MEMORY}}M -jar {{SERVER_JARFILE}}" className="font-mono bg-muted" value={settings.startup} onChange={(e) => onSettingsChange({ startup: e.target.value })} />
            </div>
            <div className="space-y-2">
                <Label htmlFor="tags">Tags</Label>
                <Input
                    id="tags"
                    placeholder="comma,separated,tags"
                    value={(settings.tags ?? []).join(', ')}
                    onChange={(e) => {
                        const parts = e.target.value.split(',').map(s => s.trim()).filter(Boolean);
                        onSettingsChange({ tags: parts });
                    }}
                />
            </div>
            <div className="space-y-2">
                <Label>Docker Images</Label>
                <div className="space-y-2">
                    {settings.dockerImages.map((image, index) => (
                        <div key={index} className="flex items-center gap-2">
                            <Input 
                                placeholder="Name (e.g., Default)" 
                                value={image.key} 
                                onChange={(e) => handleImageChange(index, 'key', e.target.value)} 
                                className="bg-muted"
                            />
                            <Input 
                                placeholder="Image URL (e.g., ghcr.io/pelican-eggs/yolks:debian)" 
                                value={image.value} 
                                onChange={(e) => handleImageChange(index, 'value', e.target.value)} 
                                className="bg-muted"
                            />
                            <Button variant="destructive" size="icon" onClick={() => removeImage(index)}>
                                <IconTrash className="h-4 w-4" />
                            </Button>
                        </div>
                    ))}
                </div>
                <Button variant="outline" size="sm" onClick={addImage} className="flex items-center gap-2">
                    <IconPlus className="h-4 w-4" />
                    Add Image
                </Button>
            </div>
        </div>
    );
};
