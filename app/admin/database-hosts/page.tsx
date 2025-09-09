import { DatabaseHostsDataTable } from "@/components/admin/DatabaseHostsDataTable";

export default function AdminDatabaseHosts() {
  return (
    <div>
      <h1 className="text-3xl font-bold mb-6">Database Hosts</h1>
      <DatabaseHostsDataTable />
    </div>
  );
}
