import type { Capability } from "../../../Types/aboutSection.type";

interface CapabilityItemProps {
    cap: Capability;
    index: number;
}

/**
 * A single zigzag capability row.
 *
 * The desktop height is pinned to --cap-row (set on .cap-viewport) because the
 * cycling track steps by exactly one row — uneven rows would drift out of
 * alignment with the window. Mobile drops the fixed height and flows naturally.
 */
export default function CapabilityItem({ cap, index }: CapabilityItemProps) {
    const imageLeft = cap.imageSide === "left";

    return (
        <div className="cap-item anim-el flex flex-col md:flex-row md:items-center gap-6 md:gap-10 lg:gap-16 border-t border-[#F8FAFC]/6 py-8 md:py-0 md:h-[var(--cap-row)] first:border-t-0">
            {/* Text — order flips so the image lands on the requested side */}
            <div
                className={`flex-1 min-w-0 ${imageLeft ? "md:order-2" : "md:order-1"}`}
            >
                <div className="flex items-baseline gap-3 mb-2">
                    <span
                        className="cap-num font-display font-bold leading-none"
                        style={{
                            fontSize: "clamp(1.4rem, 2.2vw, 1.9rem)",
                            color: cap.accent,
                            opacity: 0.35,
                        }}
                    >
                        {String(index + 1).padStart(2, "0")}
                    </span>
                    <span className="text-[0.65rem] font-body font-semibold tracking-[0.2em] uppercase text-secondary-400/50">
                        {cap.tag}
                    </span>
                </div>

                <h4
                    className="font-display font-bold text-[#F8FAFC] leading-snug mb-2"
                    style={{ fontSize: "clamp(1.1rem, 1.9vw, 1.4rem)" }}
                >
                    {cap.title}
                </h4>
                <p
                    className="font-body text-[#94A3B8] leading-[1.6] md:line-clamp-3"
                    style={{
                        fontSize: "clamp(0.82rem, 1.1vw, 0.92rem)",
                        maxWidth: "52ch",
                    }}
                >
                    {cap.description}
                </p>
            </div>

            {/* Capability image — owns half the row; the circle keeps its own size */}
            <div
                className={`self-center flex justify-center md:flex-1 ${imageLeft ? "md:order-1" : "md:order-2"}`}
            >
                <div
                    className="relative overflow-hidden rounded-full"
                    style={{
                        width: "clamp(140px, 15vh, 176px)",
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
