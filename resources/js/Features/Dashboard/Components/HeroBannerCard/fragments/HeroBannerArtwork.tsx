interface HeroBannerArtworkProps {
    alt: string;
    "data-hero-artwork"?: string;
}

export default function HeroBannerArtwork(props: HeroBannerArtworkProps) {
    const { alt, ...rest } = props;
    return (
        <div
            {...rest}
            className="absolute right-0 top-1/2 -translate-y-1/2 w-5/12 max-w-xs pointer-events-none select-none"
            aria-hidden="true"
        >
            {/* Outer glow halo */}
            <div
                className="absolute inset-0 rounded-full blur-3xl opacity-30"
                style={{ background: "radial-gradient(circle, rgba(34,211,238,0.6) 0%, transparent 70%)" }}
            />
            {/* 3D-look geometric prosthetic placeholder SVG */}
            <svg viewBox="0 0 240 320" fill="none" xmlns="http://www.w3.org/2000/svg" className="w-full h-auto drop-shadow-2xl">
                {/* Upper arm segment */}
                <rect x="90" y="20" width="60" height="100" rx="30" fill="url(#arm-grad)" />
                {/* Elbow joint */}
                <ellipse cx="120" cy="125" rx="28" ry="18" fill="url(#joint-grad)" />
                {/* Forearm segment */}
                <rect x="92" y="130" width="56" height="85" rx="28" fill="url(#arm-grad)" />
                {/* Wrist */}
                <ellipse cx="120" cy="218" rx="24" ry="14" fill="url(#joint-grad)" />
                {/* Palm */}
                <rect x="96" y="226" width="48" height="50" rx="16" fill="url(#arm-grad)" />
                {/* Fingers */}
                <rect x="98" y="270" width="10" height="36" rx="5" fill="url(#finger-grad)" />
                <rect x="112" y="268" width="10" height="40" rx="5" fill="url(#finger-grad)" />
                <rect x="126" y="268" width="10" height="40" rx="5" fill="url(#finger-grad)" />
                <rect x="140" y="272" width="9" height="32" rx="4.5" fill="url(#finger-grad)" />
                {/* Thumb */}
                <rect x="76" y="238" width="8" height="28" rx="4" transform="rotate(-20 76 238)" fill="url(#finger-grad)" />
                {/* Highlight stripe */}
                <rect x="108" y="28" width="10" height="80" rx="5" fill="rgba(255,255,255,0.18)" />
                {/* Circuit-line decoration */}
                <path d="M108 140 L108 200 M132 140 L132 200 M108 170 L132 170" stroke="rgba(34,211,238,0.5)" strokeWidth="1.5" strokeLinecap="round"/>
                <defs>
                    <linearGradient id="arm-grad" x1="90" y1="20" x2="150" y2="340" gradientUnits="userSpaceOnUse">
                        <stop stopColor="#22D3EE" stopOpacity="0.9" />
                        <stop offset="1" stopColor="#00426D" stopOpacity="0.95" />
                    </linearGradient>
                    <linearGradient id="joint-grad" x1="90" y1="0" x2="150" y2="0" gradientUnits="userSpaceOnUse">
                        <stop stopColor="#A5F3FC" stopOpacity="0.9" />
                        <stop offset="1" stopColor="#00A8B5" stopOpacity="0.85" />
                    </linearGradient>
                    <linearGradient id="finger-grad" x1="0" y1="0" x2="0" y2="1" gradientUnits="objectBoundingBox">
                        <stop stopColor="#67E8F9" stopOpacity="0.95" />
                        <stop offset="1" stopColor="#0A3D7A" stopOpacity="0.9" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
    );
}
