import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { RefObject } from "react";
import { MEDIA_DESKTOP, MEDIA_MOBILE_MOTION_OK } from "../Utils/breakpoints";
import {
    setupChapterIntroState,
    playChapterIntroDesktop,
    revealChapterIntroOnScroll,
} from "../Utils/chapterIntro";

gsap.registerPlugin(ScrollTrigger);

function buildDesktopPinnedExperience(section: HTMLElement) {
    /* ── ACT 1: THE VISION ── */
    const act1 = section.querySelector<HTMLElement>(".act-1")!;
    const a1i = setupChapterIntroState(act1);

    const act1Words = act1.querySelectorAll(".hw");
    const act1Label = act1.querySelector(".act1-label");
    const act1Body = act1.querySelector(".act1-body");
    const act1Glow = act1.querySelector(".act1-glow");

    /* Set initial states for content */
    gsap.set(act1Words, { y: "110%" });
    gsap.set(act1Label, { y: 30, opacity: 0 });
    gsap.set(act1Body, { y: 40, opacity: 0 });

    const tl1 = gsap.timeline({
        scrollTrigger: {
            trigger: act1,
            start: "top top",
            end: "+=300%",
            pin: true,
            scrub: 1,
            anticipatePin: 1,
        },
    });

    // 1. Intro Animation
    playChapterIntroDesktop(tl1, a1i)
        .to({}, { duration: 0.2 }) // Hold

        // 2. Transition (Intro up, Content up)
        .add("transition1")
        .to(
            a1i.intro,
            { yPercent: -100, duration: 0.5, ease: "power3.inOut" },
            "transition1",
        )
        .to(
            a1i.content,
            { yPercent: 0, duration: 0.5, ease: "power3.inOut" },
            "transition1",
        )

        // 3. Content Animation
        .add("content1", "transition1+=0.2")
        .to(
            act1Label,
            { y: 0, opacity: 1, duration: 0.08, ease: "none" },
            "content1",
        )
        .to(
            act1Words,
            { y: "0%", duration: 0.3, stagger: 0.04, ease: "power3.out" },
            "content1+=0.04",
        )
        .to(
            act1Body,
            { y: 0, opacity: 1, duration: 0.15, ease: "none" },
            "content1+=0.35",
        )
        /* Glow shifts subtly */
        .to(
            act1Glow,
            { x: 30, y: -20, duration: 0.5, ease: "none" },
            "content1",
        )
        /* Hold content visible */
        .to({}, { duration: 0.2 })
        /* Exit: fade out and drift up */
        .to(a1i.content, {
            opacity: 0,
            yPercent: -10,
            duration: 0.15,
            ease: "none",
        });

    /* ── ACT 2: CAPABILITIES ── */
    const act2 = section.querySelector<HTMLElement>(".act-2")!;
    if (act2) {
        const a2i = setupChapterIntroState(act2);

        const act2Header = act2.querySelector(".act2-header");
        const act2Items = act2.querySelectorAll(".cap-item");
        const act2Spine = act2.querySelector(".cap-spine");

        gsap.set(act2Header, { y: 40, opacity: 0 });
        gsap.set(act2Items, { y: 60, opacity: 0 });
        if (act2Spine) {
            gsap.set(act2Spine, { scaleY: 0, transformOrigin: "top" });
        }

        const tl2 = gsap.timeline({
            scrollTrigger: {
                trigger: act2,
                start: "top top",
                end: "+=350%",
                pin: true,
                scrub: 1,
                anticipatePin: 1,
            },
        });

        // 1. Intro Animation
        playChapterIntroDesktop(tl2, a2i)
            .to({}, { duration: 0.2 }) // Hold

            // 2. Transition
            .add("transition2")
            .to(
                a2i.intro,
                { yPercent: -100, duration: 0.5, ease: "power3.inOut" },
                "transition2",
            )
            .to(
                a2i.content,
                { yPercent: 0, duration: 0.5, ease: "power3.inOut" },
                "transition2",
            )

            // 3. Content Animation
            .add("content2", "transition2+=0.2")
            .to(
                act2Header,
                { y: 0, opacity: 1, duration: 0.08, ease: "none" },
                "content2",
            );

        /* Each capability enters progressively */
        act2Items.forEach((item, i) => {
            const startPos = 0.08 + i * 0.2;
            tl2.to(
                item,
                { y: 0, opacity: 1, duration: 0.12, ease: "none" },
                `content2+=${startPos}`,
            );

            /* Animate the number scaling in */
            const num = item.querySelector(".cap-num");
            if (num) {
                tl2.from(
                    num,
                    { scale: 0.5, duration: 0.1, ease: "back.out(1.4)" },
                    `content2+=${startPos + 0.04}`,
                );
            }
        });

        /* Spine grows with capabilities */
        if (act2Spine) {
            tl2.to(
                act2Spine,
                { scaleY: 1, duration: 0.55, ease: "none" },
                "content2+=0.1",
            );
        }

        /* Hold, then exit */
        tl2.to({}, { duration: 0.15 }).to(a2i.content, {
            opacity: 0,
            yPercent: -10,
            duration: 0.12,
            ease: "none",
        });
    }
}

function buildMobileAnimations(section: HTMLElement) {
    const acts = section.querySelectorAll(".chapter-container");

    acts.forEach((act) => {
        // Ensure content is visible on mobile
        const contentBlock = act.querySelector<HTMLElement>(".chapter-content");
        if (contentBlock) {
            gsap.set(contentBlock, { yPercent: 0, position: "relative" });
        }

        const introBlock = act.querySelector<HTMLElement>(".chapter-intro");
        if (introBlock) {
            // Keep intro block visible at the top
            gsap.set(introBlock, {
                position: "relative",
                height: "auto",
                padding: "100px 0",
            });

            revealChapterIntroOnScroll(introBlock);
        }

        const elements = act.querySelectorAll(
            ".anim-el, .hw, .cap-item, .lead-card, .support-card, .act2-header, .act3-header",
        );

        gsap.from(elements, {
            y: 40,
            opacity: 0,
            duration: 0.8,
            stagger: 0.12,
            ease: "power3.out",
            scrollTrigger: {
                trigger: contentBlock || act,
                start: "top 78%",
                toggleActions: "play none none reverse",
            },
        });
    });
}

export function useAboutSectionAnimation(sectionRef: RefObject<HTMLElement | null>) {
    useGSAP(
        () => {
            if (!sectionRef.current) return;
            const section = sectionRef.current;
            const mm = gsap.matchMedia();

            mm.add(
                {
                    isDesktop: MEDIA_DESKTOP,
                    isMobile: MEDIA_MOBILE_MOTION_OK,
                },
                (context) => {
                    const { isDesktop } = context.conditions!;

                    if (isDesktop) {
                        buildDesktopPinnedExperience(section);
                    } else {
                        buildMobileAnimations(section);
                    }
                },
            );
        },
        { scope: sectionRef },
    );
}
