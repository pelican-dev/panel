"use client";

import { IconChevronRight, IconPencil } from "@tabler/icons-react";

interface ActionsCellProps {
  viewHref?: string;
  editHref?: string;
  align?: "left" | "right";
}

export function ActionsCell({ viewHref, editHref, align = "right" }: ActionsCellProps) {
  return (
    <div className={`flex items-center ${align === "right" ? "justify-end" : "justify-start"} gap-4 text-sm`}>
      {viewHref ? (
        <a
          href={viewHref}
          className="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
        >
          <IconChevronRight className="h-4 w-4" />
          <span>View</span>
        </a>
      ) : null}
      {editHref ? (
        <a
          href={editHref}
          className="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
        >
          <IconPencil className="h-4 w-4" />
          <span>Edit</span>
        </a>
      ) : null}
    </div>
  );
}
