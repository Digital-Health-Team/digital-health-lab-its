import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { RefObject } from "react";
import { prefersReducedMotion } from "../Utils/motionPreferences";

gsap.registerPlugin(ScrollTrigger);

export function useArticlesSectionAnimation(
    containerRef: RefObject<HTMLElement | null>,
) {
    useGSAP(
        () => {
            if (!containerRef.current) return;
            const section = containerRef.current;

            if (prefersReducedMotion()) {
                // Opacity-only fades; hairlines stay pre-drawn.
                gsap.from(
                    [".articles-intro", ".articles-lead", ".articles-row", ".articles-more"],
                    {
                        opacity: 0,
                        duration: 0.8,
                        stagger: 0.08,
                        scrollTrigger: { trigger: section, start: "top 80%" },
                    },
                );
                return;
            }

            gsap.from(".articles-intro > *", {
                opacity: 0,
                y: 20,
                duration: 0.8,
                stagger: 0.15,
                ease: "power3.out",
                scrollTrigger: { trigger: ".articles-intro", start: "top 85%" },
            });

            const rules = section.querySelectorAll(".articles-row-rule");
            gsap.set(rules, { scaleX: 0 });

            const tl = gsap.timeline({
                scrollTrigger: { trigger: ".articles-lead", start: "top 80%" },
            });

            tl.from(".articles-lead", {
                x: -40,
                autoAlpha: 0,
                duration: 1,
                ease: "power3.out",
            });

            tl.from(
                ".articles-index-label",
                { y: 16, autoAlpha: 0, duration: 0.5, ease: "power3.out" },
                0.1,
            );

            tl.from(
                ".articles-row",
                {
                    y: 24,
                    autoAlpha: 0,
                    duration: 0.7,
                    stagger: 0.09,
                    ease: "power3.out",
                },
                0.2,
            );

            // The editorial touch: index hairlines draw from the left.
            tl.to(
                rules,
                { scaleX: 1, duration: 0.6, stagger: 0.09, ease: "power2.out" },
                0.3,
            );

            tl.from(
                ".articles-more",
                { y: 16, autoAlpha: 0, duration: 0.6, ease: "power3.out" },
                "-=0.5",
            );
        },
        { scope: containerRef },
    );
}
