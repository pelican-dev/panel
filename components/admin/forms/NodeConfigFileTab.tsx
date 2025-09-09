import { Textarea } from "@/components/ui/textarea";
import { Button } from "@/components/ui/button";
import { IconClipboard } from "@tabler/icons-react";

const generatedConfig = `
debug: false
api:
  host: 0.0.0.0
  port: 8080
  ssl:
    enabled: true
    cert: /etc/pelican/certs/certificate.pem
    key: /etc/pelican/certs/private.key
  upload_limit: 100
`;

export const NodeConfigFileTab = () => {
    return (
        <div className="space-y-4">
            <div>
                <h3 className="font-semibold">Instructions</h3>
                <p className="text-sm text-muted-foreground">Copy the configuration below into your node&apos;s config.yml file.</p>
            </div>
            <div className="relative">
                <Textarea readOnly value={generatedConfig} rows={15} className="font-mono" />
                <Button 
                    variant="ghost" 
                    size="icon" 
                    className="absolute top-2 right-2"
                    onClick={() => navigator.clipboard.writeText(generatedConfig)}
                >
                    <IconClipboard className="h-4 w-4" />
                </Button>
            </div>
        </div>
    );
};
