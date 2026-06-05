import { cn } from "@/Core/Utils/utils";
import { type InputHTMLAttributes, type ReactNode } from "react";

interface InputProps extends InputHTMLAttributes<HTMLInputElement> {
    leftIcon?: ReactNode;
    rightIcon?: ReactNode;
    wrapperClassName?: string;
}

export function Input({ leftIcon, rightIcon, wrapperClassName, className, ...props }: InputProps) {
    return (
        <div className={cn("relative flex items-center", wrapperClassName)}>
            {leftIcon && (
                <span className="pointer-events-none absolute left-3 flex items-center text-slate-400">
                    {leftIcon}
                </span>
            )}
            <input
                className={cn(
                    "w-full rounded-full bg-slate-100 text-sm text-slate-800 placeholder:text-slate-400",
                    "border border-transparent transition-all duration-200",
                    "focus:bg-white focus:border-secondary-500 focus:outline-none",
                    "focus:ring-2 focus:ring-secondary-500/20",
                    "hover:bg-slate-50",
                    leftIcon ? "pl-10" : "pl-4",
                    rightIcon ? "pr-10" : "pr-4",
                    "py-2.5 h-10",
                    className,
                )}
                {...props}
            />
            {rightIcon && (
                <span className="absolute right-3 flex items-center text-slate-400">
                    {rightIcon}
                </span>
            )}
        </div>
    );
}
