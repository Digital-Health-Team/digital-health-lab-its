import { useRef } from "react";
import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { Button } from "@heroui/react";

gsap.registerPlugin(ScrollTrigger);

/** Thin precision-marker flanking the subtitle label */
function PrecisionMarker() {
    return (
        <div className="hero-label flex items-center gap-3 justify-center mb-6">
            <div className="flex items-center gap-1.5">
                <div className="w-8 h-px bg-white/25" />
                <div className="w-1 h-1 rounded-full bg-secondary-400/70" />
            </div>
            <span className="text-[11px] font-body font-medium tracking-[0.32em] uppercase text-white/40">
                Welcome to
            </span>
            <div className="flex items-center gap-1.5">
                <div className="w-1 h-1 rounded-full bg-secondary-400/70" />
                <div className="w-8 h-px bg-white/25" />
            </div>
        </div>
    );
}

/** Faint concentric lab-aperture ring — microscope / precision instrument reference */
function ApertureRing() {
    return (
        <div
            className="absolute right-[-12vw] top-1/2 -translate-y-[55%] pointer-events-none select-none"
            aria-hidden
        >
            <svg
                viewBox="0 0 520 520"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                className="w-[38vw] max-w-[560px] opacity-[0.055]"
            >
                <circle
                    cx="260"
                    cy="260"
                    r="255"
                    stroke="#22D3EE"
                    strokeWidth="1"
                />
                <circle
                    cx="260"
                    cy="260"
                    r="200"
                    stroke="#22D3EE"
                    strokeWidth="0.75"
                    strokeDasharray="4 8"
                />
                <circle
                    cx="260"
                    cy="260"
                    r="140"
                    stroke="#22D3EE"
                    strokeWidth="1"
                />
                <circle
                    cx="260"
                    cy="260"
                    r="80"
                    stroke="#22D3EE"
                    strokeWidth="0.75"
                    strokeDasharray="4 8"
                />
                <circle
                    cx="260"
                    cy="260"
                    r="20"
                    stroke="#22D3EE"
                    strokeWidth="1"
                />
                {/* Crosshair */}
                <line
                    x1="260"
                    y1="5"
                    x2="260"
                    y2="515"
                    stroke="#22D3EE"
                    strokeWidth="0.5"
                />
                <line
                    x1="5"
                    y1="260"
                    x2="515"
                    y2="260"
                    stroke="#22D3EE"
                    strokeWidth="0.5"
                />
                {/* Tick marks at cardinal points */}
                {[0, 90, 180, 270].map((deg) => {
                    const rad = (deg * Math.PI) / 180;
                    const x1 = 260 + 248 * Math.cos(rad);
                    const y1 = 260 + 248 * Math.sin(rad);
                    const x2 = 260 + 235 * Math.cos(rad);
                    const y2 = 260 + 235 * Math.sin(rad);
                    return (
                        <line
                            key={deg}
                            x1={x1}
                            y1={y1}
                            x2={x2}
                            y2={y2}
                            stroke="#22D3EE"
                            strokeWidth="2"
                        />
                    );
                })}
            </svg>
        </div>
    );
}

