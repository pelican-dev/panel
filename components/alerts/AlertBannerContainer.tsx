"use client";

import * as React from "react";
import { AlertBanner, type AlertStatus } from "@/components/alerts/AlertBanner";

export type AlertBannerItem = {
  id: string;
  title?: string | null;
  body?: string | null;
  status?: AlertStatus | null;
  icon?: React.ReactNode;
  closable?: boolean;
};

export type AlertBannerContainerProps = {
  initial?: AlertBannerItem[];
};

declare global {
  interface Window {
    __alertBannerSend?: (item: AlertBannerItem) => void;
  }
}

export const AlertBannerContainer: React.FC<AlertBannerContainerProps> = ({ initial = [] }) => {
  const [items, setItems] = React.useState<AlertBannerItem[]>(initial);

  const remove = (id: string) => setItems((prev) => prev.filter((i) => i.id !== id));

  // Public API via window for quick testing/parity with Livewire "send()"
  React.useEffect(() => {
    window.__alertBannerSend = (item: AlertBannerItem) =>
      setItems((prev) => {
        const next = prev.filter((i) => i.id !== item.id);
        next.push(item);
        return next;
      });
  }, []);

  if (items.length === 0) return null;

  return (
    <div className="pointer-events-none fixed inset-x-4 top-20 z-40 space-y-2 md:inset-x-6">
      {items.map((i) => (
        <div key={i.id} className="pointer-events-auto">
          <AlertBanner
            id={i.id}
            title={i.title ?? undefined}
            body={i.body ?? undefined}
            status={i.status ?? undefined}
            icon={i.icon}
            closable={i.closable ?? true}
            onClose={remove}
          />
        </div>
      ))}
    </div>
  );
};
