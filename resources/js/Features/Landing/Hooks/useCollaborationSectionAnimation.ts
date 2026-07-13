import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { RefObject } from "react";
import { MEDIA_DESKTOP, MEDIA_MOBILE } from "../Utils/breakpoints";
import { prefersReducedMotion } from "../Utils/motionPreferences";

gsap.registerPlugin(ScrollTrigger);

const HOLD = 0.8;

/* Rail node states (tweened at each chapter transition) */
const DOT_ON = {
    backgroundColor: "#22d3ee",
    borderColor: "#22d3ee",
    boxShadow: "0 0 12px rgba(34,211,238,0.45)",
    scale: 1.25,
};
const DOT_OFF = {
    backgroundColor: "#062e5c",
    borderColor: "rgba(255,255,255,0.35)",
    boxShadow: "0 0 0px rgba(34,211,238,0)",
    scale: 1,
};
const NUM_ON = { color: "rgba(165,243,252,0.95)" };
const NUM_OFF = { color: "rgba(255,255,255,0.55)" };

const textEls = (chapter: HTMLElement) =>
    chapter.querySelectorAll(".collab-chapter-text > *");
const centerEl = (chapter: HTMLElement) =>
    chapter.querySelector(".collab-print-center");
const itemEls = (chapter: HTMLElement) =>
    chapter.querySelectorAll(".collab-print-item");

/* ── Desktop: pinned cinema — chapters play as one scrubbed sequence ── */
function buildCinemaPin(section: HTMLElement): (() => void) | void {
    const stage = section.querySelector<HTMLElement>(".collab-stage");
    const chapters = gsap.utils.toArray<HTMLElement>(".collab-chapter", section);
    const ghosts = gsap.utils.toArray<HTMLElement>(".collab-ghost", section);
    const nodes = gsap.utils.toArray<HTMLElement>(".collab-rail-node", section);
    if (!stage || chapters.length === 0) return;

    // Intro reveal (normal flow, above the stage)
    gsap.from(".collab-intro > *", {
        opacity: 0,
        y: 20,
        duration: 0.8,
        stagger: 0.15,
        ease: "power3.out",
        scrollTrigger: { trigger: ".collab-intro", start: "top 85%" },
    });

    // Initial states: only chapter 1 exists on the stage at pin start
    chapters.slice(1).forEach((chapter) => {
        gsap.set(chapter, { autoAlpha: 0 });
        gsap.set(textEls(chapter), { autoAlpha: 0 });
        const center = centerEl(chapter);
        if (center) gsap.set(center, { autoAlpha: 0 });
        gsap.set(itemEls(chapter), { autoAlpha: 0 });
    });
    ghosts.slice(1).forEach((ghost) => gsap.set(ghost, { autoAlpha: 0 }));
    if (nodes[0]) {
        gsap.set(nodes[0].querySelector(".collab-rail-dot"), DOT_ON);
        gsap.set(nodes[0].querySelector(".collab-rail-num"), NUM_ON);
    }

    // Chapter 1 assembles as the stage scrolls into view, before the pin engages
    const first = chapters[0];
    const firstDir = first.dataset.align === "left" ? -1 : 1;
    const entranceTrigger = { trigger: stage, start: "top 80%" };
    gsap.from(textEls(first), {
        x: 60 * firstDir,
        autoAlpha: 0,
        duration: 1,
        stagger: 0.1,
        ease: "power3.out",
        scrollTrigger: entranceTrigger,
    });
    const firstCenter = centerEl(first);
    if (firstCenter) {
        gsap.from(firstCenter, {
            scale: 0.85,
            autoAlpha: 0,
            duration: 0.9,
            ease: "power3.out",
            delay: 0.15,
            scrollTrigger: entranceTrigger,
        });
    }
    gsap.from(itemEls(first), {
        y: 40,
        autoAlpha: 0,
        duration: 0.9,
        stagger: 0.08,
        ease: "power3.out",
        delay: 0.3,
        scrollTrigger: entranceTrigger,
    });

    // Master scrubbed timeline
    const tl = gsap.timeline({
        scrollTrigger: {
            trigger: stage,
            start: "top top",
            end: "+=340%",
            pin: true,
            scrub: 1,
            anticipatePin: 1,
        },
    });

    tl.addLabel("ch1", 0);
    tl.to({}, { duration: HOLD }, 0);

    chapters.forEach((chapter, i) => {
        if (i === chapters.length - 1) return;
        const next = chapters[i + 1];
        const dir = next.dataset.align === "left" ? -1 : 1;
        const t = `t${i + 1}`;
        tl.addLabel(t);

        // Outgoing: text drifts up-and-away, prints lift off desynced
        tl.to(
            textEls(chapter),
            { y: -44, autoAlpha: 0, duration: 0.38, stagger: 0.02, ease: "power1.in" },
            t,
        );
        const outCenter = centerEl(chapter);
        if (outCenter) {
            tl.to(
                outCenter,
                { y: -56, scale: 0.94, autoAlpha: 0, duration: 0.42, ease: "power1.in" },
                `${t}+=0.02`,
            );
        }
        tl.to(
            itemEls(chapter),
            { y: -72, autoAlpha: 0, duration: 0.4, stagger: 0.05, ease: "power1.in" },
            `${t}+=0.05`,
        );
        tl.set(chapter, { autoAlpha: 0 }, `${t}+=0.5`);

        // Incoming: text slides from its alignment side, prints settle upward
        tl.set(next, { autoAlpha: 1 }, `${t}+=0.48`);
        tl.fromTo(
            textEls(next),
            { x: 48 * dir, autoAlpha: 0 },
            { x: 0, autoAlpha: 1, duration: 0.5, stagger: 0.05, ease: "power2.out" },
            `${t}+=0.5`,
        );
        const inCenter = centerEl(next);
        if (inCenter) {
            tl.fromTo(
                inCenter,
                { y: 64, scale: 0.9, autoAlpha: 0 },
                { y: 0, scale: 1, autoAlpha: 1, duration: 0.5, ease: "power2.out" },
                `${t}+=0.52`,
            );
        }
        tl.fromTo(
            itemEls(next),
            { y: 80, autoAlpha: 0 },
            { y: 0, autoAlpha: 1, duration: 0.5, stagger: 0.06, ease: "power2.out" },
            `${t}+=0.56`,
        );

        // Ghost numeral crossfade
        if (ghosts[i] && ghosts[i + 1]) {
            tl.to(ghosts[i], { autoAlpha: 0, duration: 0.3, ease: "none" }, t);
            tl.to(ghosts[i + 1], { autoAlpha: 1, duration: 0.3, ease: "none" }, `${t}+=0.35`);
        }

        // Rail handoff
        if (nodes[i] && nodes[i + 1]) {
            tl.to(nodes[i].querySelector(".collab-rail-dot"), { ...DOT_OFF, duration: 0.25, ease: "none" }, t);
            tl.to(nodes[i].querySelector(".collab-rail-num"), { ...NUM_OFF, duration: 0.25, ease: "none" }, t);
            tl.to(nodes[i + 1].querySelector(".collab-rail-dot"), { ...DOT_ON, duration: 0.25, ease: "none" }, `${t}+=0.4`);
            tl.to(nodes[i + 1].querySelector(".collab-rail-num"), { ...NUM_ON, duration: 0.25, ease: "none" }, `${t}+=0.4`);
        }

        tl.addLabel(`ch${i + 2}`, `${t}+=1.2`);
        tl.to({}, { duration: HOLD }, `${t}+=1.2`);
    });

    // Settle beat before the unpin
    tl.to({}, { duration: 0.35 });

    // Rail progress fill scrubs across the whole sequence
    tl.fromTo(
        ".collab-rail-fill",
        { scaleY: 0 },
        { scaleY: 1, duration: tl.duration(), ease: "none" },
        0,
    );

    // Rail nodes jump to their chapter
    const st = tl.scrollTrigger!;
    const listeners = nodes.map((node, i) => {
        const onClick = () => {
            window.scrollTo({
                top: st.labelToScroll(`ch${i + 1}`) + 2,
                behavior: "smooth",
            });
        };
        node.addEventListener("click", onClick);
        return { node, onClick };
    });

    return () => {
        listeners.forEach(({ node, onClick }) =>
            node.removeEventListener("click", onClick),
        );
    };
}

