import { useEffect, useRef } from "react";
import { router } from "@inertiajs/react";
import gsap from "gsap";

interface UseNavigationProgressReturn {
    barRef: React.RefObject<HTMLDivElement | null>;
    wrapperRef: React.RefObject<HTMLDivElement | null>;
}

/**
 * Drives a thin top progress bar during Inertia router navigations.
 *
 * Lifecycle:
 *   start    → fade in, trickle to ~30 % then slow toward ~90 %
 *   progress → (upload progress) snap to actual percentage
 *   finish   → animate to 100 %, fade out, reset for next visit
 *
 * Respects prefers-reduced-motion: jumps to end states with no tween.
 */
export function useNavigationProgress(): UseNavigationProgressReturn {
    const barRef = useRef<HTMLDivElement | null>(null);
    const wrapperRef = useRef<HTMLDivElement | null>(null);
    // Keep a reference to the in-flight trickle tween so we can kill it on finish/start.
    const trickleRef = useRef<gsap.core.Tween | null>(null);

    useEffect(() => {
        const prefersReduced =
            typeof window !== "undefined" &&
            window.matchMedia("(prefers-reduced-motion: reduce)").matches;

        function reset() {
            trickleRef.current?.kill();
            trickleRef.current = null;
            if (!barRef.current || !wrapperRef.current) return;
            gsap.set(barRef.current, { scaleX: 0, transformOrigin: "left center" });
            gsap.set(wrapperRef.current, { opacity: 0 });
        }

        function onStart() {
            if (!barRef.current || !wrapperRef.current) return;

            // Kill any previous trickle in case a new navigation starts mid-flight.
            trickleRef.current?.kill();

            // Reset to origin.
            gsap.set(barRef.current, { scaleX: 0, transformOrigin: "left center" });

            if (prefersReduced) {
                gsap.set(wrapperRef.current, { opacity: 1 });
                gsap.set(barRef.current, { scaleX: 0.5 });
                return;
            }

            // Fade in the wrapper then trickle the bar.
            gsap.to(wrapperRef.current, {
                opacity: 1,
                duration: 0.15,
                ease: "none",
                onComplete: () => {
                    if (!barRef.current) return;
                    // Fast burst to 30 %.
                    gsap.to(barRef.current, {
                        scaleX: 0.3,
                        duration: 0.4,
                        ease: "power2.out",
                        onComplete: () => {
                            // Slow trickle toward 90 % (never reaches it automatically).
                            trickleRef.current = gsap.to(barRef.current, {
                                scaleX: 0.9,
                                duration: 8,
                                ease: "power1.out",
                            });
                        },
                    });
                },
            });
        }

        function onProgress(event: CustomEvent<{ progress?: { percentage?: number } }>) {
            const pct = event.detail?.progress?.percentage;
            if (pct == null || !barRef.current) return;

            trickleRef.current?.kill();
            const scale = Math.min(pct / 100, 0.95);

            if (prefersReduced) {
                gsap.set(barRef.current, { scaleX: scale });
                return;
            }

            gsap.to(barRef.current, {
                scaleX: scale,
                duration: 0.2,
                ease: "power1.out",
            });
        }

        function onFinish() {
            if (!barRef.current || !wrapperRef.current) return;

            trickleRef.current?.kill();
            trickleRef.current = null;

            if (prefersReduced) {
                reset();
                return;
            }

            // Complete the bar, then fade out and reset.
            gsap.to(barRef.current, {
                scaleX: 1,
                duration: 0.2,
                ease: "power2.out",
                onComplete: () => {
                    if (!wrapperRef.current) return;
                    gsap.to(wrapperRef.current, {
                        opacity: 0,
                        duration: 0.3,
                        ease: "power1.in",
                        onComplete: reset,
                    });
                },
            });
        }

        // Inertia v3 router event API.
        const removeStart = router.on("start", onStart);
        const removeProgress = router.on("progress", onProgress as EventListener);
        const removeFinish = router.on("finish", onFinish);

        return () => {
            removeStart();
            removeProgress();
            removeFinish();
            trickleRef.current?.kill();
        };
    }, []);

    return { barRef, wrapperRef };
}
