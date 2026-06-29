import { createPortal } from "react-dom";
import { cn } from "@/Core/Utils/utils";
import { useEscapeKey } from "@/Core/Hooks/useEscapeKey";
import { useScrollLock } from "@/Core/Hooks/useScrollLock";
import { type ReactNode } from "react";

interface ModalProps {
    open: boolean;
    onClose: () => void;
    children: ReactNode;
    className?: string;
}

export default function Modal({ open, onClose, children, className }: ModalProps) {
    useEscapeKey(onClose, open);
    useScrollLock(open);

    return createPortal(
        <>
            {/* Backdrop — z-[200] clears sticky navbar and all nav chrome */}
            <div
                aria-hidden="true"
                onClick={onClose}
                className={cn(
                    "fixed inset-0 z-[200] transition-opacity duration-300",
                    "bg-slate-900/60 backdrop-blur-sm",
                    open ? "opacity-100 pointer-events-auto" : "opacity-0 pointer-events-none",
                )}
            />

            {/* Centered panel */}
            <div
                role="dialog"
                aria-modal="true"
                className={cn(
                    "fixed inset-0 z-[201] flex items-center justify-center p-4",
                    "pointer-events-none",
                    open ? "pointer-events-auto" : "",
                )}
            >
                <div
                    className={cn(
                        "relative w-full max-w-sm bg-white rounded-2xl shadow-xl border border-slate-100",
                        "transition-all duration-300 ease-out",
                        open
                            ? "opacity-100 scale-100 translate-y-0"
                            : "opacity-0 scale-95 translate-y-2 pointer-events-none",
                        className,
                    )}
                >
                    {children}
                </div>
            </div>
        </>,
        document.body,
    );
}
