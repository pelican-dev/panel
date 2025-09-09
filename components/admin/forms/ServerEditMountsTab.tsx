import { Checkbox } from "@/components/ui/checkbox";
import { Label } from "@/components/ui/label";

// Mock data
const mounts = [
    {
        id: '1',
        name: 'Game Saves',
        description: '/data/saves -> /home/container/saves',
    },
    {
        id: '2',
        name: 'Global Mods',
        description: '/data/mods -> /home/container/mods',
    },
];

export const ServerEditMountsTab = () => {
    return (
        <div className="space-y-4">
            <h3 className="font-semibold">Available Mounts</h3>
            <div className="space-y-2">
                {mounts.map((mount) => (
                    <div key={mount.id} className="flex items-center space-x-2">
                        <Checkbox id={`mount-${mount.id}`} />
                        <div>
                            <Label htmlFor={`mount-${mount.id}`}>{mount.name}</Label>
                            <p className="text-sm text-muted-foreground">{mount.description}</p>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
};
