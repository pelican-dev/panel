import { WebhooksDataTable } from "@/components/admin/WebhooksDataTable";

export default function AdminWebhooks() {
  return (
    <div>
      <h1 className="text-3xl font-bold mb-6">Webhooks</h1>
      <WebhooksDataTable />
    </div>
  );
}
