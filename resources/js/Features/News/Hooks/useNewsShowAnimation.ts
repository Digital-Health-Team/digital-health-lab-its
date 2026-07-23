import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { RefObject } from "react";
import { prefersReducedMotion } from "@/Features/Landing/Utils/motionPreferences";

gsap.registerPlugin(ScrollTrigger);

/**
 * Article detail reveal — masthead settles in, the hero image scales into
 * place across the navy→light seam, then body copy and related cards fade
 * up as they enter view.
 */
export function useNewsShowAnimation(containerRef: RefObject<HTMLElement | null>) {
    useGSAP(
        () => {
            if (!containerRef.current) return;
            const section = containerRef.current;

            if (prefersReducedMotion()) {
                gsap.from([".news-show-masthead", ".news-show-hero", ".news-show-body", ".news-show-related"], {
                    opacity: 0,
                    duration: 0.8,
                    stagger: 0.1,
                    scrollTrigger: { trigger: section, start: "top 80%" },
                });
                return;
            }

            const tl = gsap.timeline();

            tl.from(".news-show-masthead > *", {
                opacity: 0,
                y: 20,
                duration: 0.8,
                stagger: 0.12,
                ease: "power3.out",
            });

            tl.from(
                ".news-show-hero",
                { y: 40, scale: 0.98, autoAlpha: 0, duration: 0.9, ease: "power3.out" },
                "-=0.4",
            );

            tl.from(
                ".news-show-meta",
                { y: 16, autoAlpha: 0, duration: 0.5, ease: "power3.out" },
                "-=0.3",
            );

            gsap.from(".news-show-body > *", {
                opacity: 0,
                y: 16,
                duration: 0.6,
                stagger: 0.1,
                ease: "power3.out",
                scrollTrigger: { trigger: ".news-show-body", start: "top 85%" },
            });

            const relatedCards = section.querySelectorAll(".news-show-related-card");
            if (relatedCards.length) {
                gsap.from(relatedCards, {
                    y: 24,
                    autoAlpha: 0,
                    duration: 0.7,
                    stagger: 0.09,
                    ease: "power3.out",
                    scrollTrigger: { trigger: ".news-show-related", start: "top 85%" },
                });
            }
        },
        { scope: containerRef },
    );
}
