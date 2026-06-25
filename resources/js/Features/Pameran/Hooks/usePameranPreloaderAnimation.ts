import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import React from "react";

// ── Timing constants (tune in browser to dial pacing) ──────────────────────
/** Opacity cross-fade duration per frame (seconds). */
const DISSOLVE = 0.9;
/** Gap between the start of each consecutive cross-fade (seconds). */
const CADENCE = 1.4;
/** Duration of each frame's Ken Burns scale drift (independent of playhead). */
const KENBURNS_DUR = 3.2;
/** Individual curtain strip sweep duration on exit. */
const CURTAIN_DUR = 0.55;
/** Stagger between curtain strips (left to right). */
const CURTAIN_STAGGER = 0.05;

/**
 * Varied transform-origins give each frame a distinct Ken Burns drift direction
 * (top-left pull, bottom-right pan, centre lock, etc.) for cinematic variety.
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
    /** Root panel — useGSAP scope. */
    containerRef: React.RefObject<HTMLDivElement | null>;
    /** Background-image divs, one per preloader frame. */
    imagesRef: React.MutableRefObject<(HTMLDivElement | null)[]>;
    /** Greeting wrapper div (eyebrow + display heading). */
    greetingRef: React.RefObject<HTMLDivElement | null>;
    /** Black curtain strips used for the staggered exit sweep. */
    curtainsRef: React.MutableRefObject<(HTMLDivElement | null)[]>;
    /** Percentage counter element whose textContent is updated by the tween. */
    counterRef: React.RefObject<HTMLDivElement | null>;
    prefersReducedMotion: boolean;
    onComplete: () => void;
}

export function usePameranPreloaderAnimation({
    containerRef,
    imagesRef,
    greetingRef,
    curtainsRef,
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
            const curtains = curtainsRef.current.filter(
                (el): el is HTMLDivElement => el !== null,
            );
            const counter = counterRef.current;
            // Hero lives outside the preloader scope — reference by id.
            const hero = document.getElementById("hero");

            // ── Reduced-motion path: static first frame, greeting visible, quick fade ──
            if (prefersReducedMotion) {
                if (images[0]) gsap.set(images[0], { opacity: 1 });
                gsap.set(".greeting-item", { opacity: 1, y: 0 });
                if (hero) gsap.set(hero, { scale: 1, opacity: 1 });
                gsap.to(containerRef.current, {
                    autoAlpha: 0,
                    duration: 0.2,
                    onComplete,
                });
                return;
            }

            // ── Image window end time ─────────────────────────────────────────────
            // The last cross-fade finishes at this absolute timeline position.
            const imageWindowEnd = (images.length - 1) * CADENCE + DISSOLVE;

            // Everything between exitAt…exitAt+0.35 fades out before curtains appear.
            const exitAt = imageWindowEnd + 0.3;
            const curtainStart = exitAt + 0.35;

            // Last curtain finishes sweeping at this time (used to set container hidden).
            const sweepEnd =
                curtainStart + (curtains.length - 1) * CURTAIN_STAGGER + CURTAIN_DUR;

            // ── Initial states ────────────────────────────────────────────────────
            if (images[0]) {
                gsap.set(images[0], {
                    opacity: 1,
                    scale: 1,
                    transformOrigin: ORIGINS[0],
                });
            }
            if (images.length > 1) gsap.set(images.slice(1), { opacity: 0, scale: 1 });
            gsap.set(curtains, { opacity: 0 });
            gsap.set(".greeting-item", { opacity: 0, y: 20 });
            if (hero) {
                gsap.set(hero, {
                    scale: 1.04,
                    opacity: 0,
                    transformOrigin: "50% 50%",
                });
            }

            // ── Ken Burns helper ─────────────────────────────────────────────────
            // Fires as independent tweens via tl.call() — never advances the playhead.
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

            // ── Counter proxy ─────────────────────────────────────────────────────
            const proxy = { v: 0 };

            // ── Full cinematic timeline ───────────────────────────────────────────
            const tl = gsap.timeline({ onComplete });

            // 1. Counter: 00 → 100 across the full image window (power2.out).
            tl.to(
                proxy,
                {
                    v: 100,
                    duration: imageWindowEnd,
                    ease: "power2.out",
                    snap: { v: 1 },
                    onUpdate() {
                        if (counter) {
                            counter.textContent =
                                String(Math.round(proxy.v)).padStart(2, "0") + "%";
                        }
                    },
                },
                0,
            );

            // 2. Greeting stagger-reveal early; holds through the whole sequence.
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

            // 5. Hold at 100 — 300 ms gap before exit.
            tl.to({}, { duration: 0.3 }, imageWindowEnd);

            // ── Exit sequence ─────────────────────────────────────────────────────

            // 6a. Fade all images to black simultaneously as greeting/counter exit.
            //     This ensures the container is a plain black rect by curtainStart,
            //     so hiding it reveals the hero cleanly (no image flash).
            tl.to(images, { opacity: 0, duration: 0.3, ease: "none" }, exitAt);

            // 6b. Greeting wrapper slides up and fades (0.35 s).
            if (greetingRef.current) {
                tl.to(
                    greetingRef.current,
                    {
                        y: -28,
                        autoAlpha: 0,
                        duration: 0.35,
                        ease: "power3.out",
                    },
                    exitAt,
                );
            }

            // 6c. Counter slides up and fades simultaneously.
            if (counter) {
                tl.to(
                    counter,
                    {
                        y: -36,
                        autoAlpha: 0,
                        duration: 0.35,
                        ease: "power3.out",
                    },
                    exitAt,
                );
            }

            // 6d. Snap hero to final state just before curtains start revealing it.
            //     Using set() instead of a tween avoids the zoom-conflict glitch
            //     that occurred when hero scale-tweened while the panel slid upward.
            if (hero) {
                tl.set(hero, { opacity: 1, scale: 1 }, curtainStart - 0.02);
            }

            // 6e. Curtains: appear instantly, then stagger-sweep upward (left→right),
            //     mirroring the Core/Landing preloader transition. This replaces the
            //     former single-block yPercent: -100 which caused the laggy glitch.
            if (curtains.length > 0) {
                tl.set(curtains, { opacity: 1 }, curtainStart);
                tl.to(
                    curtains,
                    {
                        yPercent: -100,
                        duration: CURTAIN_DUR,
                        stagger: CURTAIN_STAGGER,
                        ease: "power4.inOut",
                        transformOrigin: "top",
                    },
                    curtainStart,
                );
            }

            // 6f. Hide the container the instant curtains cover the viewport.
            //     Curtains are fixed siblings at z-[10000] — as soon as they are
            //     visible (opacity: 1) they fully cover the z-[9999] container.
            //     Hiding the container here means the curtain sweep reveals the hero
            //     page, not the image stack that's still inside the container.
            tl.set(containerRef.current, { autoAlpha: 0 }, curtainStart + 0.01);
        },
        { scope: containerRef },
    );
}
