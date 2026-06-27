import { useRef } from "react";
import { Link } from "@inertiajs/react";
import Button from "@/Core/Components/Shared/Button/Button";
import { heroBannerData } from "@/Features/Dashboard/Data/heroBanner.data";
import { useHeroBannerAnimation } from "@/Features/Dashboard/Hooks/useHeroBannerAnimation";

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
            {/* Background Image */}
            <img
                src="/assets/images/hero/prosthetic_hand_banner.png"
                alt={imageAlt}
                className="absolute inset-0 w-full h-full object-cover object-[right_center] md:object-right pointer-events-none select-none z-0"
            />

            {/* Dark gradient overlay to ensure text contrast on the left */}
            <div
                className="absolute inset-0 bg-gradient-to-r from-[#031026] via-[#031026]/90 to-transparent z-0 pointer-events-none"
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
        </div>
    );
}
