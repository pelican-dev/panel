"use client";

import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Button } from "@/components/ui/button";

interface TablePaginationFooterProps {
  startItem: number;
  endItem: number;
  totalCount: number;
  perPage: number;
  onPerPageChange: (value: string) => void;
  colSpan: number;
  currentPage: number;
  totalPages: number;
  onPageChange: (page: number) => void;
}

export function TablePaginationFooter({ startItem, endItem, totalCount, perPage, onPerPageChange, colSpan, currentPage, totalPages, onPageChange }: TablePaginationFooterProps) {
  return (
    <td colSpan={colSpan}>
      <div className="grid grid-cols-3 items-center px-6 py-3">
        <div className="text-sm text-muted-foreground justify-self-start">
          Showing {startItem} to {endItem} of {totalCount} results
        </div>
        <div className="justify-self-center">
          <div className="inline-flex items-center overflow-hidden rounded-md border border-gray-200 bg-muted/50 dark:border-white/10 dark:bg-white/5:is(.dark *)">
            <div className="flex items-center gap-x-3 ps-3 pe-3 border-e border-gray-200 dark:border-white/10">
              <span className="whitespace-nowrap text-sm text-muted-foreground">Per page</span>
            </div>
            <div className="min-w-0 flex-1">
              <Select value={perPage >= totalCount ? 'all' : perPage.toString()} onValueChange={onPerPageChange}>
                <SelectTrigger className="w-24 h-9 rounded-none border-0 bg-transparent shadow-none px-3 focus:ring-0 focus:outline-none">
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="5">5</SelectItem>
                  <SelectItem value="10">10</SelectItem>
                  <SelectItem value="25">25</SelectItem>
                  <SelectItem value="50">50</SelectItem>
                  <SelectItem value="all">All</SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>
        </div>
        <div className="justify-self-end">
          {totalPages > 1 ? (
            <div className="flex items-center gap-2">
              <Button
                variant="outline"
                size="sm"
                onClick={() => onPageChange(Math.max(1, currentPage - 1))}
                disabled={currentPage <= 1}
              >
                Prev
              </Button>
              <span className="text-sm text-muted-foreground">
                Page {currentPage} of {Math.max(1, totalPages)}
              </span>
              <Button
                variant="outline"
                size="sm"
                onClick={() => onPageChange(Math.min(totalPages, currentPage + 1))}
                disabled={currentPage >= totalPages}
              >
                Next
              </Button>
            </div>
          ) : null}
        </div>
      </div>
    </td>
  );
}
