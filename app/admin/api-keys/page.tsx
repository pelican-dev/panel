import { ApiKeysDataTable } from "@/components/admin/ApiKeysDataTable";

export default function AdminApiKeys() {
  return (
    <div>
      <h1 className="text-3xl font-bold mb-6">API Keys</h1>
      <ApiKeysDataTable />
    </div>
  );
}
