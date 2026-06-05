import { cn } from "@/Core/Utils/utils";
import { type HTMLAttributes } from "react";

interface SkeletonProps extends HTMLAttributes<HTMLDivElement> {
    rounded?: "sm" | "md" | "lg" | "xl" | "full";
}

const roundedClasses = {
    sm: "rounded",
    md: "rounded-lg",
    lg: "rounded-xl",
    xl: "rounded-2xl",
    full: "rounded-full",
};

export default function Skeleton({ rounded = "md", className, ...props }: SkeletonProps) {
    return (
        <div
            className={cn("animate-pulse bg-slate-200", roundedClasses[rounded], className)}
            {...props}
        />
    );
}
