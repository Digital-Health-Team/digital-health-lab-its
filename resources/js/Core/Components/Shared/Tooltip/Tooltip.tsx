import { cn } from "@/Core/Utils/utils";
import { type ReactNode } from "react";

interface TooltipProps {
    label: string;
    children: ReactNode;
    side?: "top" | "right" | "bottom" | "left";
    className?: string;
}

const sideClasses: Record<string, string> = {
    top: "bottom-full left-1/2 -translate-x-1/2 mb-2",
    right: "left-full top-1/2 -translate-y-1/2 ml-2",
    bottom: "top-full left-1/2 -translate-x-1/2 mt-2",
    left: "right-full top-1/2 -translate-y-1/2 mr-2",
};

export default function Tooltip({ label, children, side = "right", className }: TooltipProps) {
    return (
        <span className={cn("group relative inline-flex", className)}>
            {children}
            <span
                role="tooltip"
                className={cn(
                    "pointer-events-none absolute z-50 whitespace-nowrap rounded-lg bg-primary-950 px-2.5 py-1.5 text-xs font-medium text-white opacity-0 shadow-lg",
                    "transition-opacity duration-150 group-hover:opacity-100 group-focus-within:opacity-100",
                    sideClasses[side],
                )}
            >
                {label}
            </span>
        </span>
    );
}
