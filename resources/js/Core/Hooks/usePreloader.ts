import { useState, useEffect, useCallback } from "react";

function getCurtainCount(): number {
    return typeof window !== "undefined" && window.innerWidth < 768 ? 6 : 9;
}

export function usePreloader() {
    const [isMounted, setIsMounted] = useState(true);
    const [numCurtains] = useState(getCurtainCount);

    useEffect(() => {
        if (!isMounted) return;
        const prev = document.body.style.overflow;
        document.body.style.overflow = "hidden";
        return () => {
            document.body.style.overflow = prev;
        };
    }, [isMounted]);

    const handleAnimationComplete = useCallback(() => {
        setIsMounted(false);
    }, []);

    return { isMounted, numCurtains, handleAnimationComplete };
}
