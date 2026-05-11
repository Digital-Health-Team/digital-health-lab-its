import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { RefObject } from "react";

gsap.registerPlugin(ScrollTrigger);

function buildDesktopPin(section: HTMLElement) {
    /* ── ACT 1: HEADING ── */
    const act1 = section.querySelector<HTMLElement>(".wg-act-1")!;
    const act1Content = act1.querySelector<HTMLElement>(".wg-act-content-1")!;
    const label = act1.querySelector(".wg-label");
    const hwWords = act1.querySelectorAll(".wg-hw");
    const body = act1.querySelector(".wg-body");

    gsap.set(label, { y: 28, opacity: 0 });
    gsap.set(hwWords, { y: "110%" });
    gsap.set(body, { y: 36, opacity: 0 });

    const tl1 = gsap.timeline({
        scrollTrigger: {
            trigger: act1,
            start: "top top",
            end: "+=150%",
            pin: true,
            scrub: 1,
            anticipatePin: 1,
        },
    });

    tl1.to(label, { y: 0, opacity: 1, duration: 0.08, ease: "none" })
        .to(
            hwWords,
            { y: "0%", duration: 0.28, stagger: 0.035, ease: "power2.out" },
            0.05,
        )
        .to(body, { y: 0, opacity: 1, duration: 0.14, ease: "none" }, 0.28)
        /* Hold */
        .to({}, { duration: 0.25 })
        /* Exit: content drifts up and fades */
        .to(act1Content, {
            opacity: 0,
            y: -60,
            duration: 0.18,
            ease: "none",
        });

    /* ── ACT 2: QUOTE + HEXAGON CASCADE ── */
    const act2 = section.querySelector<HTMLElement>(".wg-act-2")!;
    const act2Content = act2.querySelector<HTMLElement>(".wg-act-content-2")!;
    const hexPanel = act2.querySelector<HTMLElement>(".wg-hex-panel");
    const qWords = act2.querySelectorAll<HTMLElement>(".wg-qword > span");
    const attr = act2.querySelector(".wg-attr");
    const cta = act2.querySelector(".wg-cta");

    // Initial states
    gsap.set(qWords, { y: "110%", opacity: 0 });
    gsap.set(attr, { opacity: 0, x: 20 });
    gsap.set(cta, { opacity: 0, y: 24 });

    const tl2 = gsap.timeline({
        scrollTrigger: {
            trigger: act2,
            start: "top top",
            end: "+=200%",
            pin: true,
            scrub: 1,
            anticipatePin: 1,
        },
    });

    // Hexagon grid sweeps in from the right edge
    if (hexPanel) {
        tl2.to(
            hexPanel,
            {
                clipPath: "inset(0 0% 0 0)",
                duration: 0.35,
                ease: "none",
            },
            0,
        );
    }

    // Quote words cascade in from the right, staggered
    tl2.to(
        qWords,
        {
            y: "0%",
            opacity: 1,
            duration: 0.28,
            stagger: 0.012,
            ease: "power2.out",
        },
        0.12,
    );

    // Attribution slides in
    tl2.to(attr, { opacity: 1, x: 0, duration: 0.14, ease: "none" }, 0.5);

    // Hold, then CTA appears
    tl2.to({}, { duration: 0.15 }).to(cta, {
        opacity: 1,
        y: 0,
        duration: 0.12,
        ease: "none",
    });
}

function buildMobileFallback(section: HTMLElement) {
    const act1 = section.querySelector(".wg-act-1");
    const act2 = section.querySelector(".wg-act-2");

    if (act1) {
        gsap.from([".wg-label", ".wg-hw", ".wg-body"], {
            y: 30,
            opacity: 0,
            duration: 0.8,
            stagger: 0.08,
            ease: "power3.out",
            scrollTrigger: { trigger: act1, start: "top 82%" },
        });
    }

    if (act2) {
        // Reveal hex panel immediately
        const hex = act2.querySelector<HTMLElement>(".wg-hex-panel");
        if (hex) gsap.set(hex, { clipPath: "inset(0 0% 0 0)" });

        gsap.from([".wg-qword > span", ".wg-attr", ".wg-cta"], {
            y: 24,
            opacity: 0,
            duration: 0.7,
            stagger: 0.04,
            ease: "power3.out",
            scrollTrigger: { trigger: act2, start: "top 82%" },
        });
    }
}

export function useSharingWisdom(sectionRef: RefObject<HTMLElement | null>) {
    useGSAP(
        () => {
            if (!sectionRef.current) return;
            const section = sectionRef.current;
            const mm = gsap.matchMedia();

            mm.add(
                {
                    isDesktop:
                        "(min-width: 768px) and (prefers-reduced-motion: no-preference)",
                    isMobile:
                        "(max-width: 767px), (prefers-reduced-motion: reduce)",
                },
                (ctx) => {
                    const { isDesktop } = ctx.conditions!;
                    if (isDesktop) {
                        buildDesktopPin(section);
                    } else {
                        buildMobileFallback(section);
                    }
                },
            );
        },
        { scope: sectionRef },
    );
}
