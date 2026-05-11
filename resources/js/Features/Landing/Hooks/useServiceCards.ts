import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { RefObject } from "react";
import { SERVICES } from "../Constants/serviceCardsData";

gsap.registerPlugin(ScrollTrigger);

export function useServiceCards(containerRef: RefObject<HTMLDivElement | null>) {
    useGSAP(
        () => {
            if (!containerRef.current) return;
            
            const prefersReducedMotion = window.matchMedia(
                "(prefers-reduced-motion: reduce)",
            ).matches;

            gsap.from(".service-intro > *", {
                opacity: 0,
                y: 20,
                duration: 0.8,
                stagger: 0.15,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: ".service-intro",
                    start: "top 85%",
                },
            });

            const wrappers = gsap.utils.toArray<HTMLElement>(
                ".service-card-wrapper",
            );

            wrappers.forEach((wrapper, i) => {
                const align = SERVICES[i].align;
                const xOffset = align === "left" ? -80 : 80;

                if (!prefersReducedMotion) {
                    gsap.from(wrapper, {
                        opacity: 0,
                        x: xOffset,
                        y: 60,
                        rotation: SERVICES[i].tilt * 2, // slightly exaggerated starting tilt
                        duration: 1.4,
                        ease: "power3.out",
                        scrollTrigger: {
                            trigger: wrapper,
                            start: "top 80%",
                            toggleActions: "play none none reverse",
                        },
                    });
                } else {
                    gsap.from(wrapper, {
                        opacity: 0,
                        y: 20,
                        duration: 1,
                        scrollTrigger: {
                            trigger: wrapper,
                            start: "top 85%",
                        },
                    });
                }
            });

            // Floating 3D assets
            if (!prefersReducedMotion) {
                const floatingImages =
                    gsap.utils.toArray<HTMLElement>(".service-image");
                floatingImages.forEach((img, i) => {
                    gsap.to(img, {
                        y: "-=20",
                        rotation: (i % 2 === 0 ? 1 : -1) * 1.5,
                        duration: 3.5 + i * 0.4,
                        ease: "sine.inOut",
                        yoyo: true,
                        repeat: -1,
                    });
                });
            }
        },
        { scope: containerRef },
    );
}
