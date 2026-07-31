import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { RefObject } from "react";
import { prefersReducedMotion } from "../Utils/motionPreferences";

gsap.registerPlugin(ScrollTrigger);

export function useCtaSectionAnimation(
    containerRef: RefObject<HTMLElement | null>,
) {
    useGSAP(
        () => {
            if (!containerRef.current) return;
            const section = containerRef.current;

            const ecgPath = section.querySelector<SVGPathElement>(
                ".cta-ecg .ecg-path",
            );
            const pulseDots = section.querySelectorAll(".cta-ecg circle");

            if (prefersReducedMotion()) {
                // PRODUCT.md: ECG/pulse animation must be suppressed —
                // static drawn line, travelling pulse hidden, opacity-only fades.
                gsap.set(pulseDots, { visibility: "hidden" });
                gsap.from([".cta-intro", ".cta-ecg", ".cta-actions"], {
                    opacity: 0,
                    duration: 0.8,
                    stagger: 0.15,
                    scrollTrigger: { trigger: section, start: "top 80%" },
                });
                return;
            }

            // Travelling pulse stays hidden until the line has drawn itself
            gsap.set(pulseDots, { visibility: "hidden" });

            const tl = gsap.timeline({
                scrollTrigger: { trigger: section, start: "top 75%" },
            });

            tl.from(".cta-intro > *", {
                opacity: 0,
                y: 20,
                duration: 0.8,
                stagger: 0.15,
                ease: "power3.out",
            });

            if (ecgPath) {
                const len = ecgPath.getTotalLength();
                gsap.set(ecgPath, {
                    strokeDasharray: len,
                    strokeDashoffset: len,
                });
                tl.to(
                    ecgPath,
                    { strokeDashoffset: 0, duration: 1.4, ease: "power2.inOut" },
                    "-=0.3",
                );
            }

            tl.from(
                ".cta-actions > *",
                {
                    opacity: 0,
                    y: 24,
                    duration: 0.7,
                    stagger: 0.12,
                    ease: "power3.out",
                },
                "-=0.6",
            );

            tl.set(pulseDots, { visibility: "visible" }, "-=0.2");
        },
        { scope: containerRef },
    );
}
