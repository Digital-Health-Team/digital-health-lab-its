import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { RefObject } from "react";

gsap.registerPlugin(ScrollTrigger);

export default function useContactSection(
    sectionRef: RefObject<HTMLElement | null>,
) {
    useGSAP(
        () => {
            const section = sectionRef.current;
            if (!section) return;

            if (window.matchMedia("(prefers-reduced-motion: reduce)").matches)
                return;

            const eyebrow = section.querySelector(".cs-eyebrow");
            const headWords = section.querySelectorAll(".cs-head-word");
            const subline = section.querySelector(".cs-subline");
            const divider = section.querySelector(".cs-divider");
            const copy = section.querySelector(".cs-copy");
            const channels = section.querySelectorAll(".cs-channel");
            const form = section.querySelector(".cs-form");

            gsap.set(eyebrow, { y: 20, opacity: 0 });
            gsap.set(headWords, { y: "105%" });
            gsap.set(subline, { y: 16, opacity: 0 });
            gsap.set(divider, { scaleX: 0 });
            gsap.set(copy, { y: 16, opacity: 0 });
            gsap.set(channels, { y: 24, opacity: 0 });
            gsap.set(form, { y: 36, opacity: 0 });

            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: section,
                    start: "top 72%",
                    once: true,
                },
            });

            tl.to(eyebrow, {
                y: 0,
                opacity: 1,
                duration: 0.45,
                ease: "power3.out",
            })
                .to(
                    headWords,
                    {
                        y: "0%",
                        duration: 0.7,
                        stagger: 0.08,
                        ease: "power3.out",
                    },
                    0.1,
                )
                .to(
                    subline,
                    { y: 0, opacity: 1, duration: 0.4, ease: "power3.out" },
                    0.35,
                )
                .to(
                    divider,
                    { scaleX: 1, duration: 0.65, ease: "power3.out" },
                    0.4,
                )
                .to(
                    copy,
                    { y: 0, opacity: 1, duration: 0.5, ease: "power3.out" },
                    0.5,
                )
                .to(
                    channels,
                    {
                        y: 0,
                        opacity: 1,
                        duration: 0.5,
                        stagger: 0.1,
                        ease: "power3.out",
                    },
                    0.65,
                )
                .to(
                    form,
                    { y: 0, opacity: 1, duration: 0.65, ease: "power3.out" },
                    0.3,
                );
        },
        { scope: sectionRef },
    );
}
