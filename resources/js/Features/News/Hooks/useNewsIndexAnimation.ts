import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { RefObject } from "react";
import { prefersReducedMotion } from "@/Features/Landing/Utils/motionPreferences";

gsap.registerPlugin(ScrollTrigger);

/**
 * Article index reveal — masthead settles in, the featured lead slides up
 * from the seam, then the catalogue grid staggers into view on scroll.
 */
export function useNewsIndexAnimation(containerRef: RefObject<HTMLElement | null>) {
    useGSAP(
        () => {
            if (!containerRef.current) return;
            const section = containerRef.current;

            if (prefersReducedMotion()) {
                gsap.from([".news-index-masthead", ".news-index-lead", ".news-index-grid"], {
                    opacity: 0,
                    duration: 0.8,
                    stagger: 0.1,
                    scrollTrigger: { trigger: section, start: "top 80%" },
                });
                return;
            }

            const tl = gsap.timeline();

            tl.from(".news-index-masthead > *", {
                opacity: 0,
                y: 20,
                duration: 0.8,
                stagger: 0.12,
                ease: "power3.out",
            });

            tl.from(
                ".news-index-lead",
                { y: 40, autoAlpha: 0, duration: 0.9, ease: "power3.out" },
                "-=0.4",
            );

            const cards = section.querySelectorAll(".news-index-card");
            if (cards.length) {
                gsap.from(cards, {
                    y: 24,
                    autoAlpha: 0,
                    duration: 0.7,
                    stagger: 0.09,
                    ease: "power3.out",
                    scrollTrigger: { trigger: ".news-index-grid", start: "top 85%" },
                });
            }
        },
        { scope: containerRef },
    );
}