/* ── Mobile / reduced-motion: stacked flow with per-chapter reveals ── */
function buildMobileFallback(section: HTMLElement) {
    const hasReducedMotion = prefersReducedMotion();

    gsap.from(".collab-intro > *", {
        opacity: 0,
        ...(hasReducedMotion ? {} : { y: 20 }),
        duration: hasReducedMotion ? 0.6 : 0.8,
        stagger: hasReducedMotion ? 0.1 : 0.15,
        ease: "power3.out",
        scrollTrigger: { trigger: ".collab-intro", start: "top 85%" },
    });

    const chapters = gsap.utils.toArray<HTMLElement>(".collab-chapter", section);

    chapters.forEach((chapter) => {
        if (hasReducedMotion) {
            gsap.from(chapter, {
                opacity: 0,
                duration: 0.8,
                scrollTrigger: { trigger: chapter, start: "top 85%" },
            });
            return;
        }

        const xOffset = chapter.dataset.align === "left" ? -60 : 60;

        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: chapter,
                start: "top 75%",
                toggleActions: "play none none reverse",
            },
        });

        tl.from(textEls(chapter), {
            opacity: 0,
            x: xOffset,
            duration: 1,
            stagger: 0.1,
            ease: "power3.out",
        });

        const center = centerEl(chapter);
        if (center) {
            tl.from(
                center,
                { opacity: 0, scale: 0.85, duration: 0.9, ease: "power3.out" },
                0.15,
            );
        }

        tl.from(
            itemEls(chapter),
            { opacity: 0, y: 40, duration: 0.9, stagger: 0.08, ease: "power3.out" },
            0.3,
        );
    });
}

export function useCollaborationSectionAnimation(
    containerRef: RefObject<HTMLElement | null>,
) {
    useGSAP(
        () => {
            if (!containerRef.current) return;
            const section = containerRef.current;
            const mm = gsap.matchMedia();

            mm.add(
                {
                    isDesktop: MEDIA_DESKTOP,
                    isMobile: MEDIA_MOBILE,
                },
                (ctx) => {
                    const { isDesktop } = ctx.conditions!;
                    if (isDesktop) return buildCinemaPin(section);
                    buildMobileFallback(section);
                },
            );
        },
        { scope: containerRef },
    );
}
