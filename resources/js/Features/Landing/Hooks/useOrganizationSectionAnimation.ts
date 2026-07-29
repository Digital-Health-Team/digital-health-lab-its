import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { RefObject } from "react";
import { MEDIA_DESKTOP, MEDIA_MOBILE } from "../Utils/breakpoints";
import { ROSTER_VISIBLE_ROWS } from "../Data/organizationSection.data";
import {
    setupChapterIntroState,
    playChapterIntroDesktop,
    revealChapterIntroOnScroll,
} from "../Utils/chapterIntro";
import { prefersReducedMotion } from "../Utils/motionPreferences";

gsap.registerPlugin(ScrollTrigger);

// ── Desktop pinned choreography ──────────────────────────────────────

function buildDesktop(section: HTMLElement) {
    /* ACT 0 ── Struktur Organisasi Introduction */
    const act0 = section.querySelector<HTMLElement>(".act-0");
    if (act0) {
        const digitStrip = act0.querySelector<HTMLElement>(".digit-strip");
        const glyphs = act0.querySelectorAll(".glyph-char");
        const parabolic = act0.querySelectorAll(".parabolic-word");

        gsap.set(digitStrip, { yPercent: 0 });
        gsap.set(glyphs, { y: "120%", opacity: 0, rotateX: -90 });
        gsap.set(parabolic, { y: 30, opacity: 0, scale: 0.9 });

        const tl0 = gsap.timeline({
            scrollTrigger: {
                trigger: act0,
                start: "top top",
                end: "+=150%",
                pin: true,
                scrub: 1,
                anticipatePin: 1,
            },
        });

        playChapterIntroDesktop(tl0, { digitStrip, glyphs, parabolic }).to(
            {},
            { duration: 0.3 },
        ); // Hold
    }

    /* ACT 1 ── Head of Laboratory */
    const act1 = section.querySelector<HTMLElement>(".act-1")!;
    if (act1) {
        const a1i = setupChapterIntroState(act1);

        const a1c = act1.querySelector<HTMLElement>(".act-1-content")!;
        const a1eb = act1.querySelector(".act-1-eyebrow");
        const a1avatar = act1.querySelector(".act-1-avatar");
        const a1words = act1.querySelectorAll(".act-1-word");
        const a1role = act1.querySelector(".act-1-role");
        const a1hl = act1.querySelector(".act-1-hairline");
        const a1desc = act1.querySelector(".act-1-desc");
        const a1hexCenter = act1.querySelector(".act-1-hex-center");
        const a1hexItems = act1.querySelectorAll(".act-1-hex-item");

        gsap.set(a1eb, { y: 28, opacity: 0 });
        gsap.set(a1avatar, { scale: 0.7, opacity: 0 });
        gsap.set(a1words, { y: "110%" });
        gsap.set(a1role, { x: -18, opacity: 0 });
        gsap.set(a1hl, { scaleX: 0 });
        gsap.set(a1desc, { y: 34, opacity: 0 });
        gsap.set(a1hexCenter, { scale: 0.55, opacity: 0 });
        gsap.set(a1hexItems, { scale: 0.4, opacity: 0 });

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
                a1eb,
                { y: 0, opacity: 1, duration: 0.06, ease: "none" },
                "content1",
            )
            .to(
                a1avatar,
                { scale: 1, opacity: 1, duration: 0.12, ease: "power3.out" },
                "content1+=0.02",
            )
            .to(
                a1words,
                { y: "0%", duration: 0.28, stagger: 0.08, ease: "power3.out" },
                "content1+=0.08",
            )
            .to(
                a1role,
                { x: 0, opacity: 1, duration: 0.1, ease: "none" },
                "content1+=0.3",
            )
            .to(
                a1hl,
                { scaleX: 1, duration: 0.12, ease: "none" },
                "content1+=0.37",
            )
            .to(
                a1desc,
                { y: 0, opacity: 1, duration: 0.14, ease: "none" },
                "content1+=0.44",
            )
            .to(
                a1hexCenter,
                { scale: 1, opacity: 1, duration: 0.16, ease: "power3.out" },
                "content1+=0.14",
            )
            .to(
                a1hexItems,
                {
                    scale: 1,
                    opacity: 1,
                    duration: 0.12,
                    stagger: 0.05,
                    ease: "power3.out",
                },
                "content1+=0.27",
            )
            .to({}, { duration: 0.18 }) // Hold
            .to(a1c, { y: -52, opacity: 0, duration: 0.16, ease: "none" });
    }

    /* ACT 2 ── Seluruh Anggota Riset IDIG (flat roster, no leader)
       Pinned at 100vh like every other act. Sixteen rows don't fit a viewport, so they
       run through a fixed window: the first ROSTER_VISIBLE_ROWS animate in, then the
       track steps up exactly one row per scroll beat, the top row leaving as a new one
       arrives at the bottom. Same mechanism as AboutSection's capability list.

       The step is `100 / rowCount` percent of the TRACK's own height, which is why every
       row is locked to --roster-row in public.css — a content-sized row would drift the
       window a little further out of true on each beat. */
    const act2 = section.querySelector<HTMLElement>(".act-2");
    if (act2) {
        const a2i = setupChapterIntroState(act2);

        const a2c = act2.querySelector<HTMLElement>(".act-2-content")!;
        const a2eb = act2.querySelector(".act-2-eyebrow");
        const a2words = act2.querySelectorAll(".act-2-word");
        const a2hl = act2.querySelector(".act-2-hairline");
        const a2members = Array.from(act2.querySelectorAll(".act-2-member"));
        const a2track = act2.querySelector<HTMLElement>(".roster-track");
        const spine2 = act2.querySelector<SVGPathElement>(".act-2-connector");
        const a2gold = act2.querySelector<HTMLElement>("[data-gold]");
        const a2collageCenter = act2.querySelector(".act-2-collage-center");
        const a2collageItems = act2.querySelectorAll(".act-2-collage-item");

        gsap.set(a2eb, { y: 28, opacity: 0 });
        gsap.set(a2words, { y: "110%" });
        gsap.set(a2hl, { scaleX: 0 });
        gsap.set(a2gold, { scaleX: 0 });
        gsap.set(a2collageCenter, { scale: 0.8, opacity: 0 });
        gsap.set(a2collageItems, { y: 40, opacity: 0 });
        // Only the opening window animates in; everything below it is already visible
        // and simply rides up on the track.
        gsap.set(a2members.slice(0, ROSTER_VISIBLE_ROWS), {
            y: 28,
            opacity: 0,
        });
        if (spine2) {
            const len2 = spine2.getTotalLength();
            gsap.set(spine2, { strokeDasharray: len2, strokeDashoffset: len2 });
        }

        const steps = Math.max(0, a2members.length - ROSTER_VISIBLE_ROWS);

        const tl2 = gsap.timeline({
            scrollTrigger: {
                trigger: act2,
                start: "top top",
                // Each cycle step needs its own scroll distance on top of the intro.
                end: `+=${300 + steps * 50}%`,
                pin: true,
                scrub: 1,
                anticipatePin: 1,
            },
        });

        playChapterIntroDesktop(tl2, a2i)
            .to({}, { duration: 0.2 }) // Hold
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
            .add("content2", "transition2+=0.2")
            .to(
                a2eb,
                { y: 0, opacity: 1, duration: 0.06, ease: "none" },
                "content2",
            )
            .to(
                a2words,
                { y: "0%", duration: 0.28, stagger: 0.08, ease: "power3.out" },
                "content2+=0.08",
            )
            .to(
                a2hl,
                { scaleX: 1, duration: 0.12, ease: "none" },
                "content2+=0.3",
            )
            .to(
                a2collageCenter,
                { scale: 1, opacity: 1, duration: 0.16, ease: "power3.out" },
                "content2+=0.14",
            )
            .to(
                a2collageItems,
                {
                    y: 0,
                    opacity: 1,
                    duration: 0.12,
                    stagger: 0.05,
                    ease: "power3.out",
                },
                "content2+=0.2",
            )
            .to(
                spine2,
                { strokeDashoffset: 0, duration: 0.2, ease: "none" },
                "content2+=0.36",
            );

        /* The opening window enters progressively */
        a2members.slice(0, ROSTER_VISIBLE_ROWS).forEach((row, i) => {
            tl2.to(
                row,
                { y: 0, opacity: 1, duration: 0.1, ease: "none" },
                `content2+=${0.4 + i * 0.05}`,
            );
        });

        /* Cycle: one row per beat. yPercent is relative to the track's own height, so
           one row is 100 / a2members.length. */
        if (a2track && steps > 0) {
            for (let s = 1; s <= steps; s++) {
                tl2.to(
                    a2track,
                    {
                        yPercent: -(100 / a2members.length) * s,
                        duration: 0.3,
                        ease: "power2.inOut",
                    },
                    `content2+=${0.78 + (s - 1) * 0.36}`,
                );
            }
        }

        tl2.to(
            a2gold,
            { scaleX: 1, duration: 0.12, ease: "none" },
            `content2+=${0.78 + steps * 0.36}`,
        )
            .to({}, { duration: 0.15 })
            .to(a2c, { y: -52, opacity: 0, duration: 0.16, ease: "none" });
    }

    /* Dynamic acts (3+) — added via admin CMS.
       Starts at 3 because the static acts now end at 2. Getting this wrong is silent:
       the deleted act-3 handler above used querySelector(".act-3"), which would happily
       bind the first CMS-created act and pin it twice. */
    let dynActNum = 3;
    let dynAct = section.querySelector<HTMLElement>(`.act-${dynActNum}`);
    while (dynAct) {
        const n = dynActNum;
        const ani = setupChapterIntroState(dynAct);
        const isProfileRight = (n - 3) % 2 === 1;

        const eb = dynAct.querySelector(`.act-${n}-eyebrow`);
        const avatar = dynAct.querySelector(`.act-${n}-avatar`);
        const words = dynAct.querySelectorAll(`.act-${n}-word`);
        const role = dynAct.querySelector(`.act-${n}-role`);
        const hl = dynAct.querySelector(`.act-${n}-hairline`);
        const desc = dynAct.querySelector(`.act-${n}-desc`);
        const dynMembers = dynAct.querySelectorAll(`.act-${n}-member`);
        const spine = dynAct.querySelector<SVGPathElement>(
            `.act-${n}-connector`,
        );
        const collageCenter = dynAct.querySelector(`.act-${n}-collage-center`);
        const collageItems = dynAct.querySelectorAll(`.act-${n}-collage-item`);

        gsap.set(eb, { y: 28, opacity: 0 });
        gsap.set(avatar, { scale: 0.7, opacity: 0 });
        gsap.set(words, { y: "110%" });
        gsap.set(role, { x: isProfileRight ? 18 : -18, opacity: 0 });
        gsap.set(hl, { scaleX: 0 });
        gsap.set(desc, { y: 24, opacity: 0 });
        gsap.set(dynMembers, { y: 28, opacity: 0 });
        gsap.set(collageCenter, { scale: 0.8, opacity: 0 });
        gsap.set(collageItems, { y: 40, opacity: 0 });
        if (spine) {
            const len = spine.getTotalLength();
            gsap.set(spine, { strokeDasharray: len, strokeDashoffset: len });
        }

        const tlD = gsap.timeline({
            scrollTrigger: {
                trigger: dynAct,
                start: "top top",
                end: "+=300%",
                pin: true,
                scrub: 1,
                anticipatePin: 1,
            },
        });

        playChapterIntroDesktop(tlD, ani)
            .to({}, { duration: 0.2 })
            .add(`transitionD${n}`)
            .to(
                ani.intro,
                { yPercent: -100, duration: 0.5, ease: "power3.inOut" },
                `transitionD${n}`,
            )
            .to(
                ani.content,
                { yPercent: 0, duration: 0.5, ease: "power3.inOut" },
                `transitionD${n}`,
            )
            .add(`contentD${n}`, `transitionD${n}+=0.2`)
            .to(
                eb,
                { y: 0, opacity: 1, duration: 0.06, ease: "none" },
                `contentD${n}`,
            )
            .to(
                avatar,
                { scale: 1, opacity: 1, duration: 0.12, ease: "power3.out" },
                `contentD${n}+=0.02`,
            )
            .to(
                words,
                { y: "0%", duration: 0.28, stagger: 0.08, ease: "power3.out" },
                `contentD${n}+=0.08`,
            )
            .to(
                role,
                { x: 0, opacity: 1, duration: 0.1, ease: "none" },
                `contentD${n}+=0.3`,
            )
            .to(
                hl,
                { scaleX: 1, duration: 0.12, ease: "none" },
                `contentD${n}+=0.37`,
            )
            .to(
                desc,
                { y: 0, opacity: 1, duration: 0.1, ease: "none" },
                `contentD${n}+=0.42`,
            )
            .to(
                collageCenter,
                { scale: 1, opacity: 1, duration: 0.16, ease: "power3.out" },
                `contentD${n}+=0.14`,
            )
            .to(
                collageItems,
                {
                    y: 0,
                    opacity: 1,
                    duration: 0.12,
                    stagger: 0.05,
                    ease: "power3.out",
                },
                `contentD${n}+=0.2`,
            )
            .to(
                spine,
                { strokeDashoffset: 0, duration: 0.2, ease: "none" },
                `contentD${n}+=0.46`,
            )
            .to(
                dynMembers,
                {
                    y: 0,
                    opacity: 1,
                    duration: 0.08,
                    stagger: 0.045,
                    ease: "power3.out",
                },
                `contentD${n}+=0.5`,
            )
            .to({}, { duration: 0.1 });

        dynActNum++;
        dynAct = section.querySelector<HTMLElement>(`.act-${dynActNum}`);
    }
}

