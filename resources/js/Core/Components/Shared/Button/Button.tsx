import { cn } from "@/Core/Utils/utils";
import { type ButtonHTMLAttributes } from "react";

type ButtonVariant = "primary" | "ghost" | "glow" | "outline" | "icon";
type ButtonSize = "sm" | "md" | "lg";

interface ButtonProps extends ButtonHTMLAttributes<HTMLButtonElement> {
    variant?: ButtonVariant;
    size?: ButtonSize;
    loading?: boolean;
}

const variantClasses: Record<ButtonVariant, string> = {
    primary:
        "bg-secondary-500 hover:bg-secondary-600 text-white shadow-md shadow-secondary-500/30 hover:shadow-secondary-500/50",
    ghost:
        "bg-transparent hover:bg-slate-100 text-slate-700 hover:text-slate-900",
    glow:
        "bg-gradient-to-b from-secondary-400/30 to-secondary-500/60 border border-secondary-200/40 text-white backdrop-blur-sm hover:border-secondary-300/60",
    outline:
        "border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 hover:border-slate-300",
    icon: "bg-transparent hover:bg-slate-100 text-slate-600 hover:text-slate-900 p-0 aspect-square",
};

const sizeClasses: Record<ButtonSize, string> = {
    sm: "px-3 py-1.5 text-xs font-semibold rounded-lg",
    md: "px-5 py-2.5 text-sm font-semibold rounded-xl",
    lg: "px-8 py-3 text-sm font-semibold rounded-full",
};

const glowStyle = {
    boxShadow:
        "0 0 20px rgba(34,211,238,0.4), 0 0 40px rgba(34,211,238,0.3), inset 0 1px 0 rgba(255,255,255,0.3)",
};

export default function Button({
    variant = "primary",
    size = "md",
    loading = false,
    disabled,
    className,
    children,
    style,
    ...props
}: ButtonProps) {
    const isGlow = variant === "glow";
    const iconSize = variant === "icon" ? "w-10 h-10 flex items-center justify-center rounded-full" : "";

    return (
        <button
            disabled={disabled || loading}
            className={cn(
                "inline-flex items-center justify-center gap-2 transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary-500/50 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 font-body",
                variantClasses[variant],
                variant !== "icon" && sizeClasses[size],
                iconSize,
                className,
            )}
            style={isGlow ? { ...glowStyle, ...style } : style}
            {...props}
        >
            {loading ? (
                <span className="h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent" />
            ) : null}
            {children}
        </button>
    );
}
