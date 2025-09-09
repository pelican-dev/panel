"use client";

interface PageHeaderProps {
  title: string;
  right?: React.ReactNode;
}

export function PageHeader({ title, right }: PageHeaderProps) {
  return (
    <div className="mx-auto max-w-[1472px] px-3 mb-4 flex items-center justify-between">
      <h1 className="text-3xl font-bold">{title}</h1>
      {right ? <div className="flex items-center gap-2">{right}</div> : null}
    </div>
  );
}
