import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { RefObject } from "react";

gsap.registerPlugin(ScrollTrigger);

// ── Desktop pinned choreography ──────────────────────────────────────

function setupIntroState(chapter: HTMLElement) {
    const intro = chapter.querySelector<HTMLElement>(".chapter-intro")!;
    const content = chapter.querySelector<HTMLElement>(".chapter-content")!;
    const digitStrip = chapter.querySelector<HTMLElement>(".digit-strip")!;
    const glyphs = chapter.querySelectorAll(".glyph-char");
    const parabolic = chapter.querySelectorAll(".parabolic-word");

    gsap.set(digitStrip, { yPercent: 0 });
    gsap.set(glyphs, { y: "120%", opacity: 0, rotateX: -90 });
    gsap.set(parabolic, { y: 30, opacity: 0, scale: 0.9 });
    gsap.set(content, { yPercent: 100 });
    gsap.set(intro, { yPercent: 0 });

    return { intro, content, digitStrip, glyphs, parabolic };
}

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

        tl0.to(digitStrip, { yPercent: -50, duration: 0.4, ease: "power3.inOut" }, 0)
            .to(
                glyphs,
                {
                    y: "0%",
                    opacity: 1,
                    rotateX: 0,
                    duration: 0.4,
                    stagger: 0.03,
                    ease: "power3.out",
                },
                0.1,
            )
            .to(
                parabolic,
                {
                    y: 0,
                    opacity: 1,
                    scale: 1,
                    duration: 0.3,
                    stagger: 0.06,
                    ease: "power2.out",
                },
                0.25,
            )
            .to({}, { duration: 0.3 }); // Hold
    }

    /* ACT 1 ── Head of Laboratory */
    const act1 = section.querySelector<HTMLElement>(".act-1")!;
    if (act1) {
        const a1i = setupIntroState(act1);

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
        tl1.to(
            a1i.digitStrip,
            { yPercent: -50, duration: 0.4, ease: "power3.inOut" },
            0,
        )
            .to(
                a1i.glyphs,
                {
                    y: "0%",
                    opacity: 1,
                    rotateX: 0,
                    duration: 0.4,
                    stagger: 0.03,
                    ease: "power3.out",
                },
                0.1,
            )
            .to(
                a1i.parabolic,
                {
                    y: 0,
                    opacity: 1,
                    scale: 1,
                    duration: 0.3,
                    stagger: 0.06,
                    ease: "power2.out",
                },
                0.25,
            )
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
            .to(a1hl, { scaleX: 1, duration: 0.12, ease: "none" }, "content1+=0.37")
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

    /* ACT 2 ── IDIG HTECH */
    const act2 = section.querySelector<HTMLElement>(".act-2")!;
    if (act2) {
        const a2i = setupIntroState(act2);

        const a2c = act2.querySelector<HTMLElement>(".act-2-content")!;
        const a2eb = act2.querySelector(".act-2-eyebrow");
        const a2avatar = act2.querySelector(".act-2-avatar");
        const a2words = act2.querySelectorAll(".act-2-word");
        const a2role = act2.querySelector(".act-2-role");
        const a2hl = act2.querySelector(".act-2-hairline");
        const a2desc = act2.querySelector(".act-2-desc");
        const a2members = act2.querySelectorAll(".act-2-member");
        const spine2 = act2.querySelector<SVGPathElement>(".act-2-spine");
        const a2collageCenter = act2.querySelector(".act-2-collage-center");
        const a2collageItems = act2.querySelectorAll(".act-2-collage-item");

        gsap.set(a2eb, { y: 28, opacity: 0 });
        gsap.set(a2avatar, { scale: 0.7, opacity: 0 });
        gsap.set(a2words, { y: "110%" });
        gsap.set(a2role, { x: 18, opacity: 0 });
        gsap.set(a2hl, { scaleX: 0 });
        gsap.set(a2desc, { y: 24, opacity: 0 });
        gsap.set(a2members, { y: 28, opacity: 0 });
        gsap.set(a2collageCenter, { scale: 0.8, opacity: 0 });
        gsap.set(a2collageItems, { y: 40, opacity: 0 });
        if (spine2) {
            const len2 = spine2.getTotalLength();
            gsap.set(spine2, { strokeDasharray: len2, strokeDashoffset: len2 });
        }

        const tl2 = gsap.timeline({
            scrollTrigger: {
                trigger: act2,
                start: "top top",
                end: "+=300%",
                pin: true,
                scrub: 1,
                anticipatePin: 1,
            },
        });

        tl2.to(
            a2i.digitStrip,
            { yPercent: -50, duration: 0.4, ease: "power3.inOut" },
            0,
        )
            .to(
                a2i.glyphs,
                {
                    y: "0%",
                    opacity: 1,
                    rotateX: 0,
                    duration: 0.4,
                    stagger: 0.03,
                    ease: "power3.out",
                },
                0.1,
            )
            .to(
                a2i.parabolic,
                {
                    y: 0,
                    opacity: 1,
                    scale: 1,
                    duration: 0.3,
                    stagger: 0.06,
                    ease: "power2.out",
                },
                0.25,
            )
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
                a2avatar,
                { scale: 1, opacity: 1, duration: 0.12, ease: "power3.out" },
                "content2+=0.02",
            )
            .to(
                a2words,
                { y: "0%", duration: 0.28, stagger: 0.08, ease: "power3.out" },
                "content2+=0.08",
            )
            .to(
                a2role,
                { x: 0, opacity: 1, duration: 0.1, ease: "none" },
                "content2+=0.3",
            )
            .to(a2hl, { scaleX: 1, duration: 0.12, ease: "none" }, "content2+=0.37")
            .to(
                a2desc,
                { y: 0, opacity: 1, duration: 0.1, ease: "none" },
                "content2+=0.42",
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
                "content2+=0.46",
            )
            .to(
                a2members,
                {
                    y: 0,
                    opacity: 1,
                    duration: 0.08,
                    stagger: 0.045,
                    ease: "power3.out",
                },
                "content2+=0.5",
            )
            .to({}, { duration: 0.16 })
            .to(a2c, { y: -52, opacity: 0, duration: 0.16, ease: "none" });
    }

    /* ACT 3 ── IDIG RCMED */
    const act3 = section.querySelector<HTMLElement>(".act-3")!;
    if (act3) {
        const a3i = setupIntroState(act3);

        const a3eb = act3.querySelector(".act-3-eyebrow");
        const a3avatar = act3.querySelector(".act-3-avatar");
        const a3words = act3.querySelectorAll(".act-3-word");
        const a3role = act3.querySelector(".act-3-role");
        const a3hl = act3.querySelector(".act-3-hairline");
        const a3desc = act3.querySelector(".act-3-desc");
        const a3members = act3.querySelectorAll(".act-3-member");
        const spine3 = act3.querySelector<SVGPathElement>(".act-3-spine");
        const goldEl = act3.querySelector<HTMLElement>("[data-gold]");
        const a3collageCenter = act3.querySelector(".act-3-collage-center");
        const a3collageItems = act3.querySelectorAll(".act-3-collage-item");

        gsap.set(a3eb, { y: 28, opacity: 0 });
        gsap.set(a3avatar, { scale: 0.7, opacity: 0 });
        gsap.set(a3words, { y: "110%" });
        gsap.set(a3role, { x: -18, opacity: 0 });
        gsap.set(a3hl, { scaleX: 0 });
        gsap.set(a3desc, { y: 24, opacity: 0 });
        gsap.set(a3members, { y: 28, opacity: 0 });
        gsap.set(a3collageCenter, { scale: 0.8, opacity: 0 });
        gsap.set(a3collageItems, { y: 40, opacity: 0 });
        gsap.set(goldEl, { scaleX: 0 });
        if (spine3) {
            const len3 = spine3.getTotalLength();
            gsap.set(spine3, { strokeDasharray: len3, strokeDashoffset: len3 });
        }

        const tl3 = gsap.timeline({
            scrollTrigger: {
                trigger: act3,
                start: "top top",
                end: "+=300%",
                pin: true,
                scrub: 1,
                anticipatePin: 1,
            },
        });

        tl3.to(
            a3i.digitStrip,
            { yPercent: -50, duration: 0.4, ease: "power3.inOut" },
            0,
        )
            .to(
                a3i.glyphs,
                {
                    y: "0%",
                    opacity: 1,
                    rotateX: 0,
                    duration: 0.4,
                    stagger: 0.03,
                    ease: "power3.out",
                },
                0.1,
            )
            .to(
                a3i.parabolic,
                {
                    y: 0,
                    opacity: 1,
                    scale: 1,
                    duration: 0.3,
                    stagger: 0.06,
                    ease: "power2.out",
                },
                0.25,
            )
            .to({}, { duration: 0.2 }) // Hold
            .add("transition3")
            .to(
                a3i.intro,
                { yPercent: -100, duration: 0.5, ease: "power3.inOut" },
                "transition3",
            )
            .to(
                a3i.content,
                { yPercent: 0, duration: 0.5, ease: "power3.inOut" },
                "transition3",
            )
            .add("content3", "transition3+=0.2")
            .to(
                a3eb,
                { y: 0, opacity: 1, duration: 0.06, ease: "none" },
                "content3",
            )
            .to(
                a3avatar,
                { scale: 1, opacity: 1, duration: 0.12, ease: "power3.out" },
                "content3+=0.02",
            )
            .to(
                a3words,
                { y: "0%", duration: 0.28, stagger: 0.08, ease: "power3.out" },
                "content3+=0.08",
            )
            .to(
                a3role,
                { x: 0, opacity: 1, duration: 0.1, ease: "none" },
                "content3+=0.3",
            )
            .to(a3hl, { scaleX: 1, duration: 0.12, ease: "none" }, "content3+=0.37")
            .to(
                a3desc,
                { y: 0, opacity: 1, duration: 0.1, ease: "none" },
                "content3+=0.42",
            )
            .to(
                a3collageCenter,
                { scale: 1, opacity: 1, duration: 0.16, ease: "power3.out" },
                "content3+=0.14",
            )
            .to(
                a3collageItems,
                {
                    y: 0,
                    opacity: 1,
                    duration: 0.12,
                    stagger: 0.05,
                    ease: "power3.out",
                },
                "content3+=0.2",
            )
            .to(
                spine3,
                { strokeDashoffset: 0, duration: 0.2, ease: "none" },
                "content3+=0.46",
            )
            .to(
                a3members,
                {
                    y: 0,
                    opacity: 1,
                    duration: 0.08,
                    stagger: 0.045,
                    ease: "power3.out",
                },
                "content3+=0.5",
            )
            .to(
                goldEl,
                { scaleX: 1, duration: 0.14, ease: "none" },
                "content3+=0.88",
            )
            .to({}, { duration: 0.08 });
    }
}