export default function HeroSection() {
    const heroRef = useRef<HTMLDivElement>(null);
    const bgRef = useRef<HTMLDivElement>(null);
    const hexRef = useRef<HTMLDivElement>(null);

    useGSAP(() => {
        const ctx = gsap.context(() => {
            const ease = "power4.out";

            // 1. Overlay fades in first — establishes the ground
            gsap.from(".hero-overlay", {
                opacity: 0,
                duration: 1.4,
                ease: "power2.out",
            });

            // 2. Precision marker label fades in quietly
            gsap.from(".hero-label", {
                opacity: 0,
                y: 16,
                duration: 0.7,
                ease,
                delay: 0.2,
            });

            // 3. "IDIG" surges up from below — the commanding entrance
            gsap.from(".hero-idig", {
                opacity: 0,
                y: 80,
                skewY: 2,
                duration: 1.1,
                ease,
                delay: 0.4,
            });

            // 4. "Laboratory" slides in from slight left offset, lagging behind IDIG
            gsap.from(".hero-lab", {
                opacity: 0,
                y: 50,
                x: -24,
                duration: 1.0,
                ease,
                delay: 0.65,
            });

            // 5. Separator line scales out from center
            gsap.from(".hero-separator", {
                scaleX: 0,
                opacity: 0,
                duration: 0.7,
                ease: "power2.out",
                delay: 0.9,
                transformOrigin: "center",
            });

            // 6. Description + CTA rise together
            gsap.from([".hero-desc", ".hero-cta"], {
                opacity: 0,
                y: 28,
                duration: 0.8,
                ease,
                stagger: 0.15,
                delay: 1.0,
            });

            // 7. Bottom bar slides up
            gsap.from(".hero-bottom", {
                opacity: 0,
                y: 20,
                duration: 0.6,
                ease,
                delay: 1.3,
            });

            // Parallax — background image (deeper travel)
            gsap.to(bgRef.current!, {
                y: -120,
                ease: "none",
                scrollTrigger: {
                    trigger: heroRef.current,
                    start: "top top",
                    end: "bottom top",
                    scrub: true,
                },
            });

            // Parallax — hex texture (subtle)
            gsap.to(hexRef.current!, {
                y: -50,
                ease: "none",
                scrollTrigger: {
                    trigger: heroRef.current,
                    start: "top top",
                    end: "bottom top",
                    scrub: true,
                },
            });
        }, heroRef);

        return () => ctx.revert();
    }, []);

    return (
        <section
            ref={heroRef}
            id="discover"
            className="relative h-screen min-h-[860px] w-full overflow-hidden flex items-center justify-center"
        >
            {/* ── Background photo (parallax target) ── */}
            <div
                ref={bgRef}
                className="absolute inset-0 scale-[1.15] bg-cover bg-center"
                style={{
                    backgroundImage:
                        "url('https://images.unsplash.com/photo-1606206873764-fd15e242df52?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')",
                    backgroundPosition: "center center",
                }}
            />

            {/* ── Layered overlays for dimensional depth ── */}
            <div className="hero-overlay absolute inset-0 bg-linear-to-b from-primary-900/30 via-primary-900/65 to-primary-950/95" />
            {/* Side vignette — darkens edges, brightens centre focus */}
            <div className="absolute inset-0 bg-[radial-gradient(ellipse_80%_70%_at_50%_40%,transparent_30%,rgba(3,16,38,0.55)_100%)]" />

            {/* ── Hex texture (parallax target) ── */}
            <div
                ref={hexRef}
                className="absolute inset-0 honeycomb-dark opacity-[0.12] pointer-events-none"
            />

            {/* ── Aperture precision ring ── */}
            <ApertureRing />

            {/* ── Main content ── */}
            <div className="relative z-10 flex flex-col items-center text-center px-6 pt-24 pb-40 w-full max-w-5xl mx-auto">
                {/* Whisper label */}
                <PrecisionMarker />

                {/* Title — two-line typographic architecture */}
                <h1 className="font-display italic leading-[0.9] tracking-[-0.035em] select-none">
                    <span
                        className="hero-idig block text-white"
                        style={{
                            fontSize: "clamp(5.5rem, 15vw, 11rem)",
                            fontWeight: 800,
                        }}
                    >
                        IDIG
                    </span>
                    <span
                        className="hero-lab block text-secondary-400"
                        style={{
                            fontSize: "clamp(3.2rem, 8.5vw, 6.5rem)",
                            fontWeight: 700,
                        }}
                    >
                        Laboratory
                    </span>
                </h1>

                {/* Separator */}
                <div className="hero-separator mt-8 mb-7 h-px w-24 bg-linear-to-r from-transparent via-white/30 to-transparent" />

                {/* Description */}
                <p
                    className="hero-desc font-body text-white/65 leading-relaxed"
                    style={{ fontSize: "1.05rem", maxWidth: "50ch" }}
                >
                    Repository dan publikasi inovasi rekayasa medis ITS. Dari
                    riset akademis hingga layanan cetak tiga dimensi presisi
                    tinggi.
                </p>

                {/* CTA */}
                <div className="hero-cta mt-9">
                    <Button
                        size="lg"
                        className="hero-btn-glow px-14 py-4 rounded-2xl bg-linear-to-b from-secondary-300/30 to-secondary-500/50 border border-secondary-200/50 text-white font-display font-semibold backdrop-blur-sm transition-all duration-500 h-auto cursor-pointer"
                        style={{ fontSize: "1.05rem", letterSpacing: "0.04em" }}
                    >
                        Telurusi Lebih Lanjut
                    </Button>
                </div>
            </div>

            {/* ── Institutional bottom bar ── */}
            <div className="hero-bottom absolute bottom-0 left-0 right-0 flex items-center justify-between px-10 md:px-14 pb-7 pt-4">
                <div className="flex items-center gap-2.5 text-white/25">
                    <div className="w-4 h-px bg-white/25" />
                    <span className="text-[10px] font-body font-medium tracking-[0.28em] uppercase">
                        ITS Medical Technology
                    </span>
                </div>

                <div className="flex items-center gap-2 text-white/25">
                    <span className="text-[10px] font-body font-medium tracking-[0.28em] uppercase">
                        Scroll
                    </span>
                    <div className="flex flex-col gap-0.5">
                        <div className="w-px h-4 bg-white/20 mx-auto" />
                        <div className="w-px h-2 bg-white/10 mx-auto" />
                    </div>
                </div>
            </div>
        </section>
    );
}
