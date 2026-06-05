import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { type RefObject } from "react";

export function useHeroBannerAnimation(containerRef: RefObject<HTMLDivElement | null>) {
    useGSAP(
        () => {
            if (!containerRef.current) return;

            // Honour reduced-motion preference
            if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;

            const ease = "power3.out";

            const ctx = gsap.context(() => {
                // Staggered content entrance
                gsap.from("[data-hero-eyebrow]", { opacity: 0, y: 12, duration: 0.5, ease, delay: 0.1 });
                gsap.from("[data-hero-title]", { opacity: 0, y: 20, duration: 0.65, ease, delay: 0.25 });
                gsap.from("[data-hero-body]", { opacity: 0, y: 16, duration: 0.55, ease, delay: 0.4 });
                gsap.from("[data-hero-cta]", { opacity: 0, y: 12, scale: 0.96, duration: 0.5, ease, delay: 0.55 });
                gsap.from("[data-hero-artwork]", { opacity: 0, x: 24, duration: 0.7, ease, delay: 0.35 });
            }, containerRef);

            return () => ctx.revert();
        },
        { scope: containerRef },
    );
}
