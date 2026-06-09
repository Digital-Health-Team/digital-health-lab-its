import { useRef } from "react";
import { Button } from "@heroui/react";
import { ArrowRight } from "lucide-react";
import { heroData } from "../../Data/heroSection.data";
import { useHeroSectionAnimation } from "../../Hooks/useHeroSectionAnimation";
import ApertureRing from "./fragments/ApertureRing";
import PrecisionMarker from "./fragments/PrecisionMarker";

export default function HeroSection() {
    const heroRef = useRef<HTMLDivElement>(null);
    const bgRef = useRef<HTMLDivElement>(null);
    const hexRef = useRef<HTMLDivElement>(null);

    useHeroSectionAnimation(heroRef, bgRef, hexRef);

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
                    backgroundImage: `url('${heroData.bgImageUrl}')`,
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

                {/* Title — logo mark */}
                <div className="hero-idig relative flex flex-col items-center select-none">
                    {/* Radial bloom — gives the logo a light-source feel against the dark overlay */}
                    <div
                        aria-hidden
                        className="absolute pointer-events-none"
                        style={{
                            inset: "-72px -96px",
                            background:
                                "radial-gradient(ellipse 65% 55% at 50% 54%, rgba(0,168,181,0.20) 0%, rgba(0,168,181,0.06) 50%, transparent 75%)",
                            filter: "blur(24px)",
                        }}
                    />

                    <img
                        src="/assets/images/logo_idig_htech_white.png"
                        alt={`${heroData.titleMain} ${heroData.titleSub}`}
                        draggable={false}
                        className="relative w-auto object-contain"
                        style={{
                            height: "clamp(6.5rem, 17vw, 12rem)",
                            filter:
                                "drop-shadow(0 0 18px rgba(0,168,181,0.65)) drop-shadow(0 0 56px rgba(0,168,181,0.22))",
                        }}
                    />
                </div>

                {/* Separator */}
                <div className="hero-separator mt-8 mb-7 h-px w-24 bg-linear-to-r from-transparent via-white/30 to-transparent" />

                {/* Description */}
                <p
                    className="hero-desc font-body text-white/65 leading-relaxed"
                    style={{ fontSize: "1.05rem", maxWidth: "50ch" }}
                >
                    {heroData.description}
                </p>

                {/* CTA */}
                <div className="hero-cta mt-9">
                    <Button
                        href="/dashboard"
                        size="lg"
                        className="group relative overflow-hidden px-14 py-4 rounded-full border-0 bg-transparent text-white font-display font-bold transition-all duration-500 h-auto cursor-pointer active:scale-95 before:absolute before:-inset-1 before:z-0 before:animate-[spin_4s_linear_infinite] before:blur-md before:opacity-80 group-hover:before:opacity-100 before:bg-[conic-gradient(from_0deg,var(--color-blue-500)_0deg,var(--color-yellow-400)_120deg,var(--color-blue-500)_240deg,var(--color-yellow-400)_360deg)] after:absolute after:inset-[2px] after:z-1 after:rounded-[inherit] after:backdrop-blur-xl after:bg-secondary-400/20 after:ring-1 after:ring-inset after:ring-white/20"
                        style={{ fontSize: "1.05rem", letterSpacing: "0.04em" }}
                    >
                        <span className="relative z-10 flex items-center gap-2">
                            {heroData.ctaText}
                            <ArrowRight className="w-5 h-5 transition-all duration-300 ease-[cubic-bezier(0.25,1,0.5,1)] group-hover:translate-x-1 group-hover:-rotate-12" />
                        </span>
                    </Button>
                </div>
            </div>

            {/* ── Institutional bottom bar ── */}
            <div className="hero-bottom absolute bottom-0 left-0 right-0 flex items-center justify-between px-10 md:px-14 pb-7 pt-4">
                <div className="flex items-center gap-2.5 text-white/25">
                    <div className="w-4 h-px bg-white/25" />
                    <span className="text-[10px] font-body font-medium tracking-[0.28em] uppercase">
                        {heroData.bottomBrandText}
                    </span>
                </div>

                <div className="flex items-center gap-2 text-white/25">
                    <span className="text-[10px] font-body font-medium tracking-[0.28em] uppercase">
                        {heroData.bottomScrollText}
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
