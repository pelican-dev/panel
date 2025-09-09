"use client";

import * as React from "react";
import { useRouter } from "next/navigation";
import { Input } from "@/components/ui/input";
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from "@/components/ui/tooltip";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Sheet, SheetContent, SheetTrigger, SheetHeader, SheetTitle, SheetClose } from "@/components/ui/sheet";
import { Separator } from "@/components/ui/separator";
import { Button } from "@/components/ui/button";
import { IconBell, IconSearch, IconX, IconCircleCheck } from "@tabler/icons-react";

export const HeaderActions = () => {
  const router = useRouter();
  const [q, setQ] = React.useState("");
  const [notifications, setNotifications] = React.useState<
    Array<{
      id: string;
      title: string;
      timeAgo: string;
      meta?: string;
      ctaLabel?: string;
      ctaHref?: string;
      read?: boolean;
      status?: "success" | "warning" | "error";
    }>
  >([
    {
      id: "1",
      title: "Server Installation completed",
      timeAgo: "4 minutes ago",
      meta: "Server Name: testest",
      ctaLabel: "Open Server",
      ctaHref: "/admin/servers/1",
      read: false,
      status: "success",
    },
  ]);

  const onSearch = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const query = q.trim();
    if (!query) return;
    router.push(`/admin/search?q=${encodeURIComponent(query)}`);
  };

  return (
    <div className="flex items-center gap-2">
      {/* Global search */}
      <form onSubmit={onSearch} className="hidden md:flex items-center gap-2">
        <div className="relative">
          <IconSearch className="pointer-events-none absolute left-2 top-1/2 -translate-y-1/2 h-6 w-6 text-muted-foreground" />
          <Input
            value={q}
            onChange={(e) => setQ(e.target.value)}
            placeholder="Search..."
            className="pl-10 w-64"
          />
        </div>
      </form>

      {/* Notifications */}
      <TooltipProvider>
        <Sheet>
          <Tooltip>
            <TooltipTrigger asChild>
              <SheetTrigger asChild>
                <Button variant="ghost" size="icon" className="h-9 w-9 relative">
                  <IconBell className="h-6 w-6 text-[rgb(var(--gray-500))]" />
                  {notifications.some((n) => !n.read) && (
                    <span className="absolute top-1 right-1 h-2.5 w-2.5 rounded-full bg-[rgb(var(--primary-500))] ring-2 ring-background" />
                  )}
                </Button>
              </SheetTrigger>
            </TooltipTrigger>
            <TooltipContent>Notifications</TooltipContent>
          </Tooltip>
          <SheetContent side="right" className="p-0">
            <SheetHeader className="px-6 pt-6 pb-3">
              <div className="flex items-center">
                <SheetTitle className="flex items-center gap-2">
                  Notifications
                  <span className="ml-1 rounded-md border px-2 py-0.5 text-xs text-[rgb(var(--primary-400))] border-[rgb(var(--primary-400))]/30">
                    {notifications.filter((n) => !n.read).length}
                  </span>
                </SheetTitle>
                <div className="ml-auto">
                  <SheetClose asChild>
                    <button aria-label="Close" className="p-1 rounded hover:bg-accent">
                      <IconX className="h-4 w-4" />
                    </button>
                  </SheetClose>
                </div>
              </div>
              <div className="mt-2 flex items-center gap-4 text-sm">
                <button
                  type="button"
                  className="text-[rgb(var(--primary-400))] hover:underline font-semibold"
                  onClick={() => setNotifications((prev) => prev.map((n) => ({ ...n, read: true })))}
                >
                  Mark all as read
                </button>
                <button
                  type="button"
                  className="text-[rgb(var(--danger-400))] hover:underline font-semibold"
                  onClick={() => setNotifications([])}
                >
                  Clear
                </button>
              </div>
            </SheetHeader>
            <Separator className="mb-0" />
            <div className="space-y-0">
              {notifications.length === 0 ? (
                <div className="rounded-lg border p-3 text-sm">No notifications</div>
              ) : (
                notifications.map((n) => {
                  const borderColor = n.status === 'success'
                    ? 'border-l-[rgb(var(--success-500))]'
                    : n.status === 'warning'
                    ? 'border-l-[rgb(var(--warning-500))]'
                    : n.status === 'error'
                    ? 'border-l-[rgb(var(--danger-500))]'
                    : 'border-l-[rgb(var(--primary-500))]';
                  return (
                  <div
                    key={n.id}
                    className={`relative w-full rounded-none border-b last:border-b-0 border-l-2 ${borderColor} border-white/10 first:border-t-0 bg-transparent p-3 text-sm shadow-sm`}
                  >
                    <div className="flex items-start gap-3">
                      <div className="mt-0.5">
                        {n.status === 'success' ? (
                          <IconCircleCheck className="h-6 w-6 text-[rgb(var(--success-500))]" />
                        ) : null}
                      </div>
                      <div className="flex-1">
                        <div className="flex items-start justify-between">
                          <p className="font-medium">{n.title}</p>
                          <button
                            type="button"
                            onClick={() => setNotifications((prev) => prev.filter((x) => x.id !== n.id))}
                            className="text-muted-foreground hover:text-foreground"
                            aria-label="Dismiss notification"
                          >
                            <IconX className="h-4 w-4" />
                          </button>
                        </div>
                        <p className="mt-1 text-xs text-muted-foreground">{n.timeAgo}</p>
                        {n.meta && <p className="mt-1 text-xs text-muted-foreground">{n.meta}</p>}
                        {n.ctaHref && n.ctaLabel && (
                          <Button size="sm" className="mt-3 font-semibold" asChild>
                            <a href={n.ctaHref}>{n.ctaLabel}</a>
                          </Button>
                        )}
                      </div>
                    </div>
                  </div>
                )})
              )}
            </div>
          </SheetContent>
        </Sheet>
      </TooltipProvider>

      {/* User avatar */}
      <TooltipProvider>
        <Tooltip>
          <TooltipTrigger asChild>
            <button className="h-8 w-8 rounded-full overflow-hidden border border-border">
              <Avatar className="h-8 w-8">
                <AvatarImage src="" alt="User" />
                <AvatarFallback>U</AvatarFallback>
              </Avatar>
            </button>
          </TooltipTrigger>
          <TooltipContent>Account</TooltipContent>
        </Tooltip>
      </TooltipProvider>
    </div>
  );
};
