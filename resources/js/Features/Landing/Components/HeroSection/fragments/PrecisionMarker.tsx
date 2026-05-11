import { heroData } from "../../../Constants/heroData";

/** Elegant glassmorphism eyebrow badge HUD element */
export default function PrecisionMarker() {
    return (
        <div className="hero-label mb-6">
            <div className="inline-flex items-center gap-3 px-4 py-1.5 rounded-full bg-white/3 backdrop-blur-md border border-white/10 shadow-[inset_0_1px_1px_rgba(255,255,255,0.05)]">
                <div className="w-1.5 h-1.5 rounded-full bg-secondary-400 shadow-[0_0_6px_rgba(34,211,238,0.6)]" />
                <span className="text-xs font-body font-bold tracking-[0.25em] uppercase text-white">
                    {heroData.badgeText}
                </span>
                <div className="w-1.5 h-1.5 rounded-full bg-secondary-400 shadow-[0_0_6px_rgba(34,211,238,0.6)]" />
            </div>
        </div>
    );
}
