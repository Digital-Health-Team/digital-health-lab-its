import { useState, useEffect, useCallback } from "react";
import { getCurtainCount } from "../Utils/motionPreferences";

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
