import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { RefObject } from "react";

gsap.registerPlugin(ScrollTrigger);

export function useHeroSection(
    heroRef: RefObject<HTMLDivElement | null>,
    bgRef: RefObject<HTMLDivElement | null>,
    hexRef: RefObject<HTMLDivElement | null>
) {
    useGSAP(() => {
        if (!heroRef.current || !bgRef.current || !hexRef.current) return;

        const ctx = gsap.context(() => {
            const ease = "power4.out";

            // 1. Overlay fades in first — establishes the ground
            gsap.from(".hero-overlay", {
                opacity: 0,
                duration: 1.4,
                ease: "power2.out",
            });

            // 2. Precision marker label fades in quietly
            gsap.from(".hero-label", {
                opacity: 0,
                y: 16,
                duration: 0.7,
                ease,
                delay: 0.2,
            });

            // 3. "IDIG" surges up from below — the commanding entrance
            gsap.from(".hero-idig", {
                opacity: 0,
                y: 80,
                skewY: 2,
                duration: 1.1,
                ease,
                delay: 0.4,
            });

            // 4. "Laboratory" slides in from slight left offset, lagging behind IDIG
            gsap.from(".hero-lab", {
                opacity: 0,
                y: 50,
                x: -24,
                duration: 1.0,
                ease,
                delay: 0.65,
            });

            // 5. Separator line scales out from center
            gsap.from(".hero-separator", {
                scaleX: 0,
                opacity: 0,
                duration: 0.7,
                ease: "power2.out",
                delay: 0.9,
                transformOrigin: "center",
            });

            // 6. Description + CTA rise together
            gsap.from([".hero-desc", ".hero-cta"], {
                opacity: 0,
                y: 28,
                duration: 0.8,
                ease,
                stagger: 0.15,
                delay: 1.0,
            });

            // 7. Bottom bar slides up
            gsap.from(".hero-bottom", {
                opacity: 0,
                y: 20,
                duration: 0.6,
                ease,
                delay: 1.3,
            });

            // Parallax — background image (deeper travel)
            gsap.to(bgRef.current, {
                y: -120,
                ease: "none",
                scrollTrigger: {
                    trigger: heroRef.current,
                    start: "top top",
                    end: "bottom top",
                    scrub: true,
                },
            });

            // Parallax — hex texture (subtle)
            gsap.to(hexRef.current, {
                y: -50,
                ease: "none",
                scrollTrigger: {
                    trigger: heroRef.current,
                    start: "top top",
                    end: "bottom top",
                    scrub: true,
                },
            });
        }, heroRef);

        return () => ctx.revert();
    }, [heroRef, bgRef, hexRef]);
}
