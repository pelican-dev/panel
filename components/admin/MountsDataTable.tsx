import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { IconPencil, IconEye, IconWriting, IconWritingOff } from "@tabler/icons-react";

const mounts = [
    {
        name: 'Game Saves',
        source: '/data/saves',
        target: '/home/container/saves',
        eggs: ['Minecraft', 'Terraria'],
        nodes: ['Node 1'],
        read_only: false,
    },
    {
        name: 'Global Mods',
        source: '/data/mods',
        target: '/home/container/mods',
        eggs: [],
        nodes: [],
        read_only: true,
    },
];

export const MountsDataTable = () => {
    return (
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Name</TableHead>
                    <TableHead>Eggs</TableHead>
                    <TableHead>Nodes</TableHead>
                    <TableHead>Read Only</TableHead>
                    <TableHead>Actions</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                {mounts.map((mount) => (
                    <TableRow key={mount.name}>
                        <TableCell>
                            <p className="font-semibold">{mount.name}</p>
                            <p className="text-sm text-muted-foreground font-mono">{mount.source} -&gt; {mount.target}</p>
                        </TableCell>
                        <TableCell className="flex gap-1">
                            {mount.eggs.length > 0 ? (
                                mount.eggs.map((egg) => (
                                    <Badge key={egg} variant="outline">{egg}</Badge>
                                ))
                            ) : (
                                <Badge variant="secondary">All</Badge>
                            )}
                        </TableCell>
                        <TableCell className="flex gap-1">
                            {mount.nodes.length > 0 ? (
                                mount.nodes.map((node) => (
                                    <Badge key={node} variant="outline">{node}</Badge>
                                ))
                            ) : (
                                <Badge variant="secondary">All</Badge>
                            )}
                        </TableCell>
                        <TableCell>
                            <Badge variant={mount.read_only ? 'success' : 'warning'} className="flex items-center gap-1 w-fit">
                                {mount.read_only ? <IconWritingOff className="h-4 w-4" /> : <IconWriting className="h-4 w-4" />}
                                {mount.read_only ? 'Read Only' : 'Writable'}
                            </Badge>
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
