"use client";

import * as React from "react";
import { cn } from "@/lib/utils";
import { IconCircleCheck, IconAlertTriangle, IconInfoCircle, IconX, IconCircleX } from "@tabler/icons-react";

export type AlertStatus = "success" | "warning" | "danger" | "info";

export type AlertBannerProps = {
  id: string;
  title?: string | null;
  body?: string | null;
  status?: AlertStatus | null;
  icon?: React.ReactNode;
  closable?: boolean;
  onClose?: (id: string) => void;
  className?: string;
};

function statusColor(status?: AlertStatus | null) {
  switch (status) {
    case "success":
      return {
        icon: "text-[rgb(var(--success-500))]",
        ring: "ring-[rgb(var(--success-500))]/30",
      };
    case "warning":
      return {
        icon: "text-[rgb(var(--warning-500))]",
        ring: "ring-[rgb(var(--warning-500))]/30",
      };
    case "danger":
      return {
        icon: "text-[rgb(var(--danger-500))]",
        ring: "ring-[rgb(var(--danger-500))]/30",
      };
    default:
      return {
        icon: "text-[rgb(var(--info-500))]",
        ring: "ring-[rgb(var(--info-500))]/30",
      };
  }
}

function defaultIcon(status?: AlertStatus | null) {
  switch (status) {
    case "success":
      return <IconCircleCheck className="h-5 w-5" />;
    case "warning":
      return <IconAlertTriangle className="h-5 w-5" />;
    case "danger":
      return <IconCircleX className="h-5 w-5" />;
    default:
      return <IconInfoCircle className="h-5 w-5" />;
  }
}

export const AlertBanner: React.FC<AlertBannerProps> = ({
  id,
  title,
  body,
  status = "info",
  icon,
  closable = false,
  onClose,
  className,
}) => {
  const colors = statusColor(status);

  return (
    <div
      className={cn(
        "relative w-full rounded-md border bg-card/80 ring-1 flex items-start gap-3 p-3",
        colors.ring,
        className,
      )}
      role="status"
      aria-live="polite"
    >
      <div className={cn("mt-0.5", colors.icon)}>
        {icon ?? defaultIcon(status)}
      </div>
      <div className="flex-1">
        {title && <div className="font-medium">{title}</div>}
        {body && <div className="text-sm text-muted-foreground">{body}</div>}
      </div>
      {closable && (
        <button
          onClick={() => onClose?.(id)}
          aria-label="Dismiss alert"
          className="p-1 rounded hover:bg-accent text-muted-foreground"
        >
          <IconX className="h-4 w-4" />
        </button>
      )}
    </div>
  );
};
