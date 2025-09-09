import { NodesDataTable } from "@/components/admin/NodesDataTable";
import { Button } from "@/components/ui/button";
import { IconPlus } from "@tabler/icons-react";
import Link from "next/link";
import { PageHeader } from "@/components/layout/PageHeader";

export default function AdminNodes() {
  return (
    <div>
      <PageHeader
        title="Nodes"
        right={
          <Button asChild>
            <Link href="/admin/nodes/create" className="flex items-center gap-2">
              <IconPlus className="h-5 w-5" />
              Create Node
            </Link>
          </Button>
        }
      />
      <NodesDataTable />
    </div>
  );
}
