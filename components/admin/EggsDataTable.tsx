"use client";

import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, TableFooter } from "@/components/ui/table";
import { IconEgg, IconServer } from "@tabler/icons-react";
import { useEffect, useState } from "react";
import { ActionsCell } from "@/components/tables/ActionsCell";
import { TablePaginationFooter } from "@/components/tables/TablePaginationFooter";

type EggAttributes = {
  id: number | null;
  uuid: string | null;
  name: string;
  author: string | null;
  description: string | null;
  features: string[];
  docker_image?: string | null;
  docker_images?: Record<string, string>;
  startup?: string | null;
  created_at: string | null;
  updated_at: string | null;
};

interface ApiResponse<T> {
  object: string;
  data: { object: string; attributes: T }[];
  meta?: { pagination?: { total: number } };
}

export const EggsDataTable = () => {
  const [eggs, setEggs] = useState<EggAttributes[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [currentPage, setCurrentPage] = useState(1);
  const [perPage, setPerPage] = useState(10);
  const [totalCount, setTotalCount] = useState(0);

  useEffect(() => {
    fetchEggs();
  }, []);

  const fetchEggs = async () => {
    try {
      setLoading(true);
      const res = await fetch(`/api/application/eggs?per_page=100`, { cache: "no-store" });
      if (!res.ok) throw new Error("Failed to fetch eggs");
      const json: ApiResponse<EggAttributes> = await res.json();
      const items = (json?.data ?? []).map((i) => i.attributes);
      setEggs(items);
      setTotalCount(json?.meta?.pagination?.total ?? items.length);
    } catch (err) {
      setError(err instanceof Error ? err.message : "An error occurred");
    } finally {
      setLoading(false);
    }
  };

  const handlePerPageChange = (value: string) => {
    setPerPage(value === "all" ? totalCount : parseInt(value));
    setCurrentPage(1);
  };

  const displayed = perPage >= totalCount ? eggs : eggs.slice((currentPage - 1) * perPage, currentPage * perPage);
  const startItem = Math.min((currentPage - 1) * perPage + 1, totalCount);
  const endItem = Math.min(currentPage * perPage, totalCount);
  const totalPages = perPage >= totalCount ? 1 : Math.max(1, Math.ceil(totalCount / perPage));

  if (loading) {
    return (
      <div className="p-6 text-sm text-muted-foreground">Loading eggs…</div>
    );
  }

  if (error) {
    return (
      <div className="p-6 text-sm text-red-500">Error: {error}</div>
    );
  }

  return (
    <Table>
      <TableHeader>
        <TableRow>
          <TableHead>Name</TableHead>
          <TableHead>Servers</TableHead>
          <TableHead className="text-right"><span className="sr-only">Actions</span></TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        {displayed.map((egg) => (
          <TableRow key={egg.uuid ?? egg.id ?? egg.name}>
            <TableCell>
              <div className="flex items-center gap-3">
                <IconEgg className="h-5 w-5 shrink-0" />
                <div className="min-w-0">
                  <p className="font-semibold truncate">{egg.name}</p>
                  {egg.description && (
                    <p className="text-sm text-muted-foreground line-clamp-1">{egg.description}</p>
                  )}
                </div>
              </div>
            </TableCell>
            <TableCell>
              <div className="flex items-center gap-2">
                <IconServer className="h-5 w-5" />
                <span>0</span>
              </div>
            </TableCell>
            <TableCell className="text-right">
              <ActionsCell editHref={`/admin/eggs/${egg.uuid ?? egg.id ?? ""}/edit`} />
            </TableCell>
          </TableRow>
        ))}
      </TableBody>
      <TableFooter>
        <TableRow>
          <TablePaginationFooter
            colSpan={3}
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
  );
};
