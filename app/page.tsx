import { HeaderActions } from "@/components/HeaderActions";
import { ServerTabs } from "@/components/ServerTabs";

export default function Home() {
  return (
    <main className="flex min-h-screen flex-col items-center p-12 md:p-24">
      <div className="w-full max-w-7xl">
        <div className="flex justify-between items-center mb-6">
          <h1 className="text-3xl font-bold">Servers</h1>
          <HeaderActions />
        </div>
        <ServerTabs />
      </div>
    </main>
  );
}
