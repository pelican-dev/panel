import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { IconPencil, IconEye } from "@tabler/icons-react";

const databaseHosts = [
    {
        name: 'Local MariaDB',
        host: '127.0.0.1',
        port: 3306,
        username: 'root',
        databases: 15,
        nodes: ['Node 1', 'Node 2'],
    },
    {
        name: 'Remote PostgreSQL',
        host: 'db.example.com',
        port: 5432,
        username: 'postgres',
        databases: 8,
        nodes: [],
    },
];

export const DatabaseHostsDataTable = () => {
    return (
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Name</TableHead>
                    <TableHead>Host</TableHead>
                    <TableHead>Port</TableHead>
                    <TableHead>Username</TableHead>
                    <TableHead>Databases</TableHead>
                    <TableHead>Nodes</TableHead>
                    <TableHead>Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                {databaseHosts.map((host) => (
                    <TableRow key={host.name}>
                        <TableCell className="font-semibold">{host.name}</TableCell>
                        <TableCell>{host.host}</TableCell>
                        <TableCell>{host.port}</TableCell>
                        <TableCell>{host.username}</TableCell>
                        <TableCell>{host.databases}</TableCell>
                        <TableCell className="flex gap-1">
                            {host.nodes.length > 0 ? (
                                host.nodes.map((node) => (
                                    <Badge key={node} variant="outline">{node}</Badge>
                                ))
                            ) : (
                                <Badge variant="secondary">None</Badge>
                            )}
                        </TableCell>
                        <TableCell className="flex gap-2">
                            <Button variant="outline" size="icon"><IconEye className="h-4 w-4" /></Button>
                            <Button variant="outline" size="icon"><IconPencil className="h-4 w-4" /></Button>
                        </TableCell>
                    </TableRow>
                ))}
            </TableBody>
        </Table>
    );
};
