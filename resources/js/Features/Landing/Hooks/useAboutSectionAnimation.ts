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

/**
 * How many capability rows the pinned desktop window shows at once.
 * Must match the `*3` in .cap-viewport's `md:h-[calc(var(--cap-row)*3)]`
 * (AboutSection.tsx) — CSS can't read this constant.
 */
const VISIBLE_ROWS = 3;

/**
 * Mobile only. Desktop shows the rows through a pinned, cycling window; mobile
 * stacks all of them, so each row gets its own trigger — one staggered sweep
 * would play the last rows off-screen on a list this tall.
 */
function revealCapabilityRows(act2: HTMLElement) {
    act2.querySelectorAll(".cap-item").forEach((item) => {
        gsap.from(item, {
            y: 60,
            opacity: 0,
            duration: 0.9,
            ease: "power3.out",
            scrollTrigger: { trigger: item, start: "top 80%" },
        });

        const num = item.querySelector(".cap-num");
        if (num) {
            gsap.from(num, {
                scale: 0.5,
                duration: 0.5,
                ease: "back.out(1.4)",
                scrollTrigger: { trigger: item, start: "top 80%" },
            });
        }
    });
}

function buildDesktopPinnedExperience(section: HTMLElement) {
    /* ── ACT 1: THE VISION ── */
    const act1 = section.querySelector<HTMLElement>(".act-1")!;
    const a1i = setupChapterIntroState(act1);

    const act1Words = act1.querySelectorAll(".hw");
    const act1Label = act1.querySelector(".act1-label");
    const act1Body = act1.querySelector(".act1-body");
    const act1Glow = act1.querySelector(".act1-glow");
    const act1Media = act1.querySelector(".act1-media");

    /* Set initial states for content */
    gsap.set(act1Words, { y: "110%" });
    gsap.set(act1Label, { y: 30, opacity: 0 });
    gsap.set(act1Body, { y: 40, opacity: 0 });
    gsap.set(act1Media, { y: 48, opacity: 0, scale: 0.94 });

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
        /* Lands between the headline and the body so the fold reads
           label → headline → imagery → prose. */
        .to(
            act1Media,
            { y: 0, opacity: 1, scale: 1, duration: 0.15, ease: "none" },
            "content1+=0.2",
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
    const act2 = section.querySelector<HTMLElement>(".act-2");
    if (act2) {
        const a2i = setupChapterIntroState(act2);

        const act2Header = act2.querySelector(".act2-header");
        const items = Array.from(act2.querySelectorAll(".cap-item"));
        const track = act2.querySelector<HTMLElement>(".cap-track");

        /* Only the first window animates in — the rest ride in on the track. */
        gsap.set(act2Header, { y: 40, opacity: 0 });
        gsap.set(items.slice(0, VISIBLE_ROWS), { y: 60, opacity: 0 });

        const steps = Math.max(0, items.length - VISIBLE_ROWS);

        const tl2 = gsap.timeline({
            scrollTrigger: {
                trigger: act2,
                start: "top top",
                // Each cycle step needs its own scroll distance on top of the intro.
                end: `+=${300 + steps * 75}%`,
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

        /* The first three rows enter progressively */
        items.slice(0, VISIBLE_ROWS).forEach((item, i) => {
            const startPos = 0.08 + i * 0.14;
            tl2.to(
                item,
                { y: 0, opacity: 1, duration: 0.12, ease: "none" },
                `content2+=${startPos}`,
            );

            const num = item.querySelector(".cap-num");
            if (num) {
                tl2.from(
                    num,
                    { scale: 0.5, duration: 0.1, ease: "back.out(1.4)" },
                    `content2+=${startPos + 0.04}`,
                );
            }
        });

        /* Cycle: the track steps up exactly one row per beat, so row 1 leaves the
           top as row 4 arrives at the bottom. yPercent is relative to the track's
           own height, hence one row = 100 / items.length. */
        if (track && steps > 0) {
            for (let s = 1; s <= steps; s++) {
                tl2.to(
                    track,
                    {
                        yPercent: -(100 / items.length) * s,
                        duration: 0.35,
                        ease: "power2.inOut",
                    },
                    `content2+=${0.55 + (s - 1) * 0.45}`,
                );
            }
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

        // .cap-item is excluded — revealCapabilityRows() gives each row its own
        // trigger, since the act-2 list is far taller than one viewport.
        const elements = act.querySelectorAll(
            ".anim-el:not(.cap-item), .hw, .lead-card, .support-card, .act2-header, .act3-header",
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

    const act2 = section.querySelector<HTMLElement>(".act-2");
    if (act2) revealCapabilityRows(act2);
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
