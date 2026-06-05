import { useRef } from "react";
import { Link } from "@inertiajs/react";
import Button from "@/Core/Components/Shared/Button/Button";
import { heroBannerData } from "@/Features/Dashboard/Data/heroBanner.data";
import { useHeroBannerAnimation } from "@/Features/Dashboard/Hooks/useHeroBannerAnimation";
import HeroBannerArtwork from "./fragments/HeroBannerArtwork";

const glowCta = {
    boxShadow: "0 0 24px rgba(34,211,238,0.4), 0 0 40px rgba(34,211,238,0.3), inset 0 1px 0 rgba(255,255,255,0.3)",
};

export default function HeroBannerCard() {
    const containerRef = useRef<HTMLDivElement>(null);
    useHeroBannerAnimation(containerRef);

    const { eyebrow, title, titleBreak, body, ctaLabel, ctaHref, imageAlt } = heroBannerData;

    return (
        <div
            ref={containerRef}
            className="relative overflow-hidden rounded-3xl px-10 py-12 pr-0 min-h-[280px] flex items-center"
            style={{
                background: "linear-gradient(135deg, #031026 0%, #062E5C 55%, #0A3D7A 100%)",
            }}
        >
            {/* ECG line decoration */}
            <svg
                aria-hidden="true"
                className="absolute inset-0 w-full h-full pointer-events-none opacity-[0.12]"
                preserveAspectRatio="none"
            >
                <polyline
                    points="0,80 60,80 80,30 100,130 120,60 140,80 200,80 220,35 240,80 300,80 320,30 340,120 360,60 380,80 440,80 460,35 480,80 540,80"
                    fill="none"
                    stroke="#22D3EE"
                    strokeWidth="2"
                />
            </svg>

            {/* Honeycomb pattern overlay */}
            <div
                aria-hidden="true"
                className="absolute inset-0 pointer-events-none opacity-[0.06]"
                style={{
                    backgroundImage: `url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='56' height='100'%3E%3Cpath d='M28 66L0 50V18L28 2l28 16v32L28 66zm0-84l28 16v32L28 50 0 34V2L28-18z' fill='none' stroke='%2322D3EE' stroke-width='1'/%3E%3C/svg%3E")`,
                }}
            />

            {/* Content */}
            <div className="relative z-10 max-w-lg pr-8">
                <p data-hero-eyebrow className="text-secondary-400 text-xs font-semibold uppercase tracking-widest mb-3">
                    {eyebrow}
                </p>
                <h1 data-hero-title className="font-display text-3xl font-bold text-white leading-tight mb-4">
                    {title}
                    {titleBreak && (
                        <>
                            <br />
                            {titleBreak}
                        </>
                    )}
                </h1>
                <p data-hero-body className="text-sm text-slate-300 leading-relaxed mb-6 max-w-sm">
                    {body}
                </p>
                <div data-hero-cta>
                    <Link href={ctaHref}>
                        <Button variant="glow" size="md" style={glowCta}>
                            {ctaLabel}
                        </Button>
                    </Link>
                </div>
            </div>

            {/* Floating artwork */}
            <HeroBannerArtwork alt={imageAlt} data-hero-artwork="" />
        </div>
    );
}
