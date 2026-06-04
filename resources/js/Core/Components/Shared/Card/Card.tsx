import { cn } from "@/Core/Utils/utils";
import { type HTMLAttributes } from "react";

type CardVariant = "default" | "hero";

interface CardProps extends HTMLAttributes<HTMLDivElement> {
    variant?: CardVariant;
}

const softShadow = {
    boxShadow: "0 10px 40px -10px rgba(3,16,38,0.12)",
};

export function Card({ variant = "default", className, style, children, ...props }: CardProps) {
    return (
        <div
            className={cn(
                "bg-white border border-slate-200",
                variant === "default" ? "rounded-2xl" : "rounded-3xl",
                className,
            )}
            style={{ ...softShadow, ...style }}
            {...props}
        >
            {children}
        </div>
    );
}

export function CardHeader({ className, children, ...props }: HTMLAttributes<HTMLDivElement>) {
    return (
        <div className={cn("px-6 pt-6 pb-4", className)} {...props}>
            {children}
        </div>
    );
}

export function CardTitle({ className, children, ...props }: HTMLAttributes<HTMLHeadingElement>) {
    return (
        <h3 className={cn("font-display text-xl font-bold text-slate-800", className)} {...props}>
            {children}
        </h3>
    );
}

export function CardBody({ className, children, ...props }: HTMLAttributes<HTMLDivElement>) {
    return (
        <div className={cn("px-6 py-4", className)} {...props}>
            {children}
        </div>
    );
}

export function CardFooter({ className, children, ...props }: HTMLAttributes<HTMLDivElement>) {
    return (
        <div className={cn("px-6 pt-4 pb-6", className)} {...props}>
            {children}
        </div>
    );
}
