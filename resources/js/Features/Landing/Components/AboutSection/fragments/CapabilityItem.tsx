interface CapabilityItemProps {
    cap: {
        tag: string;
        title: string;
        description: string;
        accent: string;
        image: string;
        imageAlt: string;
    };
    index: number;
}

/** A single capability row in the About section's Act 2 list. */
export default function CapabilityItem({ cap, index }: CapabilityItemProps) {
    return (
        <div className="cap-item anim-el flex flex-col md:flex-row md:items-start gap-4 md:gap-10 border-t border-[#F8FAFC]/6 py-8 md:py-10 first:border-t-0 first:pt-0 md:pl-[clamp(56px,5vw,72px)]">
            {/* Number */}
            <span
                className="cap-num font-display font-bold leading-none shrink-0"
                style={{
                    fontSize: "clamp(2rem, 3.5vw, 2.8rem)",
                    color: cap.accent,
                    opacity: 0.35,
                    width: "clamp(40px, 5vw, 60px)",
                }}
            >
                {String(index + 1).padStart(2, "0")}
            </span>

            <div className="flex-1 min-w-0">
                <span className="text-[0.65rem] font-body font-semibold tracking-[0.2em] uppercase text-secondary-400/50 block mb-2">
                    {cap.tag}
                </span>
                <h4
                    className="font-display font-bold text-[#F8FAFC] leading-snug mb-3"
                    style={{ fontSize: "clamp(1.05rem, 1.8vw, 1.3rem)" }}
                >
                    {cap.title}
                </h4>
                <p
                    className="font-body text-[#94A3B8] leading-[1.7]"
                    style={{
                        fontSize: "clamp(0.85rem, 1.2vw, 0.94rem)",
                        maxWidth: "52ch",
                    }}
                >
                    {cap.description}
                </p>
            </div>

            {/* Capability image — desktop only */}
            <div className="hidden md:block shrink-0 self-center">
                <div
                    className="relative overflow-hidden rounded-full"
                    style={{
                        width: "clamp(80px, 8vw, 120px)",
                        aspectRatio: "1 / 1",
                        border: `2px solid ${cap.accent}50`,
                        boxShadow: `0 0 0 4px ${cap.accent}18`,
                    }}
                >
                    <img
                        src={cap.image}
                        alt={cap.imageAlt}
                        className="absolute inset-0 w-full h-full object-cover object-center"
                        loading="lazy"
                        draggable={false}
                    />
                    {/* Inner vignette */}
                    <div
                        className="absolute inset-0 pointer-events-none rounded-full"
                        style={{
                            boxShadow: "inset 0 0 18px rgba(3,16,38,0.5)",
                        }}
                    />
                </div>
            </div>
        </div>
    );
}
