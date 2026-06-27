import { useState, useEffect, useCallback } from "react";
import {
    prefersReducedMotion,
    getCurtainCount,
} from "@/Features/Landing/Utils/motionPreferences";

/**
 * Module-scoped flag — true after the first hard load. Resets to false on every
 * hard refresh (module re-evaluation), but survives client-side Inertia navigation
 * so the preloader is skipped when the user returns to /pameran within the same SPA
 * session.
 */
let hasPlayed = false;

export function usePameranPreloader() {
    /**
     * Lazy initializer runs once per component mount. On the first mount after a
     * hard load, hasPlayed is false → shouldPlay is true → we mark it played and
     * show the loader. On any subsequent SPA mount, hasPlayed is already true →
     * isMounted starts false → component returns null immediately.
     */
    const [isMounted, setIsMounted] = useState<boolean>(() => {
        const shouldPlay = !hasPlayed;
        hasPlayed = true;
        return shouldPlay;
    });

    /** Captured once on mount; SSR-safe via lazy init. */
    const [reducedMotion] = useState<boolean>(() => prefersReducedMotion());

    /** 6 on mobile, 9 on desktop — matches the Core/Landing preloader pattern. */
    const [numCurtains] = useState<number>(getCurtainCount);

    /** Lock body scroll while the preloader is visible. */
    useEffect(() => {
        if (!isMounted) return;
        const prev = document.body.style.overflow;
        document.body.style.overflow = "hidden";
        return () => {
            document.body.style.overflow = prev;
        };
    }, [isMounted]);

    /** Called by the animation hook's onComplete — unmounts the preloader. */
    const handleAnimationComplete = useCallback(() => {
        setIsMounted(false);
    }, []);

    return {
        isMounted,
        prefersReducedMotion: reducedMotion,
        numCurtains,
        handleAnimationComplete,
    };
}
