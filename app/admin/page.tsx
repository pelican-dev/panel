import { CanaryWidget } from "@/components/admin/CanaryWidget";
import { UpdateWidget } from "@/components/admin/UpdateWidget";

export default function AdminDashboard() {
  const currentVersion = "v1.0.0"; // Placeholder

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-3xl font-bold">Dashboard</h1>
        <p className="text-muted-foreground">Version: {currentVersion}</p>
      </div>
      <div className="space-y-4">
        <UpdateWidget />
        <CanaryWidget />
      </div>
    </div>
  );
}
