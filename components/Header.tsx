"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { HeaderActions } from "@/components/HeaderActions";
import { IconChevronRight } from "@tabler/icons-react";

function getSegments(pathname: string) {
  const segments = pathname.split("/").filter(Boolean);
  // Remove panel slug like "admin" from breadcrumb visual
  return segments;
}

export const Header = () => {
  const pathname = usePathname() || "/";
  const segments = getSegments(pathname);

  return (
    <header className="sticky top-0 z-40 flex h-16 items-center gap-4 border-b bg-card/80 backdrop-blur supports-[backdrop-filter]:bg-card/60 px-4">
      <div className="flex flex-1 items-center gap-4">
        {/* Hamburger for small screens */}
        <button
          type="button"
          aria-label="Toggle sidebar"
          className="md:hidden fi-icon-btn h-9 w-9 grid place-items-center rounded-md text-gray-500 hover:text-gray-400 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-white/5"
          onClick={() => {
            window.dispatchEvent(new CustomEvent('toggle-sidebar'));
          }}
        >
          <svg className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fillRule="evenodd" d="M3 5.75A.75.75 0 0 1 3.75 5h12.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 5.75Zm0 4.25c0-.414.336-.75.75-.75h12.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 10Zm.75 3.5a.75.75 0 0 0 0 1.5h12.5a.75.75 0 0 0 0-1.5H3.75Z" clipRule="evenodd" />
          </svg>
        </button>
      </div>
      <div className="flex items-center gap-2">
        <HeaderActions />
      </div>
    </header>
  );
};
