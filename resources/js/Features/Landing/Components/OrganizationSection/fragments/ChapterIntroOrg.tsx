interface ChapterIntroOrgProps {
    digitNum: string;
    glyphText: string;
    subText: string;
}

export default function ChapterIntroOrg({ digitNum, glyphText, subText }: ChapterIntroOrgProps) {
    return (
        <div className="chapter-intro absolute inset-0 z-20 flex flex-col items-center justify-center bg-white text-primary-900">
            <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(0,168,181,0.03)_0%,transparent_70%)] pointer-events-none" />

            <div className="digit-roulette overflow-hidden h-[clamp(8rem,15vw,16rem)] text-[clamp(8rem,15vw,16rem)] leading-none font-display font-black text-primary-900/5 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                <div className="digit-strip flex flex-col">
                    <span>00</span>
                    <span>{digitNum}</span>
                </div>
            </div>

            <div className="split-glyph flex overflow-hidden text-[clamp(2rem,5vw,4.5rem)] font-display italic font-extrabold tracking-tight mt-8 relative z-10 text-center flex-wrap justify-center px-4 gap-x-[0.28em]">
                {glyphText.split(" ").map((word, wi) => (
                    <span key={wi} className="inline-flex shrink-0">
                        {word.split("").map((char, ci) => (
                            <span
                                key={ci}
                                className="glyph-char inline-block"
                                style={{ transformOrigin: "50% 100%" }}
                            >
                                {char}
                            </span>
                        ))}
                    </span>
                ))}
            </div>

            <div className="parabolic-text text-[clamp(0.85rem,1.2vw,1.1rem)] font-body uppercase tracking-[0.3em] font-semibold mt-6 text-primary-900/50 overflow-hidden relative z-10 flex flex-wrap gap-x-2 gap-y-1 justify-center px-4">
                {subText.split(" ").map((word, i) => (
                    <span key={i} className="inline-block parabolic-word">
                        {word}
                    </span>
                ))}
            </div>
        </div>
    );
}
