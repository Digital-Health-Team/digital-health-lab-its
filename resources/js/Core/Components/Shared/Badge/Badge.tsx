import { cn } from "@/Core/Utils/utils";
import { type HTMLAttributes } from "react";

type BadgeVariant = "verified" | "pending" | "rejected" | "neutral" | "tag" | "new" | "sale";

interface BadgeProps extends HTMLAttributes<HTMLSpanElement> {
    variant?: BadgeVariant;
}

const variantClasses: Record<BadgeVariant, string> = {
    verified: "bg-emerald-50 text-emerald-700 border-emerald-200",
    pending: "bg-accent-300/20 text-yellow-700 border-accent-300/40",
    rejected: "bg-red-50 text-red-700 border-red-200",
    neutral: "bg-slate-100 text-slate-600 border-slate-200",
    tag: "bg-secondary-500/10 text-secondary-700 border-secondary-200/60",
    new: "bg-primary-700 text-white border-primary-700",
    sale: "bg-red-500 text-white border-red-500",
};

export default function Badge({ variant = "neutral", className, children, ...props }: BadgeProps) {
    return (
        <span
            className={cn(
                "inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold border tracking-wide uppercase",
                variantClasses[variant],
                className,
            )}
            {...props}
        >
            {children}
        </span>
    );
}
