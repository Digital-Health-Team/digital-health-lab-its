import { useEffect } from "react";

export function useScrollLock(locked: boolean): void {
    useEffect(() => {
        if (!locked) return;
        const scrollbarWidth =
            window.innerWidth - document.documentElement.clientWidth;
        document.documentElement.style.overflow = "hidden";
        document.documentElement.style.paddingRight = `${scrollbarWidth}px`;
        return () => {
            document.documentElement.style.overflow = "";
            document.documentElement.style.paddingRight = "";
        };
    }, [locked]);
}
