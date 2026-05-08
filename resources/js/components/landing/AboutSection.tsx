import { useRef } from "react";
import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

/* ══════════════════════════════════════════════════════════════════
   CONTENT DATA
   ══════════════════════════════════════════════════════════════════ */

const capabilities = [
    {
        tag: "3D Innovation",
        title: "Cetak Tiga Dimensi Presisi Tinggi",
        description:
            "Perancangan dan fabrikasi implan, prostetik, serta model anatomi menggunakan teknologi additive manufacturing dengan material biokompatibel.",
        accent: "#00A8B5",
    },
    {
        tag: "Custom Order",
        title: "Layanan Desain & Produksi Kustom",
        description:
            "Layanan berbasis pesanan untuk rumah sakit, klinik, dan institusi pendidikan. Dari konsep digital hingga produk fisik siap pakai.",
        accent: "#FFC72C",
    },
    {
        tag: "Digital Repository",
        title: "Repositori Publikasi Terpusat",
        description:
            "Sentralisasi jurnal, laporan riset, dan dokumentasi teknis dalam satu platform terbuka yang mendukung akses dan kolaborasi lintas disiplin.",
        accent: "#22D3EE",
    },
] as const;

/* Headline words for Act 1 word-by-word reveal */
const headlineWords: {
    word: string;
    accent: boolean;
    lineBreakAfter: boolean;
}[] = [
    { word: "Menjembatani", accent: false, lineBreakAfter: false },
    { word: "Inovasi", accent: false, lineBreakAfter: true },
    { word: "Kesehatan", accent: false, lineBreakAfter: false },
    { word: "dan", accent: false, lineBreakAfter: false },
    { word: "Rekayasa.", accent: true, lineBreakAfter: false },
];

/* ══════════════════════════════════════════════════════════════════
   MAIN ABOUT SECTION — SCROLL-PINNED CINEMATIC CHAPTERS

   Three acts, each pinned to viewport during scroll:
   Act 1: Vision — word-by-word headline reveal
   Act 2: Capabilities — scrubbed capability stack-up
   Act 3: Team — staggered member reveals

   Design decisions:
   – Dark ground state per DESIGN.md "Institute Midnight Rule"
   – Only opacity and transform animated (no layout properties)
   – prefers-reduced-motion: shows all content statically
   – Desktop: full pinned experience; Mobile: simple trigger animations
   ══════════════════════════════════════════════════════════════════ */

