"use client";

import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, TableFooter } from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
import { IconLock, IconLockOpenOff, IconEyeCheck, IconEyeCancel } from "@tabler/icons-react";
import { useState } from "react";
import { ActionsCell } from "@/components/tables/ActionsCell";
import { TablePaginationFooter } from "@/components/tables/TablePaginationFooter";

const nodes = [
    {
        health: 'healthy',
        name: 'Node 1',
        address: 'us-east.node.pelican.dev',
        ssl: true,
        public: true,
        servers: 5,
    },
    {
        health: 'unhealthy',
        name: 'Node 2',
        address: 'eu-west.node.pelican.dev',
        ssl: false,
        public: true,
        servers: 2,
    },
    {
        health: 'maintenance',
        name: 'Dev Node',
        address: 'dev.node.pelican.dev',
        ssl: true,
        public: false,
        servers: 10,
    },
];

const getHealthVariant = (health: string) => {
    switch (health) {
        case 'healthy':
            return 'success';
        case 'unhealthy':
            return 'destructive';
        default:
            return 'secondary';
    }
};

export const NodesDataTable = () => {
    const [currentPage, setCurrentPage] = useState(1);
    const [perPage, setPerPage] = useState(10);
    const totalCount = nodes.length;

    const handlePerPageChange = (value: string) => {
        setPerPage(value === 'all' ? totalCount : parseInt(value));
        setCurrentPage(1);
    };

    const displayed = perPage >= totalCount ? nodes : nodes.slice((currentPage - 1) * perPage, currentPage * perPage);
    const startItem = Math.min((currentPage - 1) * perPage + 1, totalCount);
    const endItem = Math.min(currentPage * perPage, totalCount);

    return (
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Health</TableHead>
                    <TableHead>Name</TableHead>
                    <TableHead>Address</TableHead>
                    <TableHead>SSL</TableHead>
                    <TableHead>Public</TableHead>
                    <TableHead>Servers</TableHead>
                    <TableHead className="text-right"><span className="sr-only">Actions</span></TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                {displayed.map((node) => (
                    <TableRow key={node.name}>
                        <TableCell>
                            <Badge variant={getHealthVariant(node.health)} className="capitalize">{node.health}</Badge>
                        </TableCell>
                        <TableCell>{node.name}</TableCell>
                        <TableCell>{node.address}</TableCell>
                        <TableCell>{node.ssl ? <IconLock className="h-6 w-6 text-green-500" /> : <IconLockOpenOff className="h-6 w-6 text-red-500" />}</TableCell>
                        <TableCell>{node.public ? <IconEyeCheck className="h-6 w-6 text-green-500" /> : <IconEyeCancel className="h-6 w-6 text-gray-500" />}</TableCell>
                        <TableCell>{node.servers}</TableCell>
                        <TableCell className="text-right">
                            <ActionsCell editHref={`/admin/nodes/${node.name}/edit`} />
                        </TableCell>
                    </TableRow>
                ))}
            </TableBody>
            <TableFooter>
                <TableRow>
                    <TablePaginationFooter
                        colSpan={7}
                        startItem={startItem}
                        endItem={endItem}
                        totalCount={totalCount}
                        perPage={perPage}
                        onPerPageChange={handlePerPageChange}
                        currentPage={currentPage}
                        totalPages={perPage >= totalCount ? 1 : Math.max(1, Math.ceil(totalCount / perPage))}
                        onPageChange={setCurrentPage}
                    />
                </TableRow>
            </TableFooter>
        </Table>
    );
};
