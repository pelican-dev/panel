import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Button } from "@/components/ui/button";
import { IconPencil, IconEye, IconCopy, IconBrandDiscord, IconWebhook } from "@tabler/icons-react";

const webhooks = [
    {
        type: 'discord',
        endpoint: 'https://discord.com/api/webhooks/12345/...',
        description: 'Server start/stop notifications',
    },
    {
        type: 'regular',
        endpoint: 'https://my-app.com/webhook/pelican',
        description: 'Custom integration for server events',
    },
];

export const WebhooksDataTable = () => {
    return (
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Type</TableHead>
                    <TableHead>Endpoint</TableHead>
                    <TableHead>Description</TableHead>
                    <TableHead>Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                {webhooks.map((webhook) => (
                    <TableRow key={webhook.endpoint}>
                        <TableCell>
                            {webhook.type === 'discord' ? 
                                <IconBrandDiscord className="h-6 w-6 text-indigo-500" /> : 
                                <IconWebhook className="h-6 w-6" />
                            }
                        </TableCell>
                        <TableCell className="font-mono">{webhook.endpoint}</TableCell>
                        <TableCell>{webhook.description}</TableCell>
                        <TableCell className="flex gap-2">
                            <Button variant="outline" size="icon"><IconEye className="h-4 w-4" /></Button>
                            <Button variant="outline" size="icon"><IconPencil className="h-4 w-4" /></Button>
                            <Button variant="outline" size="icon"><IconCopy className="h-4 w-4" /></Button>
                        </TableCell>
                    </TableRow>
                ))}
            </TableBody>
        </Table>
    );
};
