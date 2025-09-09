import { ServersDataTable } from "@/components/admin/ServersDataTable";
import { PageHeader } from "@/components/layout/PageHeader";

export default function AdminServers() {
  return (
    <div>
      <PageHeader title="Servers" />
      <ServersDataTable />
    </div>
  );
}
