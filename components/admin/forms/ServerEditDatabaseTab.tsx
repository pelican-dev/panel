import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { IconPlus, IconTrash } from "@tabler/icons-react";

// Mock data
const databases = [
    {
        name: 's1_default',
        username: 'u1_abcdef',
        host: '127.0.0.1',
        port: 3306,
    },
];

export const ServerEditDatabaseTab = () => {
    return (
        <div className="space-y-6">
            <div className="flex justify-end">
                <Button className="flex items-center gap-2">
                    <IconPlus className="h-4 w-4" />
                    New Database
                </Button>
            </div>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Database</TableHead>
                        <TableHead>Username</TableHead>
                        <TableHead>Host</TableHead>
                        <TableHead>Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {databases.map((db) => (
                        <TableRow key={db.name}>
                            <TableCell>{db.name}</TableCell>
                            <TableCell>{db.username}</TableCell>
                            <TableCell>{db.host}:{db.port}</TableCell>
                            <TableCell>
                                <Button variant="destructive" size="icon">
                                    <IconTrash className="h-4 w-4" />
                                </Button>
                            </TableCell>
                        </TableRow>
                    ))}
                </TableBody>
            </Table>
        </div>
    );
};