// ── Mobile / reduced-motion fallback ────────────────────────────────

function buildMobile(section: HTMLElement) {
    const prefersReduced = window.matchMedia(
        "(prefers-reduced-motion: reduce)",
    ).matches;

    // Ensure spines always render fully drawn
    section
        .querySelectorAll<SVGPathElement>("path[class*='-spine']")
        .forEach((spine) => {
            const len = spine.getTotalLength();
            gsap.set(spine, { strokeDasharray: len, strokeDashoffset: 0 });
        });

    if (prefersReduced) return; // Leave everything in natural visible state

    // Act 0 — Struktur Organisasi (mobile)
    const act0 = section.querySelector<HTMLElement>(".act-0");
    if (act0) {
        const introBlock0 = act0.querySelector<HTMLElement>(".chapter-intro");
        if (introBlock0) {
            const digitStrip = introBlock0.querySelector<HTMLElement>(".digit-strip");
            const glyphs = introBlock0.querySelectorAll(".glyph-char");
            const parabolic = introBlock0.querySelectorAll(".parabolic-word");

            if (digitStrip)
                gsap.to(digitStrip, {
                    yPercent: -50,
                    duration: 1,
                    ease: "power3.out",
                    scrollTrigger: { trigger: introBlock0, start: "top 80%" },
                });
            if (glyphs.length)
                gsap.fromTo(
                    glyphs,
                    { y: "120%", opacity: 0, rotateX: -90 },
                    {
                        y: "0%",
                        opacity: 1,
                        rotateX: 0,
                        duration: 0.8,
                        stagger: 0.05,
                        ease: "power3.out",
                        scrollTrigger: { trigger: introBlock0, start: "top 80%" },
                    },
                );
            if (parabolic.length)
                gsap.fromTo(
                    parabolic,
                    { y: 30, opacity: 0 },
                    {
                        y: 0,
                        opacity: 1,
                        duration: 0.8,
                        stagger: 0.1,
                        ease: "power3.out",
                        scrollTrigger: { trigger: introBlock0, start: "top 80%" },
                    },
                );
        }
    }

    (["1", "2", "3"] as const).forEach((n) => {
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

            // We can also animate the intro elements
            const digitStrip =
                introBlock.querySelector<HTMLElement>(".digit-strip");
            const glyphs = introBlock.querySelectorAll(".glyph-char");
            const parabolic = introBlock.querySelectorAll(".parabolic-word");

            if (digitStrip)
                gsap.to(digitStrip, {
                    yPercent: -50,
                    duration: 1,
                    ease: "power3.out",
                    scrollTrigger: { trigger: introBlock, start: "top 80%" },
                });
            if (glyphs)
                gsap.fromTo(
                    glyphs,
                    { y: "120%", opacity: 0, rotateX: -90 },
                    {
                        y: "0%",
                        opacity: 1,
                        rotateX: 0,
                        duration: 0.8,
                        stagger: 0.05,
                        ease: "power3.out",
                        scrollTrigger: {
                            trigger: introBlock,
                            start: "top 80%",
                        },
                    },
                );
            if (parabolic)
                gsap.fromTo(
                    parabolic,
                    { y: 30, opacity: 0 },
                    {
                        y: 0,
                        opacity: 1,
                        duration: 0.8,
                        stagger: 0.1,
                        ease: "power3.out",
                        scrollTrigger: {
                            trigger: introBlock,
                            start: "top 80%",
                        },
                    },
                );
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
            "path[class*='-spine']",
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

export function useOrganizationSection(sectionRef: RefObject<HTMLElement | null>) {
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
                    isDesktop ? buildDesktop(section) : buildMobile(section);
                },
            );
        },
        { scope: sectionRef },
    );
}