// ── Mobile / reduced-motion fallback ────────────────────────────────

function buildMobile(section: HTMLElement) {
    // Ensure connectors always render fully drawn on mobile
    section
        .querySelectorAll<SVGPathElement>("path[class*='-connector']")
        .forEach((connector) => {
            const len = connector.getTotalLength();
            gsap.set(connector, { strokeDasharray: len, strokeDashoffset: 0 });
        });

    if (prefersReducedMotion()) return; // Leave everything in natural visible state

    // Act 0 — Struktur Organisasi (mobile)
    const act0 = section.querySelector<HTMLElement>(".act-0");
    if (act0) {
        const introBlock0 = act0.querySelector<HTMLElement>(".chapter-intro");
        if (introBlock0) {
            revealChapterIntroOnScroll(introBlock0);
        }
    }

    // Discover all numbered acts dynamically (act-1, act-2, act-3, act-4, …)
    const actNums: string[] = [];
    section
        .querySelectorAll<HTMLElement>(".chapter-container")
        .forEach((el) => {
            for (const cls of Array.from(el.classList)) {
                if (/^act-\d+$/.test(cls) && cls !== "act-0") {
                    actNums.push(cls.slice(4));
                }
            }
        });

    actNums.forEach((n) => {
        const act = section.querySelector<HTMLElement>(`.act-${n}`);
        if (!act) return;

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

        const eyebrow = act.querySelector(`.act-${n}-eyebrow`);
        const avatar = act.querySelector(`.act-${n}-avatar`);
        const words = act.querySelectorAll(`.act-${n}-word`);
        const role = act.querySelector(`.act-${n}-role`);
        const hl = act.querySelector(`.act-${n}-hairline`);
        const desc = act.querySelector(`.act-${n}-desc`);
        const members = act.querySelectorAll(`.act-${n}-member`);
        const goldEl = act.querySelector<HTMLElement>("[data-gold]");
        const spine = act.querySelector<SVGPathElement>(
            "path[class*='-connector']",
        );

        const fadeEls = [
            eyebrow,
            avatar,
            ...Array.from(words),
            role,
            ...Array.from(members),
        ].filter(Boolean);
        if (desc) fadeEls.push(desc);

        gsap.set(fadeEls, { y: 28, opacity: 0 });
        if (hl) gsap.set(hl, { scaleX: 0 });
        if (goldEl) gsap.set(goldEl, { scaleX: 0 });

        gsap.to(fadeEls, {
            y: 0,
            opacity: 1,
            duration: 0.75,
            stagger: 0.07,
            ease: "power3.out",
            scrollTrigger: { trigger: act, start: "top 84%" },
        });

        if (hl) {
            gsap.to(hl, {
                scaleX: 1,
                duration: 0.55,
                ease: "power2.out",
                scrollTrigger: { trigger: act, start: "top 82%" },
            });
        }
        if (goldEl) {
            gsap.to(goldEl, {
                scaleX: 1,
                duration: 0.55,
                ease: "power2.out",
                scrollTrigger: { trigger: act, start: "top 72%" },
            });
        }
        if (spine) {
            const len = spine.getTotalLength();
            gsap.set(spine, { strokeDasharray: len, strokeDashoffset: len });
            gsap.to(spine, {
                strokeDashoffset: 0,
                duration: 1.1,
                ease: "power2.out",
                scrollTrigger: { trigger: act, start: "top 78%" },
            });
        }
    });
}

export function useOrganizationSectionAnimation(
    sectionRef: RefObject<HTMLElement | null>,
) {
    useGSAP(
        () => {
            if (!sectionRef.current) return;
            const section = sectionRef.current;

            const mm = gsap.matchMedia();

            mm.add(
                {
                    isDesktop: MEDIA_DESKTOP,
                    isMobile: MEDIA_MOBILE,
                },
                (ctx) => {
                    const { isDesktop } = ctx.conditions!;
                    isDesktop ? buildDesktop(section) : buildMobile(section);
                },
            );
        },
        { scope: sectionRef },
    );
}
