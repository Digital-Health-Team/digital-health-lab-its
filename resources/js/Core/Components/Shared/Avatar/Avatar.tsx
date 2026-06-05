import { cn } from "@/Core/Utils/utils";

type AvatarSize = "sm" | "md" | "lg" | "xl";

interface AvatarProps {
    src?: string;
    name?: string;
    size?: AvatarSize;
    statusDot?: boolean;
    className?: string;
}

const sizeClasses: Record<AvatarSize, string> = {
    sm: "h-7 w-7 text-[10px]",
    md: "h-8 w-8 text-xs",
    lg: "h-10 w-10 text-sm",
    xl: "h-12 w-12 text-base",
};

function initials(name: string): string {
    return name
        .trim()
        .split(/\s+/)
        .slice(0, 2)
        .map((w) => w[0]?.toUpperCase() ?? "")
        .join("");
}

export default function Avatar({ src, name = "", size = "md", statusDot = false, className }: AvatarProps) {
    return (
        <span className={cn("relative inline-flex shrink-0", className)}>
            {src ? (
                <img
                    src={src}
                    alt={name}
                    className={cn("rounded-full object-cover ring-2 ring-secondary-500/20", sizeClasses[size])}
                />
            ) : (
                <span
                    className={cn(
                        "rounded-full bg-gradient-to-br from-primary-700 to-secondary-500 text-white font-semibold flex items-center justify-center ring-2 ring-secondary-500/20",
                        sizeClasses[size],
                    )}
                >
                    {initials(name) || "?"}
                </span>
            )}
            {statusDot && (
                <span className="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full bg-emerald-500 ring-2 ring-white" />
            )}
        </span>
    );
}
