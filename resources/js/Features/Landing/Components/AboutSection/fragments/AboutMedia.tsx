import type { CSSProperties } from "react";
import type { AboutMediaItem } from "../../../Types/aboutSection.type";

interface AboutMediaProps {
    items: AboutMediaItem[];
}

/**
 * Act 1's image stack — circles falling down the right-hand column.
 *
 * Overlap is DOM order, not z-index: each circle paints over the one before it.
 * The outer ring is the section background colour, so it cuts a clean gap into
 * the circle underneath and the overlap reads as depth instead of two merged
 * blobs. Both the vertical bite and the horizontal stagger are fractions of
 * --about-media, so the composition holds its proportions at every size.
 *
 * Hidden between md and lg: that range is pinned to a single viewport and the
 * vision copy already fills it, so there is no right-hand column to occupy.
 * Below md the act scrolls normally and the stack sits under the text.
 */
export default function AboutMedia({ items }: AboutMediaProps) {
    return (
        <div
            className="act1-media anim-el flex md:hidden lg:flex flex-col items-start"
            style={
                {
                    "--about-media": "clamp(168px, 26vh, 268px)",
                } as CSSProperties
            }
        >
            {items.map((item, i) => (
                <div
                    key={item.image}
                    className="relative overflow-hidden rounded-full"
                    style={{
                        width: "var(--about-media)",
                        aspectRatio: "1 / 1",
                        marginTop:
                            i === 0
                                ? undefined
                                : "calc(var(--about-media) * -0.26)",
                        marginLeft:
                            i === 0
                                ? undefined
                                : "calc(var(--about-media) * 0.24)",
                        border: `2px solid ${item.accent}59`,
                        boxShadow: `0 0 0 5px ${item.accent}1F, 0 0 0 12px #062E5C`,
                    }}
                >
                    {/* Native <img>: Core's <Image> preloads via JS, which would defeat
                        loading="lazy" on a still this far below the fold. */}
                    <img
                        src={item.image}
                        alt={item.alt}
                        className="absolute inset-0 w-full h-full object-cover object-center"
                        loading="lazy"
                        draggable={false}
                    />
                    {/* Inner vignette — same treatment as the capability circles. */}
                    <div
                        className="absolute inset-0 pointer-events-none rounded-full"
                        style={{ boxShadow: "inset 0 0 24px rgba(3,16,38,0.55)" }}
                    />
                </div>
            ))}
        </div>
    );
}
