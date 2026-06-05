import gsap from "gsap";

export interface ChapterIntroElements {
    intro: HTMLElement;
    content: HTMLElement;
    digitStrip: HTMLElement;
    glyphs: NodeListOf<Element>;
    parabolic: NodeListOf<Element>;
}

export function setupChapterIntroState(chapter: HTMLElement): ChapterIntroElements {
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

export function playChapterIntroDesktop(
    tl: gsap.core.Timeline,
    els: { digitStrip: HTMLElement | null; glyphs: NodeListOf<Element>; parabolic: NodeListOf<Element> },
    baseTime = 0,
): gsap.core.Timeline {
    return tl
        .to(els.digitStrip, { yPercent: -50, duration: 0.4, ease: "power3.inOut" }, baseTime)
        .to(
            els.glyphs,
            { y: "0%", opacity: 1, rotateX: 0, duration: 0.4, stagger: 0.03, ease: "power3.out" },
            baseTime + 0.1,
        )
        .to(
            els.parabolic,
            { y: 0, opacity: 1, scale: 1, duration: 0.3, stagger: 0.06, ease: "power2.out" },
            baseTime + 0.25,
        );
}

export function revealChapterIntroOnScroll(introBlock: HTMLElement): void {
    const digitStrip = introBlock.querySelector<HTMLElement>(".digit-strip");
    const glyphs = introBlock.querySelectorAll(".glyph-char");
    const parabolic = introBlock.querySelectorAll(".parabolic-word");

    if (digitStrip)
        gsap.to(digitStrip, {
            yPercent: -50,
            duration: 1,
            ease: "power3.out",
            scrollTrigger: { trigger: introBlock, start: "top 80%" },
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
                scrollTrigger: { trigger: introBlock, start: "top 80%" },
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
                scrollTrigger: { trigger: introBlock, start: "top 80%" },
            },
        );
}
