import { useRef } from "react";
import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

/* ══════════════════════════════════════════════════════════════════
   CONTENT
   ══════════════════════════════════════════════════════════════════ */

const HEADING_WORDS = [
    { text: "We", accent: false },
    { text: "believe", accent: false },
    { text: "in", accent: false },
    { text: "the", accent: false },
    { text: "art", accent: false },
    { text: "of", accent: false },
    { text: "Sharing", accent: true },
    { text: "Wisdom.", accent: true },
];

const QUOTE_WORDS =
    "If you are planning for a year, sow rice; if you are planning for a decade, plant trees; if you are planning for a lifetime, educate people.".split(
        " ",
    );

const ECG_PATH =
    "M0,50 L180,50 L200,50 L215,20 L230,80 L248,28 L262,50 L420,50 L440,50 L455,18 L470,82 L488,24 L502,50 L700,50 L720,50 L735,22 L750,78 L768,26 L782,50 L1200,50";

/* ══════════════════════════════════════════════════════════════════
   SHARED SUB-COMPONENTS
   ══════════════════════════════════════════════════════════════════ */

function EcgLine() {
    return (
        <svg
            className="absolute left-0 right-0 top-1/2 -translate-y-1/2 w-full h-32 pointer-events-none"
            viewBox="0 0 1200 100"
            preserveAspectRatio="none"
            aria-hidden="true"
        >
            <defs>
                <linearGradient id="wgEcg" x1="0%" x2="100%">
                    <stop offset="0%" stopColor="#22D3EE" stopOpacity="0" />
                    <stop offset="25%" stopColor="#22D3EE" stopOpacity="0.85" />
                    <stop offset="75%" stopColor="#22D3EE" stopOpacity="0.85" />
                    <stop offset="100%" stopColor="#22D3EE" stopOpacity="0" />
                </linearGradient>
                <filter
                    id="wgGlow"
                    x="-300%"
                    y="-300%"
                    width="700%"
                    height="700%"
                >
                    <feGaussianBlur stdDeviation="5" result="b" />
                    <feMerge>
                        <feMergeNode in="b" />
                        <feMergeNode in="SourceGraphic" />
                    </feMerge>
                </filter>
                <filter
                    id="wgHalo"
                    x="-500%"
                    y="-500%"
                    width="1100%"
                    height="1100%"
                >
                    <feGaussianBlur stdDeviation="10" />
                </filter>
            </defs>

            <path
                className="ecg-path"
                d={ECG_PATH}
                stroke="url(#wgEcg)"
                strokeWidth="2"
                fill="none"
                strokeLinecap="round"
                strokeLinejoin="round"
            />

            {/* Traveling pulse — outer halo */}
            <circle r="16" fill="#22D3EE" opacity="0.10" filter="url(#wgHalo)">
                <animateMotion
                    dur="4s"
                    repeatCount="indefinite"
                    path={ECG_PATH}
                />
            </circle>
            {/* Mid glow ring */}
            <circle r="7" fill="#22D3EE" opacity="0.40" filter="url(#wgGlow)">
                <animateMotion
                    dur="4s"
                    repeatCount="indefinite"
                    path={ECG_PATH}
                />
            </circle>
            {/* Bright core */}
            <circle r="3.5" fill="#ffffff" opacity="0.95" filter="url(#wgGlow)">
                <animateMotion
                    dur="4s"
                    repeatCount="indefinite"
                    path={ECG_PATH}
                />
            </circle>
        </svg>
    );
}

/* ══════════════════════════════════════════════════════════════════
   MAIN COMPONENT
   ══════════════════════════════════════════════════════════════════ */

