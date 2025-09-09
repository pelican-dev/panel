import { UsersDataTable } from "@/components/admin/UsersDataTable";
import { Button } from "@/components/ui/button";
import { IconPlus } from "@tabler/icons-react";
import Link from "next/link";

export default function AdminUsers() {
  return (
    <div>
      <div className="flex items-center justify-between mb-6">
        <h1 className="text-3xl font-bold">Users</h1>
        <Button asChild>
          <Link href="/admin/users/create" className="flex items-center gap-2">
            <IconPlus className="h-5 w-5" />
            Create User
          </Link>
        </Button>
      </div>
      <UsersDataTable />
    </div>
  );
}
