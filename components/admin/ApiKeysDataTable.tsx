import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Button } from "@/components/ui/button";
import { IconTrash, IconClipboard } from "@tabler/icons-react";

const apiKeys = [
    {
        key: 'ptlc_1234567890abcdef',
        description: 'My awesome API key',
        lastUsed: '2 days ago',
        created: '1 month ago',
        createdBy: 'johndoe',
    },
    {
        key: 'ptlc_fedcba0987654321',
        description: 'Discord Bot Key',
        lastUsed: 'Never',
        created: '3 weeks ago',
        createdBy: 'janedoe',
    },
];

export const ApiKeysDataTable = () => {
    return (
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Key</TableHead>
                    <TableHead>Description</TableHead>
                    <TableHead>Last Used</TableHead>
                    <TableHead>Created</TableHead>
                    <TableHead>Created By</TableHead>
                    <TableHead>Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                {apiKeys.map((apiKey) => (
                    <TableRow key={apiKey.key}>
                        <TableCell className="font-mono flex items-center gap-2">
                            <span>{apiKey.key}</span>
                            <Button variant="ghost" size="icon" onClick={() => navigator.clipboard.writeText(apiKey.key)}>
                                <IconClipboard className="h-4 w-4" />
                            </Button>
                        </TableCell>
                        <TableCell>{apiKey.description}</TableCell>
                        <TableCell>{apiKey.lastUsed}</TableCell>
                        <TableCell>{apiKey.created}</TableCell>
                        <TableCell>{apiKey.createdBy}</TableCell>
                        <TableCell>
                            <Button variant="destructive" size="icon"><IconTrash className="h-4 w-4" /></Button>
                        </TableCell>
                    </TableRow>
                ))}
            </TableBody>
        </Table>
    );
};