export default function SharingWisdom() {
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

    return (
        <section
            ref={sectionRef}
            id="sharing-wisdom"
            className="relative bg-primary-900"
        >
            {/* ═══════════════════════════════════════════════════
                ACT 1 — HEADING
                ECG runs. Heading words cascade in from below.
               ═══════════════════════════════════════════════════ */}
            <div className="wg-act-1 relative h-screen overflow-hidden">
                {/* Honeycomb texture */}
                <div className="absolute inset-0 honeycomb-dark opacity-[0.06] pointer-events-none" />

                {/* ECG line */}
                <EcgLine />

                {/* Ambient glow — left */}
                <div
                    className="wg-glow-1 absolute bottom-0 left-0 w-[60vw] h-[55vh] pointer-events-none"
                    aria-hidden="true"
                    style={{
                        background:
                            "radial-gradient(ellipse at 15% 90%, rgba(0,168,181,0.12) 0%, transparent 55%)",
                    }}
                />

                <div className="wg-act-content-1 relative z-10 h-full flex flex-col justify-center px-[clamp(24px,6vw,80px)]">
                    <div className="max-w-5xl mx-auto w-full">
                        {/* Chapter eyebrow */}
                        <div className="wg-label flex items-center gap-3 mb-10">
                            <div className="w-8 h-px bg-secondary-400/40" />
                            <span className="text-[0.68rem] font-body font-semibold tracking-[0.3em] uppercase text-secondary-400/70">
                                Berbagi Kebijaksanaan
                            </span>
                        </div>

                        {/* Heading — word-by-word overflow-hidden reveal */}
                        <h2
                            className="font-display font-extrabold leading-[1.1] tracking-tight"
                            style={{ fontSize: "clamp(2.4rem, 6vw, 4.5rem)" }}
                        >
                            {HEADING_WORDS.map((w, i) => (
                                <span
                                    key={i}
                                    className="inline-block mr-[0.22em]"
                                >
                                    <span className="inline-block overflow-hidden">
                                        <span
                                            className={`wg-hw inline-block ${
                                                w.accent
                                                    ? "text-secondary-400 italic"
                                                    : "text-white"
                                            }`}
                                        >
                                            {w.text}
                                        </span>
                                    </span>
                                </span>
                            ))}
                        </h2>

                        {/* Body text */}
                        <p
                            className="wg-body mt-8 font-body text-white/60 leading-[1.75]"
                            style={{
                                fontSize: "clamp(0.95rem, 1.4vw, 1.05rem)",
                                maxWidth: "48ch",
                            }}
                        >
                            Kami ingin berbagi ilmu dan fasilitas agar bisa
                            saling membantu. Kami telah menguji dan memvalidasi
                            konten-konten pada website ini.
                        </p>
                    </div>
                </div>

                {/* Act number */}
                <div className="absolute bottom-8 right-8 md:right-14 text-white/4 font-display font-extrabold text-[7rem] leading-none select-none pointer-events-none">
                    01
                </div>
            </div>

            {/* ═══════════════════════════════════════════════════
                ACT 2 — QUOTE + HEXAGON CASCADE
                Quote words dissolve in. Hexagons assemble from right.
               ═══════════════════════════════════════════════════ */}
            <div className="wg-act-2 relative h-screen overflow-hidden">
                {/* Hexagon cascade — right half, assembled via clip */}
                <div
                    className="wg-hex-panel absolute inset-y-0 right-0 w-3/5 honeycomb-dark pointer-events-none"
                    aria-hidden="true"
                    style={{ opacity: 0.22, clipPath: "inset(0 100% 0 0)" }}
                />

                {/* Ambient glow — right */}
                <div
                    className="absolute inset-y-0 right-0 w-1/2 pointer-events-none"
                    aria-hidden="true"
                    style={{
                        background:
                            "radial-gradient(ellipse 80% 80% at 85% 50%, rgba(0,66,109,0.45), transparent 70%)",
                    }}
                />

                <div className="wg-act-content-2 relative z-10 h-full flex flex-col justify-center px-[clamp(24px,6vw,80px)]">
                    <div className="max-w-6xl mx-auto w-full grid grid-cols-1 lg:grid-cols-[2fr_3fr] gap-12 lg:gap-20 items-center">
                        {/* Left placeholder — keeps grid anchored */}
                        <div />

                        {/* Right — Quote */}
                        <blockquote
                            className="relative rounded-2xl px-8 pt-12 pb-8"
                            style={{
                                background: "rgba(6, 46, 92, 0.45)",
                                border: "1px solid rgba(34, 211, 238, 0.18)",
                                boxShadow:
                                    "0 8px 48px rgba(3, 16, 38, 0.55), inset 0 1px 0 rgba(34, 211, 238, 0.10)",
                            }}
                        >
                            <span
                                className="absolute top-3 left-6 text-8xl text-secondary-400 font-serif leading-none select-none"
                                style={{ opacity: 0.8 }}
                                aria-hidden="true"
                            >
                                &ldquo;
                            </span>

                            {/* Quote text — words revealed individually */}
                            <p className="text-lg md:text-xl font-body italic text-white/90 leading-relaxed">
                                {QUOTE_WORDS.map((w, i) => (
                                    <span
                                        key={i}
                                        className="wg-qword inline-block mr-[0.28em] overflow-hidden"
                                    >
                                        <span className="inline-block">
                                            {w}
                                        </span>
                                    </span>
                                ))}
                            </p>

                            <div className="wg-attr mt-8 flex items-center gap-4">
                                <div
                                    className="w-12 h-12 rounded-full flex items-center justify-center shrink-0"
                                    style={{
                                        background: "#00426d",
                                        boxShadow:
                                            "0 0 0 2px rgba(34,211,238,0.45), 0 0 12px rgba(34,211,238,0.18)",
                                    }}
                                >
                                    <span className="text-secondary-400 font-bold text-sm font-body">
                                        DK
                                    </span>
                                </div>

                                <div>
                                    <p className="text-white font-semibold font-body text-sm">
                                        Djoko Kuswanto, S.T., M.Biotech.
                                    </p>
                                    <p className="text-xs text-secondary-400 font-body mt-0.5">
                                        Dosen Pengampu Mata Kuliah DTK
                                    </p>
                                </div>
                            </div>
                        </blockquote>
                    </div>

                    {/* CTA — fades in at end of Act 2 */}
                    <div className="wg-cta mt-14 flex justify-center">
                        <a
                            href="#categories"
                            className="inline-flex items-center gap-3 px-10 py-4 rounded-full border border-secondary-400/55 text-white font-body font-medium text-base hover:border-secondary-400 hover:bg-secondary-400/10 active:scale-95 transition-all duration-200"
                        >
                            Explore Our Innovations
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>

                {/* Act number */}
                <div className="absolute bottom-8 right-8 md:right-14 text-white/4 font-display font-extrabold text-[7rem] leading-none select-none pointer-events-none">
                    02
                </div>
            </div>
        </section>
    );
}

/* ══════════════════════════════════════════════════════════════════
   DESKTOP PINNED EXPERIENCE
   ══════════════════════════════════════════════════════════════════ */

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

/* ══════════════════════════════════════════════════════════════════
   MOBILE / REDUCED-MOTION FALLBACK
   ══════════════════════════════════════════════════════════════════ */

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
