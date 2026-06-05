import { cn } from "@/Core/Utils/utils";
import { useEscapeKey } from "@/Core/Hooks/useEscapeKey";
import { useScrollLock } from "@/Core/Hooks/useScrollLock";
import { type ReactNode } from "react";

interface SheetProps {
    open: boolean;
    onClose: () => void;
    children: ReactNode;
    className?: string;
}

export default function Sheet({ open, onClose, children, className }: SheetProps) {
    useEscapeKey(onClose, open);
    useScrollLock(open);

    return (
        <>
            {/* Backdrop */}
            <div
                aria-hidden="true"
                onClick={onClose}
                className={cn(
                    "fixed inset-0 z-40 transition-opacity duration-300",
                    "bg-primary-950/80 backdrop-blur-sm",
                    open ? "opacity-100 pointer-events-auto" : "opacity-0 pointer-events-none",
                )}
            />
            {/* Drawer */}
            <div
                role="dialog"
                aria-modal="true"
                className={cn(
                    "fixed inset-y-0 left-0 z-50 w-60 bg-primary-950 shadow-2xl",
                    "transition-transform duration-300 ease-in-out",
                    open ? "translate-x-0" : "-translate-x-full",
                    className,
                )}
            >
                {children}
            </div>
        </>
    );
}
