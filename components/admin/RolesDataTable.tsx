import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { IconPencil, IconEye } from "@tabler/icons-react";

const roles = [
    {
        name: 'Administrator',
        permissions: 'All',
        nodes: ['All'],
        users: 1,
    },
    {
        name: 'Support',
        permissions: 15,
        nodes: ['Node 1', 'Node 2'],
        users: 3,
    },
    {
        name: 'User',
        permissions: 5,
        nodes: [],
        users: 50,
    },
];

export const RolesDataTable = () => {
    return (
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Name</TableHead>
                    <TableHead>Permissions</TableHead>
                    <TableHead>Nodes</TableHead>
                    <TableHead>Users</TableHead>
                    <TableHead>Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                {roles.map((role) => (
                    <TableRow key={role.name}>
                        <TableCell className="font-semibold">{role.name}</TableCell>
                        <TableCell>
                            <Badge variant={role.permissions === 'All' ? 'success' : 'secondary'}>{role.permissions}</Badge>
                        </TableCell>
                        <TableCell className="flex gap-1">
                            {role.nodes.length > 0 ? (
                                role.nodes.map((node) => (
                                    <Badge key={node} variant="outline">{node}</Badge>
                                ))
                            ) : (
                                <Badge variant="secondary">All</Badge>
                            )}
                        </TableCell>
                        <TableCell>{role.users}</TableCell>
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