export default function AboutSection() {
    const sectionRef = useRef<HTMLElement>(null);

    useGSAP(
        () => {
            const section = sectionRef.current!;
            const mm = gsap.matchMedia();

            mm.add(
                {
                    isDesktop:
                        "(min-width: 768px) and (prefers-reduced-motion: no-preference)",
                    isMobile:
                        "(max-width: 767px) and (prefers-reduced-motion: no-preference)",
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

    return (
        <section
            ref={sectionRef}
            id="about"
            className="relative bg-primary-900"
        >
            {/* ═══════════════════════════════════════════════════
                ACT 1 — THE VISION
                Pinned full-viewport. Headline words cascade in.
               ═══════════════════════════════════════════════════ */}
            <div className="chapter-container act-1 relative h-screen w-full overflow-hidden">
                {/* Intro Block (White & Navy) */}
                <div className="chapter-intro absolute inset-0 z-20 flex flex-col items-center justify-center bg-white text-primary-900">
                    <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(0,168,181,0.03)_0%,transparent_70%)] pointer-events-none" />

                    <div className="digit-roulette overflow-hidden h-[clamp(8rem,15vw,16rem)] text-[clamp(8rem,15vw,16rem)] leading-none font-display font-black text-primary-900/5 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                        <div className="digit-strip flex flex-col">
                            <span>00</span>
                            <span>01</span>
                        </div>
                    </div>

                    <div className="split-glyph flex overflow-hidden text-[clamp(2rem,5vw,4.5rem)] font-display italic font-extrabold tracking-tight mt-8 relative z-10 text-center flex-wrap justify-center px-4">
                        {"THE VISION".split("").map((char, i) => (
                            <span
                                key={i}
                                className="glyph-char inline-block"
                                style={{ transformOrigin: "50% 100%" }}
                            >
                                {char === " " ? "\u00A0" : char}
                            </span>
                        ))}
                    </div>

                    <div className="parabolic-text text-[clamp(0.85rem,1.2vw,1.1rem)] font-body uppercase tracking-[0.3em] font-semibold mt-6 text-primary-900/50 overflow-hidden relative z-10 flex gap-2">
                        {"Strategic Alignment".split(" ").map((word, i) => (
                            <span
                                key={i}
                                className="inline-block parabolic-word"
                            >
                                {word}
                            </span>
                        ))}
                    </div>
                </div>

                {/* Content Block (Dark) */}
                <div className="chapter-content absolute inset-0 z-10 h-full w-full bg-primary-900">
                    {/* Background layers */}
                    <div className="absolute inset-0 honeycomb-dark opacity-[0.04] pointer-events-none" />
                    <div
                        className="act1-glow absolute bottom-0 left-0 w-[70vw] h-[60vh] pointer-events-none select-none"
                        aria-hidden="true"
                        style={{
                            background:
                                "radial-linear(ellipse at 20% 90%, rgba(0,168,181,0.1) 0%, transparent 55%)",
                        }}
                    />

                    {/* Content */}
                    <div className="act-content relative z-10 h-full flex flex-col justify-center px-[clamp(24px,5vw,48px)]">
                        <div className="max-w-5xl mx-auto w-full">
                            {/* Chapter marker */}
                            <div className="act1-label anim-el flex items-center gap-3 mb-10">
                                <div className="w-10 h-px bg-secondary-400/40" />
                                <span className="text-[0.68rem] font-body font-semibold tracking-[0.3em] uppercase text-secondary-400/70">
                                    Tentang Kami
                                </span>
                            </div>

                            {/* Headline — word-by-word reveal */}
                            <h2
                                className="font-display font-extrabold leading-[1.05] tracking-tight max-w-[18ch]"
                                style={{
                                    fontSize: "clamp(2.4rem, 6.5vw, 4.5rem)",
                                }}
                            >
                                {headlineWords.map((item, i) => (
                                    <span key={i}>
                                        <span className="inline-block overflow-hidden">
                                            <span
                                                className={`hw inline-block ${
                                                    item.accent
                                                        ? "text-secondary-400"
                                                        : "text-[#F8FAFC]"
                                                }`}
                                            >
                                                {item.word}
                                            </span>
                                        </span>
                                        {item.lineBreakAfter ? (
                                            <>
                                                {" "}
                                                <br className="hidden md:block" />
                                            </>
                                        ) : (
                                            " "
                                        )}
                                    </span>
                                ))}
                            </h2>

                            {/* Body text */}
                            <div className="act1-body anim-el mt-10 md:mt-14 flex flex-col gap-8 max-w-2xl">
                                <p
                                    className="font-body text-white leading-loose pl-5 border-l border-yellow-400"
                                    style={{
                                        fontSize:
                                            "clamp(0.95rem, 1.5vw, 1.08rem)",
                                    }}
                                >
                                    Laboratorium Teknologi Medis ITS berdiri
                                    sebagai pionir yang menjembatani dunia riset
                                    akademis multidisiplin dengan kebutuhan
                                    nyata pada sektor layanan kesehatan
                                    nasional. Kami berdedikasi penuh untuk
                                    menghadirkan berbagai solusi rekayasa
                                    biomedis yang inovatif, presisi, serta
                                    diproduksi dengan standar kualitas tinggi
                                    yang telah tervalidasi secara klinis,
                                    terdokumentasi secara komprehensif, dan siap
                                    untuk didistribusikan.
                                </p>
                                <p
                                    className="font-body text-white leading-loose pl-5 border-l border-yellow-400"
                                    style={{
                                        fontSize:
                                            "clamp(0.95rem, 1.5vw, 1.08rem)",
                                    }}
                                >
                                    Melalui sinergi kuat antara peneliti,
                                    praktisi medis, dan insinyur profesional,
                                    kami bertransformasi menjadi pusat unggulan
                                    dalam pengembangan prostetik, implan kustom,
                                    serta perangkat medis lainnya. Komitmen
                                    utama kami adalah mendobrak batas
                                    konvensional teknologi manufaktur medis demi
                                    meningkatkan kualitas hidup pasien serta
                                    mendorong kemandirian fasilitas kesehatan di
                                    seluruh Indonesia.
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* Act number — subtle chapter indicator */}
                    <div className="absolute bottom-8 right-8 md:right-12 text-[#F8FAFC]/6 font-display font-extrabold text-[6rem] md:text-[8rem] leading-none select-none pointer-events-none">
                        01
                    </div>
                </div>
            </div>

            {/* ═══════════════════════════════════════════════════
                ACT 2 — CAPABILITIES
                Pinned. Capabilities stack up as you scroll.
                Vertical spine grows between items.
               ═══════════════════════════════════════════════════ */}
            <div className="chapter-container act-2 relative h-screen w-full overflow-hidden">
                {/* Intro Block (White & Navy) */}
                <div className="chapter-intro absolute inset-0 z-20 flex flex-col items-center justify-center bg-white text-primary-900">
                    <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(0,168,181,0.03)_0%,transparent_70%)] pointer-events-none" />

                    <div className="digit-roulette overflow-hidden h-[clamp(8rem,15vw,16rem)] text-[clamp(8rem,15vw,16rem)] leading-none font-display font-black text-primary-900/5 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                        <div className="digit-strip flex flex-col">
                            <span>00</span>
                            <span>02</span>
                        </div>
                    </div>

                    <div className="split-glyph flex overflow-hidden text-[clamp(2rem,5vw,4.5rem)] font-display italic font-extrabold tracking-tight mt-8 relative z-10 text-center flex-wrap justify-center px-4">
                        {"CAPABILITIES".split("").map((char, i) => (
                            <span
                                key={i}
                                className="glyph-char inline-block"
                                style={{ transformOrigin: "50% 100%" }}
                            >
                                {char === " " ? "\u00A0" : char}
                            </span>
                        ))}
                    </div>

                    <div className="parabolic-text text-[clamp(0.85rem,1.2vw,1.1rem)] font-body uppercase tracking-[0.3em] font-semibold mt-6 text-primary-900/50 overflow-hidden relative z-10 flex gap-2">
                        {"Core Competencies".split(" ").map((word, i) => (
                            <span
                                key={i}
                                className="inline-block parabolic-word"
                            >
                                {word}
                            </span>
                        ))}
                    </div>
                </div>

                {/* Content Block (Dark) */}
                <div className="chapter-content absolute inset-0 z-10 h-full w-full bg-primary-900">
                    <div className="absolute inset-0 honeycomb-dark opacity-[0.04] pointer-events-none" />
                    <div
                        className="absolute top-0 right-0 w-[50vw] h-[50vh] pointer-events-none select-none"
                        aria-hidden="true"
                        style={{
                            background:
                                "radial-linear(ellipse at 80% 20%, rgba(34,211,238,0.06) 0%, transparent 55%)",
                        }}
                    />

                    <div className="act-content relative z-10 h-full flex flex-col justify-center px-[clamp(24px,5vw,48px)]">
                        <div className="max-w-5xl mx-auto w-full">
                            {/* Header */}
                            <div className="act2-header anim-el">
                                <span className="inline-block text-[0.65rem] font-body font-semibold tracking-[0.25em] uppercase text-secondary-400/60 mb-3">
                                    Kapabilitas
                                </span>
                                <h3
                                    className="font-display font-bold text-[#F8FAFC] leading-[1.12] tracking-tight"
                                    style={{
                                        fontSize:
                                            "clamp(1.6rem, 3.5vw, 2.4rem)",
                                    }}
                                >
                                    Tiga Pilar Keunggulan
                                </h3>
                            </div>

                            {/* Capability items with spine */}
                            <div className="mt-12 md:mt-16 relative">
                                {/* Vertical progress spine */}
                                <div
                                    className="cap-spine absolute left-[clamp(24px,3vw,36px)] top-0 bottom-0 w-px hidden md:block"
                                    style={{
                                        background:
                                            "linear-linear(to bottom, rgba(0,168,181,0.4), rgba(34,211,238,0.15) 50%, rgba(255,199,44,0.3))",
                                        transformOrigin: "top",
                                    }}
                                />

                                <div className="space-y-0">
                                    {capabilities.map((cap, i) => (
                                        <div
                                            key={cap.tag}
                                            className="cap-item anim-el flex flex-col md:flex-row md:items-start gap-4 md:gap-10 border-t border-[#F8FAFC]/6 py-8 md:py-10 first:border-t-0 first:pt-0 md:pl-[clamp(56px,5vw,72px)]"
                                        >
                                            {/* Number */}
                                            <span
                                                className="cap-num font-display font-bold leading-none shrink-0"
                                                style={{
                                                    fontSize:
                                                        "clamp(2rem, 3.5vw, 2.8rem)",
                                                    color: cap.accent,
                                                    opacity: 0.35,
                                                    width: "clamp(40px, 5vw, 60px)",
                                                }}
                                            >
                                                {String(i + 1).padStart(2, "0")}
                                            </span>

                                            <div className="flex-1 min-w-0">
                                                <span className="text-[0.65rem] font-body font-semibold tracking-[0.2em] uppercase text-secondary-400/50 block mb-2">
                                                    {cap.tag}
                                                </span>
                                                <h4
                                                    className="font-display font-bold text-[#F8FAFC] leading-snug mb-3"
                                                    style={{
                                                        fontSize:
                                                            "clamp(1.05rem, 1.8vw, 1.3rem)",
                                                    }}
                                                >
                                                    {cap.title}
                                                </h4>
                                                <p
                                                    className="font-body text-[#94A3B8] leading-[1.7]"
                                                    style={{
                                                        fontSize:
                                                            "clamp(0.85rem, 1.2vw, 0.94rem)",
                                                        maxWidth: "52ch",
                                                    }}
                                                >
                                                    {cap.description}
                                                </p>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>

                        <div className="absolute bottom-8 right-8 md:right-12 text-[#F8FAFC]/6 font-display font-extrabold text-[6rem] md:text-[8rem] leading-none select-none pointer-events-none">
                            02
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}

/* ══════════════════════════════════════════════════════════════════
   DESKTOP PINNED EXPERIENCE
   Each act pins to viewport and scrubs through a GSAP timeline.
   ══════════════════════════════════════════════════════════════════ */

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

function buildDesktopPinnedExperience(section: HTMLElement) {
    /* ── ACT 1: THE VISION ── */
    const act1 = section.querySelector<HTMLElement>(".act-1")!;
    const a1i = setupIntroState(act1);

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
    const a2i = setupIntroState(act2);

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

/* ══════════════════════════════════════════════════════════════════
   MOBILE ANIMATIONS (no pinning)
   Simple scroll-triggered fade-in for each act's content.
   ══════════════════════════════════════════════════════════════════ */

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

            // Animate intro elements
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
