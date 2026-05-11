import { ECG_PATH } from "../../../Constants/sharingWisdomData";

/** Animated ECG line SVG with a travelling glowing pulse. */
export default function EcgLine() {
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
                <filter id="wgGlow" x="-300%" y="-300%" width="700%" height="700%">
                    <feGaussianBlur stdDeviation="5" result="b" />
                    <feMerge>
                        <feMergeNode in="b" />
                        <feMergeNode in="SourceGraphic" />
                    </feMerge>
                </filter>
                <filter id="wgHalo" x="-500%" y="-500%" width="1100%" height="1100%">
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

            {/* Travelling pulse — outer halo */}
            <circle r="16" fill="#22D3EE" opacity="0.10" filter="url(#wgHalo)">
                <animateMotion dur="4s" repeatCount="indefinite" path={ECG_PATH} />
            </circle>
            {/* Mid glow ring */}
            <circle r="7" fill="#22D3EE" opacity="0.40" filter="url(#wgGlow)">
                <animateMotion dur="4s" repeatCount="indefinite" path={ECG_PATH} />
            </circle>
            {/* Bright core */}
            <circle r="3.5" fill="#ffffff" opacity="0.95" filter="url(#wgGlow)">
                <animateMotion dur="4s" repeatCount="indefinite" path={ECG_PATH} />
            </circle>
        </svg>
    );
}
