import { Link } from "@inertiajs/react";
import { ElementType } from "react";

interface LeaderProfileBlockProps {
    /** Act class prefix used by useOrganizationSectionAnimation, e.g. "act-1" */
    actKey: string;
    align: "left" | "right";
    image?: string;
    full: string;
    display: string[];
    href?: string;
}

// Renders the avatar + italic display-name pair used by every act's leader
// profile. Wrapping in <Link> (vs. a static <div>) doesn't disturb the GSAP
// scroll choreography — querySelector(`.${actKey}-avatar` / `-word`) finds
// those elements through any ancestor tag.
export default function LeaderProfileBlock({
    actKey,
    align,
    image,
    full,
    display,
    href,
}: LeaderProfileBlockProps) {
    const isRight = align === "right";
    const Wrapper: ElementType = href ? Link : "div";
    const wrapperProps = href
        ? {
              href,
              "aria-label": full,
              className: `group flex flex-row items-center gap-6 mb-8 rounded-2xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary-500/50 focus-visible:ring-offset-4 focus-visible:ring-offset-surface-base${isRight ? " justify-end" : ""}`,
          }
        : {
              className: `flex flex-row items-center gap-6 mb-8${isRight ? " justify-end" : ""}`,
          };

    const avatar = (
        <div
            className={`${actKey}-avatar w-[clamp(4.4rem,12.8vw,10.1rem)] h-[clamp(4.4rem,12.8vw,10.1rem)] rounded-full bg-primary-700/[0.07] border border-primary-700/10 flex items-center justify-center shrink-0 overflow-hidden relative${href ? " transition-shadow duration-300 group-hover:shadow-glow-cyan" : ""}`}
        >
            <img
                src={image}
                alt={href ? "" : full}
                className="absolute inset-0 w-full h-full object-cover"
            />
        </div>
    );

    const name = (
        <div className="min-w-0">
            <h3
                className={`font-display font-extrabold italic leading-[0.92] tracking-[-0.02em] text-primary-900${href ? " transition-colors duration-300 group-hover:text-secondary-500" : ""}${isRight ? " text-right" : ""}`}
                style={{ fontSize: "clamp(2.4rem, 7vw, 5.5rem)" }}
                aria-label={href ? undefined : full}
            >
                {display.map((word, i) => (
                    <span
                        key={i}
                        className="block overflow-hidden pb-[0.3em] -mb-[0.3em] pt-[0.3em] -mt-[0.3em] px-[0.1em] -mx-[0.1em]"
                    >
                        <span className={`block ${actKey}-word`}>{word}</span>
                    </span>
                ))}
            </h3>
        </div>
    );

    return (
        <Wrapper {...wrapperProps}>
            {isRight ? (
                <>
                    {name}
                    {avatar}
                </>
            ) : (
                <>
                    {avatar}
                    {name}
                </>
            )}
        </Wrapper>
    );
}
