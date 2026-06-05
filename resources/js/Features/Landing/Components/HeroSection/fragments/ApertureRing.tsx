/** Faint concentric lab-aperture ring — microscope / precision instrument reference */
export default function ApertureRing() {
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
                <circle cx="260" cy="260" r="255" stroke="#22D3EE" strokeWidth="1" />
                <circle cx="260" cy="260" r="200" stroke="#22D3EE" strokeWidth="0.75" strokeDasharray="4 8" />
                <circle cx="260" cy="260" r="140" stroke="#22D3EE" strokeWidth="1" />
                <circle cx="260" cy="260" r="80" stroke="#22D3EE" strokeWidth="0.75" strokeDasharray="4 8" />
                <circle cx="260" cy="260" r="20" stroke="#22D3EE" strokeWidth="1" />
                {/* Crosshair */}
                <line x1="260" y1="5" x2="260" y2="515" stroke="#22D3EE" strokeWidth="0.5" />
                <line x1="5" y1="260" x2="515" y2="260" stroke="#22D3EE" strokeWidth="0.5" />
                {/* Tick marks at cardinal points */}
                {[0, 90, 180, 270].map((deg) => {
                    const rad = (deg * Math.PI) / 180;
                    const x1 = 260 + 248 * Math.cos(rad);
                    const y1 = 260 + 248 * Math.sin(rad);
                    const x2 = 260 + 235 * Math.cos(rad);
                    const y2 = 260 + 235 * Math.sin(rad);
                    return (
                        <line key={deg} x1={x1} y1={y1} x2={x2} y2={y2} stroke="#22D3EE" strokeWidth="2" />
                    );
                })}
            </svg>
        </div>
    );
}
