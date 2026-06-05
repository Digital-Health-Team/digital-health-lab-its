import { cn } from "@/Core/Utils/utils";
import {
    createContext,
    useCallback,
    useContext,
    useRef,
    useState,
    type HTMLAttributes,
    type MouseEvent,
    type ReactNode,
} from "react";
import { useEscapeKey } from "@/Core/Hooks/useEscapeKey";
import { useOutsideClick } from "@/Core/Hooks/useOutsideClick";

interface DropdownContextValue {
    open: boolean;
    setOpen: (open: boolean) => void;
}

const DropdownContext = createContext<DropdownContextValue>({
    open: false,
    setOpen: () => {},
});

interface DropdownMenuProps {
    children: ReactNode;
    className?: string;
}

export function DropdownMenu({ children, className }: DropdownMenuProps) {
    const [open, setOpen] = useState(false);
    const containerRef = useRef<HTMLDivElement>(null);

    const close = useCallback(() => setOpen(false), []);
    useOutsideClick(containerRef, close, open);
    useEscapeKey(close, open);

    return (
        <DropdownContext.Provider value={{ open, setOpen }}>
            <div ref={containerRef} className={cn("relative inline-flex", className)}>
                {children}
            </div>
        </DropdownContext.Provider>
    );
}

interface DropdownMenuTriggerProps {
    children: ReactNode;
    className?: string;
}

export function DropdownMenuTrigger({ children, className }: DropdownMenuTriggerProps) {
    const { open, setOpen } = useContext(DropdownContext);
    return (
        <button
            type="button"
            aria-haspopup="menu"
            aria-expanded={open}
            onClick={() => setOpen(!open)}
            className={cn(
                "focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary-500/50 rounded-xl",
                className,
            )}
        >
            {children}
        </button>
    );
}

interface DropdownMenuContentProps extends HTMLAttributes<HTMLDivElement> {
    align?: "left" | "right";
    width?: string;
}

export function DropdownMenuContent({
    align = "right",
    width = "w-56",
    className,
    children,
    ...props
}: DropdownMenuContentProps) {
    const { open } = useContext(DropdownContext);
    return (
        <div
            role="menu"
            aria-hidden={!open}
            className={cn(
                "absolute top-full z-50 mt-2 rounded-2xl bg-white border border-slate-100 py-1.5",
                "shadow-[0_12px_32px_-8px_rgba(3,16,38,0.18)]",
                "transition-all duration-150 origin-top-right",
                open
                    ? "opacity-100 scale-100 translate-y-0 pointer-events-auto"
                    : "opacity-0 scale-95 -translate-y-1 pointer-events-none",
                align === "right" ? "right-0" : "left-0",
                width,
                className,
            )}
            {...props}
        >
            {children}
        </div>
    );
}

interface DropdownMenuItemProps {
    destructive?: boolean;
    icon?: ReactNode;
    className?: string;
    children: ReactNode;
    onClick?: (e: MouseEvent<HTMLButtonElement>) => void;
}

export function DropdownMenuItem({
    destructive = false,
    icon,
    className,
    children,
    onClick,
}: DropdownMenuItemProps) {
    const { setOpen } = useContext(DropdownContext);
    return (
        <button
            type="button"
            role="menuitem"
            onClick={(e) => {
                onClick?.(e);
                setOpen(false);
            }}
            className={cn(
                "w-full flex items-center gap-2.5 px-3.5 py-2 text-sm transition-colors duration-100 text-left",
                destructive
                    ? "text-red-600 hover:bg-red-50"
                    : "text-slate-700 hover:bg-slate-50 hover:text-slate-900",
                className,
            )}
        >
            {icon && <span className="shrink-0 h-4 w-4 text-slate-400">{icon}</span>}
            {children}
        </button>
    );
}

export function DropdownMenuSeparator({ className }: { className?: string }) {
    return <div role="separator" className={cn("my-1.5 h-px bg-slate-100", className)} />;
}
