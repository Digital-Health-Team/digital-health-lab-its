interface ChapterIntroProps {
    /** e.g. "01" or "02" */
    digitNum: string;
    /** Text for the large split-glyph animation, e.g. "THE VISION" */
    glyphText: string;
    /** Subtitle words for the parabolic reveal, e.g. "Strategic Alignment" */
    subText: string;
}

/**
 * Section-scoped chapter transition block used by AboutSection.
 * Renders the light (white + navy) chapter intro overlay with:
 *   – digit roulette counter
 *   – split-glyph heading
 *   – parabolic subtitle reveal
 */
export default function ChapterIntro({ digitNum, glyphText, subText }: ChapterIntroProps) {
    return (
        <div className="chapter-intro absolute inset-0 z-20 flex flex-col items-center justify-center bg-white text-primary-900">
            <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(0,168,181,0.03)_0%,transparent_70%)] pointer-events-none" />

            <div className="digit-roulette overflow-hidden h-[clamp(8rem,15vw,16rem)] text-[clamp(8rem,15vw,16rem)] leading-none font-display font-black text-primary-900/5 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                <div className="digit-strip flex flex-col">
                    <span>00</span>
                    <span>{digitNum}</span>
                </div>
            </div>

            <div className="split-glyph flex overflow-hidden text-[clamp(2rem,5vw,4.5rem)] font-display italic font-extrabold tracking-tight mt-8 relative z-10 text-center flex-wrap justify-center px-4">
                {glyphText.split("").map((char, i) => (
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
                {subText.split(" ").map((word, i) => (
                    <span key={i} className="inline-block parabolic-word">
                        {word}
                    </span>
                ))}
            </div>
        </div>
    );
}
