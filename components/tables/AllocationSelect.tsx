"use client";

import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";

export interface AllocationOption {
  id: number;
  ip: string;
  port: number;
}

interface AllocationSelectProps {
  value: number;
  options: AllocationOption[];
  onChange: (allocationId: number) => void;
  className?: string;
}

export function AllocationSelect({ value, options, onChange, className }: AllocationSelectProps) {
  return (
    <Select defaultValue={value.toString()} onValueChange={(v) => onChange(parseInt(v))}>
      <SelectTrigger className={className ?? "w-[314px] bg-muted/50 dark:bg-white/5:is(.dark *)"}>
        <SelectValue />
      </SelectTrigger>
      <SelectContent>
        {options?.map((a) => (
          <SelectItem key={a.id} value={a.id.toString()}>
            {a.ip}:{a.port}
          </SelectItem>
        ))}
      </SelectContent>
    </Select>
  );
}
