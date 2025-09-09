'use client';

import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, TableFooter } from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { IconLoader2, IconBrandDocker, IconEgg } from "@tabler/icons-react";
import { useEffect, useState } from "react";
import { AllocationSelect } from "@/components/tables/AllocationSelect";
import { ActionsCell } from "@/components/tables/ActionsCell";
import { TablePaginationFooter } from "@/components/tables/TablePaginationFooter";

interface Server {
    id: number;
    external_id: string | null;
    uuid: string;
    identifier: string;
    name: string;
    description: string;
    status: string;
    suspended: boolean;
    limits: {
        memory: number;
        swap: number;
        disk: number;
        io: number;
        cpu: number;
        threads: string;
        oom_disabled: boolean;
        oom_killer: boolean;
    };
    feature_limits: {
        databases: number;
        allocations: number;
        backups: number;
    };
    user: number;
    node: number;
    allocation: number;
    allocations?: {
        id: number;
        ip: string;  
        port: number;
        alias?: string;
        is_default: boolean;
    }[];
    egg: number;
    container: {
        startup_command: string;
        image: string;
        installed: number;
        environment: Record<string, string>;
    };
    updated_at: string;
    created_at: string;
}

interface ApiResponse {
    object: string;
    data: {
        object: string;
        attributes: Server;
    }[];
    meta: {
        pagination: {
            total: number;
            count: number;
            per_page: number;
            current_page: number;
            total_pages: number;
            links: Record<string, unknown>;
        };
    };
}

const getConditionVariant = (status: string) => {
    switch (status) {
        case 'running':
            return 'success';
        case 'stopped':
            return 'destructive';
        case 'starting':
        case 'installing':
            return 'secondary';
        default:
            return 'outline';
    }
};

export const ServersDataTable = () => {
    const [servers, setServers] = useState<Server[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const [currentPage, setCurrentPage] = useState(1);
    const [perPage, setPerPage] = useState(10);
    const [totalCount, setTotalCount] = useState(0);

    useEffect(() => {
        fetchServers();
    }, []);

    const fetchServers = async () => {
        try {
            setLoading(true);
            const response = await fetch('/api/application/servers');
            
            if (!response.ok) {
                throw new Error('Failed to fetch servers');
            }

            const data: ApiResponse = await response.json();
            setServers(data.data.map(item => item.attributes));
            setTotalCount(data.meta?.pagination?.total || data.data.length);
        } catch (err) {
            setError(err instanceof Error ? err.message : 'An error occurred');
        } finally {
            setLoading(false);
        }
    };

    const handleAllocationChange = async (serverUuid: string, allocationId: number) => {
        try {
            // TODO: Implement API call to update server allocation
            console.log(`Changing allocation for server ${serverUuid} to ${allocationId}`);
            
            // Update local state optimistically
            setServers(prevServers => 
                prevServers.map(server => 
                    server.uuid === serverUuid 
                        ? { ...server, allocation: allocationId }
                        : server
                )
            );
        } catch (err) {
            console.error('Failed to update allocation:', err);
            // TODO: Show error toast
        }
    };

    const handlePerPageChange = (value: string) => {
        setPerPage(value === 'all' ? totalCount : parseInt(value));
        setCurrentPage(1);
    };

    // Calculate pagination info
    const displayedServers = perPage >= totalCount ? servers : servers.slice((currentPage - 1) * perPage, currentPage * perPage);
    const startItem = Math.min((currentPage - 1) * perPage + 1, totalCount);
    const endItem = Math.min(currentPage * perPage, totalCount);
    const totalPages = perPage >= totalCount ? 1 : Math.max(1, Math.ceil(totalCount / perPage));

    if (loading) {
        return (
            <div className="flex items-center justify-center p-8">
                <IconLoader2 className="h-6 w-6 animate-spin" />
                <span className="ml-2">Loading servers...</span>
            </div>
        );
    }

    if (error) {
        return (
            <div className="p-8 text-center">
                <p className="text-red-500">Error: {error}</p>
                <Button onClick={fetchServers} className="mt-4">Retry</Button>
            </div>
        );
    }

    return (
        <div className="space-y-4">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Condition</TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Egg</TableHead>
                        <TableHead>Username</TableHead>
                        <TableHead>Primary Allocation</TableHead>
                        <TableHead>Backups</TableHead>
                        <TableHead className="text-right"><span className="sr-only">Actions</span></TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {displayedServers.map((server) => (
                    <TableRow key={server.uuid} className="h-[69px]">
                        <TableCell>
                            <Badge variant={getConditionVariant(server.status)} className="capitalize">
                                {server.status}
                            </Badge>
                        </TableCell>
                        <TableCell>
                            <div className="flex items-center gap-2">
                                <IconBrandDocker className="h-4 w-4 text-blue-500" />
                                <div className="font-medium">{server.name}</div>
                            </div>
                        </TableCell>
                        <TableCell>
                            <div className="flex items-center gap-2">
                                <IconEgg className="h-4 w-4 text-orange-500" />
                                <span>Egg {server.egg}</span>
                            </div>
                        </TableCell>
                        <TableCell>User {server.user}</TableCell>
                        <TableCell>
                            <AllocationSelect
                                value={server.allocation}
                                options={(server.allocations ?? []).map(a => ({ id: a.id, ip: a.ip, port: a.port }))}
                                onChange={(id) => handleAllocationChange(server.uuid, id)}
                            />
                        </TableCell>
                        <TableCell>{server.feature_limits.backups}</TableCell>
                        <TableCell className="text-right">
                            <ActionsCell viewHref={`/admin/servers/${server.id}`} editHref={`/admin/servers/${server.id}/edit`} />
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
                            totalPages={totalPages}
                            onPageChange={setCurrentPage}
                        />
                    </TableRow>
                </TableFooter>
            </Table>
        </div>
    );
};
