import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Textarea } from "@/components/ui/textarea";

export const ServerInformationStep = () => {
    return (
        <div className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="space-y-2">
                    <Label htmlFor="name">Server Name</Label>
                    <Input id="name" placeholder="My Awesome Server" />
                </div>
                <div className="space-y-2">
                    <Label htmlFor="owner">Server Owner</Label>
                    <Select>
                        <SelectTrigger id="owner">
                            <SelectValue placeholder="Select an owner" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="johndoe">johndoe</SelectItem>
                            <SelectItem value="janedoe">janedoe</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
            </div>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="space-y-2">
                    <Label htmlFor="node">Node</Label>
                    <Select>
                        <SelectTrigger id="node">
                            <SelectValue placeholder="Select a node" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="node1">Node 1</SelectItem>
                            <SelectItem value="node2">Node 2</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div className="space-y-2">
                    <Label htmlFor="allocation">Primary Allocation</Label>
                    <Select>
                        <SelectTrigger id="allocation">
                            <SelectValue placeholder="Select an allocation" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="alloc1">192.168.1.100:25565</SelectItem>
                            <SelectItem value="alloc2">192.168.1.100:25566</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
            </div>
            <div className="space-y-2">
                <Label htmlFor="description">Description</Label>
                <Textarea id="description" placeholder="A brief description of the server." />
            </div>
        </div>
    );
};
