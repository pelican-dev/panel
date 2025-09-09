import { Badge } from "@/components/ui/badge";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { ServerCard } from "@/components/ServerCard";

export const ServerTabs = () => {
  const serverCounts = {
    my: 5,
    other: 2,
    all: 7,
  };

  return (
    <Tabs defaultValue="my" className="w-full">
      <TabsList className="grid w-full grid-cols-3 md:w-auto md:inline-flex mb-4">
        <TabsTrigger value="my" className="flex items-center gap-2">
          My Servers <Badge variant="secondary">{serverCounts.my}</Badge>
        </TabsTrigger>
        <TabsTrigger value="other" className="flex items-center gap-2">
          Other Servers <Badge variant="secondary">{serverCounts.other}</Badge>
        </TabsTrigger>
        <TabsTrigger value="all" className="flex items-center gap-2">
          All Servers <Badge variant="secondary">{serverCounts.all}</Badge>
        </TabsTrigger>
      </TabsList>
      <TabsContent value="my">
        {/* For now, we'll just show the same card. Later, this will be dynamic. */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <ServerCard />
            <ServerCard />
            <ServerCard />
        </div>
      </TabsContent>
      <TabsContent value="other">
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <ServerCard />
        </div>
      </TabsContent>
      <TabsContent value="all">
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <ServerCard />
            <ServerCard />
            <ServerCard />
            <ServerCard />
            <ServerCard />
        </div>
      </TabsContent>
    </Tabs>
  );
};
