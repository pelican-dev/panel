import { EggsDataTable } from "@/components/admin/EggsDataTable";
import { Button } from "@/components/ui/button";
import { IconPlus } from "@tabler/icons-react";
import Link from "next/link";
import { PageHeader } from "@/components/layout/PageHeader";

export default function AdminEggs() {
  return (
    <div className="mx-auto max-w-[1472px] px-3">
      <PageHeader
        title="Eggs"
        right={(
          <Button asChild>
            <Link href="/admin/eggs/create" className="flex items-center gap-2">
              <IconPlus className="h-5 w-5" />
              Create Egg
            </Link>
          </Button>
        )}
      />
      <EggsDataTable />
    </div>
  );
}
