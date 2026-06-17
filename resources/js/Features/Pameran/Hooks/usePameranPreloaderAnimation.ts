import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import React from "react";

// ── Timing constants (tune in browser to dial pacing) ──────────────────────
/** Opacity cross-fade duration per frame (seconds). */
const DISSOLVE = 0.9;
/** Gap between the start of each consecutive cross-fade (seconds). */
const CADENCE = 1.4;
/** Duration of each frame's Ken Burns scale drift (seconds, independent of playhead). */
const KENBURNS_DUR = 3.2;
/** Exit panel slide-up duration. */
const EXIT_DUR = 0.6;

/**
 * Varied transform-origins give each frame a distinct drift direction
 * (top-left pull, bottom-right, centre pan, etc.) for cinematic variety.
 */
const ORIGINS = [
    "50% 50%",
    "30% 40%",
    "70% 60%",
    "40% 70%",
    "60% 30%",
    "35% 55%",
    "65% 45%",
] as const;

interface UsePameranPreloaderAnimationProps {
    /** Root panel — useGSAP scope + slide-up target. */
    containerRef: React.RefObject<HTMLDivElement | null>;
    /** Background-image divs, one per preloader frame. */
    imagesRef: React.MutableRefObject<(HTMLDivElement | null)[]>;
    /** Greeting wrapper div (eyebrow + display heading). */
    greetingRef: React.RefObject<HTMLDivElement | null>;
    /** Percentage counter element whose textContent is updated by the tween. */
    counterRef: React.RefObject<HTMLDivElement | null>;
    prefersReducedMotion: boolean;
    onComplete: () => void;
}

export function usePameranPreloaderAnimation({
    containerRef,
    imagesRef,
    greetingRef,
    counterRef,
    prefersReducedMotion,
    onComplete,
}: UsePameranPreloaderAnimationProps) {
    useGSAP(
        () => {
            if (!containerRef.current) return;

            const images = imagesRef.current.filter(
                (el): el is HTMLDivElement => el !== null,
            );
            const counter = counterRef.current;
            // Hero lives outside the preloader scope — reference by id.
            const hero = document.getElementById("hero");

            // ── Reduced-motion path: static first frame, greeting visible, quick fade ──
            if (prefersReducedMotion) {
                if (images[0]) gsap.set(images[0], { opacity: 1 });
                // Reveal greeting immediately without animation.
                gsap.set(".greeting-item", { opacity: 1, y: 0 });
                if (hero) {
                    gsap.set(hero, {
                        scale: 1,
                        opacity: 1,
                        transformOrigin: "50% 50%",
                    });
                }
                gsap.to(containerRef.current, {
                    autoAlpha: 0,
                    duration: 0.2,
                    onComplete,
                });
                return;
            }

            // ── Compute image window end ──────────────────────────────────────────
            // imageWindowEnd = time at which the last cross-fade finishes.
            const imageWindowEnd = (images.length - 1) * CADENCE + DISSOLVE;
            const exitAt = imageWindowEnd + 0.3; // 300 ms hold before exit

            // ── Initial states ────────────────────────────────────────────────────
            // Frame 0 starts visible; rest hidden. Scale reset so Ken Burns starts from 1.
            if (images[0]) {
                gsap.set(images[0], {
                    opacity: 1,
                    scale: 1,
                    transformOrigin: ORIGINS[0],
                });
            }
            if (images.length > 1) {
                gsap.set(images.slice(1), { opacity: 0, scale: 1 });
            }
            // Greeting items start hidden and slightly below.
            gsap.set(".greeting-item", { opacity: 0, y: 20 });
            // Hero starts scaled up and invisible — revealed on exit.
            if (hero) {
                gsap.set(hero, {
                    scale: 1.04,
                    opacity: 0,
                    transformOrigin: "50% 50%",
                });
            }

            // ── Ken Burns helper ─────────────────────────────────────────────────
            // Independent tweens launched via tl.call() — never advance the playhead.
            const startKenBurns = (i: number) => {
                const el = images[i];
                if (!el) return;
                gsap.to(el, {
                    scale: 1.08,
                    duration: KENBURNS_DUR,
                    ease: "power1.inOut",
                    transformOrigin: ORIGINS[i % ORIGINS.length],
                });
            };

            // ── Proxy object for the tweened percentage ───────────────────────────
            const proxy = { v: 0 };

            // ── Full cinematic timeline ───────────────────────────────────────────
            const tl = gsap.timeline({ onComplete });

            // 1. Counter: 00 → 100 across the full image window.
            //    Runs from t=0, finishing exactly as the last frame settles.
            tl.to(
                proxy,
                {
                    v: 100,
                    duration: imageWindowEnd,
                    ease: "power2.out",
                    snap: { v: 1 },
                    onUpdate() {
                        if (counter) {
                            counter.textContent = String(
                                Math.round(proxy.v),
                            ).padStart(2, "0");
                        }
                    },
                },
                0,
            );

            // 2. Greeting reveal — staggered word-by-word early in the timeline,
            //    then holds throughout the whole image sequence.
            tl.to(
                ".greeting-item",
                {
                    opacity: 1,
                    y: 0,
                    duration: 0.7,
                    stagger: 0.14,
                    ease: "power3.out",
                },
                0.5,
            );

            // 3. Frame 0 Ken Burns starts immediately.
            tl.call(() => startKenBurns(0), [], 0);

            // 4. Cross-fades + Ken Burns for frames 1..N-1 at CADENCE intervals.
            images.slice(1).forEach((img, i) => {
                const t = (i + 1) * CADENCE;
                tl.to(
                    img,
                    { opacity: 1, duration: DISSOLVE, ease: "power2.inOut" },
                    t,
                );
                tl.call(() => startKenBurns(i + 1), [], t);
            });

            // 5. Hold at 100 — explicit 300 ms gap before exit sequence starts.
            tl.to({}, { duration: 0.3 }, imageWindowEnd);

            // 6. Exit sequence — all start at exitAt.
            //    a. Greeting wrapper slides up and fades.
            if (greetingRef.current) {
                tl.to(
                    greetingRef.current,
                    {
                        y: -32,
                        autoAlpha: 0,
                        duration: 0.45,
                        ease: "power3.out",
                    },
                    exitAt,
                );
            }

            //    b. Counter slides up and fades.
            if (counter) {
                tl.to(
                    counter,
                    {
                        y: -40,
                        autoAlpha: 0,
                        duration: 0.4,
                        ease: "power3.out",
                    },
                    exitAt,
                );
            }

            //    c. Panel slides up off-screen.
            tl.to(
                containerRef.current,
                { yPercent: -100, duration: EXIT_DUR, ease: "power4.inOut" },
                exitAt,
            );

            //    d. Hero fades + scales in (1.04 → 1) concurrently with panel exit.
            if (hero) {
                tl.to(
                    hero,
                    { scale: 1, opacity: 1, duration: 0.7, ease: "power2.out" },
                    exitAt,
                );
            }
        },
        { scope: containerRef },
    );
}
