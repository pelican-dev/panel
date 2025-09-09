import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { Button } from "@/components/ui/button";

// This is a mock server. In a real application, you would pass this data in as props.
const mockServer = {
    startup: 'java -Xms128M -Xmx1024M -jar server.jar',
};

export const ServerEditStartupTab = () => {
    return (
        <div className="space-y-6">
            <div className="space-y-2">
                <Label htmlFor="startup">Startup Command</Label>
                <Textarea id="startup" defaultValue={mockServer.startup} rows={8} className="font-mono" />
            </div>
            <div className="flex justify-end">
                <Button variant="outline">Preview Startup Command</Button>
            </div>
        </div>
    );
};
