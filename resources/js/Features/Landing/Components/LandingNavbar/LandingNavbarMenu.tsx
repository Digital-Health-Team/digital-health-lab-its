import { useEffect, useRef, useState } from "react";
import gsap from "gsap";
import { navItems } from "../../Data/landingNavbar.data";

interface LandingNavbarMenuProps {
    pillRect: DOMRect;
    onClose: () => void;
}

export default function LandingNavbarMenu({ pillRect, onClose }: LandingNavbarMenuProps) {
    const circleContainerRef = useRef<HTMLDivElement>(null);
    const displacementRef = useRef<SVGFEDisplacementMapElement>(null);
    const linkRowRefs = useRef<(HTMLDivElement | null)[]>([]);
    const linkInnerRefs = useRef<(HTMLDivElement | null)[]>([]);
    const closeButtonRef = useRef<HTMLButtonElement>(null);
    const hairlineRef = useRef<HTMLDivElement>(null);
    const tlRef = useRef<gsap.core.Timeline | null>(null);
    const isClosing = useRef(false);
    const [noiseSeed] = useState(() => Math.floor(Math.random() * 999));
    const [hoveredItem, setHoveredItem] = useState<number | null>(null);

    const pillCenterX = pillRect.left + pillRect.width / 2;
    const pillCenterY = pillRect.top + pillRect.height / 2;
    const maxRadius = Math.ceil(
        Math.hypot(
            Math.max(pillCenterX, window.innerWidth - pillCenterX),
            Math.max(pillCenterY, window.innerHeight - pillCenterY),
        ),
    ) + 100;

    // ─── Focus trap ───────────────────────────────────────────
    useEffect(() => {
        const handleKeyDown = (e: KeyboardEvent) => {
            if (e.key === "Escape") {
                handleClose();
                return;
            }
            if (e.key !== "Tab") return;

            const focusables = Array.from(
                document.querySelectorAll<HTMLElement>(
                    "#nav-overlay button, #nav-overlay a[href]",
                ),
            ).filter((el) => !el.closest("[aria-hidden='true']"));

            if (focusables.length === 0) return;
            const first = focusables[0];
            const last = focusables[focusables.length - 1];

            if (e.shiftKey) {
                if (document.activeElement === first) {
                    e.preventDefault();
                    last.focus();
                }
            } else {
                if (document.activeElement === last) {
                    e.preventDefault();
                    first.focus();
                }
            }
        };

        window.addEventListener("keydown", handleKeyDown);
        return () => window.removeEventListener("keydown", handleKeyDown);
    }, []);

    // ─── Mount animation ──────────────────────────────────────
    useEffect(() => {
        const container = circleContainerRef.current;
        const displacement = displacementRef.current;
        if (!container || !displacement) return;

        // Body scroll lock — preserve scrollbar gutter to avoid layout shift
        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        document.documentElement.style.overflow = "hidden";
        if (scrollbarWidth > 0) {
            document.documentElement.style.paddingRight = `${scrollbarWidth}px`;
        }

        // Initial positions
        gsap.set(container, {
            clipPath: `circle(0px at ${pillCenterX}px ${pillCenterY}px)`,
            autoAlpha: 1,
        });
        gsap.set(displacement, { attr: { scale: 20 } });

        // Push inner content below the overflow:hidden mask wrapper
        linkInnerRefs.current.forEach((inner) => {
            if (inner) gsap.set(inner, { y: 80 });
        });
        if (hairlineRef.current) {
            gsap.set(hairlineRef.current, { scaleX: 0, transformOrigin: "left center" });
        }

        // Focus close button
        requestAnimationFrame(() => closeButtonRef.current?.focus());

        const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

        if (reducedMotion) {
            gsap.set(container, {
                clipPath: `circle(${maxRadius}px at ${pillCenterX}px ${pillCenterY}px)`,
                autoAlpha: 1,
            });
            gsap.set(displacement, { attr: { scale: 0 } });
            linkInnerRefs.current.forEach((i) => i && gsap.set(i, { y: 0 }));
            if (hairlineRef.current) gsap.set(hairlineRef.current, { scaleX: 1 });
            return;
        }

        const tl = gsap.timeline();

        // Phase 1 — radial flood with liquid displacement edge
        tl.to(
            container,
            {
                clipPath: `circle(${maxRadius}px at ${pillCenterX}px ${pillCenterY}px)`,
                duration: 0.75,
                ease: "power3.out",
            },
        );

        // Displacement decays as the circle expands — liquid → crisp
        tl.to(
            displacement,
            { attr: { scale: 0 }, duration: 0.75, ease: "power2.out" },
            "<",
        );

        // Phase 2 — nav links slide up from behind overflow:hidden mask wrappers
        linkInnerRefs.current.forEach((inner, i) => {
            if (!inner) return;
            tl.to(
                inner,
                { y: 0, duration: 0.6, ease: "power3.out" },
                0.52 + i * 0.075,
            );
        });

        // Phase 3 — hairline scribe (institutional seam)
        if (hairlineRef.current) {
            tl.to(
                hairlineRef.current,
                { scaleX: 1, duration: 0.5, ease: "power3.out" },
                1.05,
            );
        }

        tlRef.current = tl;

        return () => {
            // Restore scroll when component unmounts (fallback)
            document.documentElement.style.overflow = "";
            document.documentElement.style.paddingRight = "";
        };
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    // ─── Hover handlers ───────────────────────────────────────
    const handleItemHover = (idx: number) => {
        setHoveredItem(idx);
        linkInnerRefs.current.forEach((inner, i) => {
            if (!inner) return;
            gsap.to(inner, { x: i === idx ? 20 : 0, duration: 0.5, ease: "power3.out" });
        });
    };

    const handleMenuLeave = () => {
        setHoveredItem(null);
        linkInnerRefs.current.forEach((inner) => {
            if (!inner) return;
            gsap.to(inner, { x: 0, duration: 0.4, ease: "power3.out" });
        });
    };

    // ─── Close handler ────────────────────────────────────────
    const handleClose = () => {
        if (isClosing.current) return;
        isClosing.current = true;

        const container = circleContainerRef.current;
        const displacement = displacementRef.current;

        const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

        const restore = () => {
            document.documentElement.style.overflow = "";
            document.documentElement.style.paddingRight = "";
            onClose();
        };

        if (reducedMotion || !container || !displacement) {
            restore();
            return;
        }

        const closeTl = gsap.timeline({ onComplete: restore });

        // Links drop back down beneath their overflow:hidden mask wrappers (top → bottom)
        // Reset x to 0 in case the user was hovering an item before closing
        linkInnerRefs.current.forEach((inner, i) => {
            if (!inner) return;
            closeTl.to(
                inner,
                { y: 80, x: 0, duration: 0.3, ease: "power3.in" },
                i * 0.04,
            );
        });

        // Re-engage displacement at the edge
        closeTl.to(
            displacement,
            { attr: { scale: 16 }, duration: 0.25, ease: "power2.in" },
            0.15,
        );

        // Circle contracts back to pill center
        closeTl.to(
            container,
            {
                clipPath: `circle(0px at ${pillCenterX}px ${pillCenterY}px)`,
                duration: 0.55,
                ease: "power3.in",
            },
            0.22,
        );
    };

    return (
        <>
            {/* SVG filter definitions — zero-size, hidden */}
            <svg
                aria-hidden="true"
                focusable="false"
                style={{
                    position: "absolute",
                    width: 0,
                    height: 0,
                    overflow: "hidden",
                    pointerEvents: "none",
                }}
            >
                <defs>
                    <filter
                        id="vault-displacement"
                        x="-30%"
                        y="-30%"
                        width="160%"
                        height="160%"
                        colorInterpolationFilters="sRGB"
                    >
                        <feTurbulence
                            type="turbulence"
                            baseFrequency="0.010"
                            numOctaves="3"
                            seed={noiseSeed}
                            result="noise"
                        />
                        <feDisplacementMap
                            ref={displacementRef}
                            in="SourceGraphic"
                            in2="noise"
                            scale={20}
                            xChannelSelector="R"
                            yChannelSelector="G"
                        />
                    </filter>
                </defs>
            </svg>

            {/*
             * Two-layer overlay structure:
             *   - Outer div: applies the displacement filter to the rendered output below
             *   - Inner div (circleContainerRef): gets the clip-path circle reveal
             *
             * This means the filter sees the clipped-circle output and displaces it,
             * making the expanding circle edge look organic/liquid rather than mathematically clean.
             * Nav links are invisible during the flood; by the time they appear, scale=0 (crisp).
             */}
            <div
                style={{
                    position: "fixed",
                    inset: 0,
                    zIndex: 100,
                    filter: "url(#vault-displacement)",
                    overflow: "visible",
                    pointerEvents: "none",
                }}
            >
                <div
                    id="nav-overlay"
                    ref={circleContainerRef}
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="nav-overlay-title"
                    style={{
                        position: "fixed",
                        inset: 0,
                        opacity: 0,
                        pointerEvents: "auto",
                    }}
                >
                    {/* Institute Navy backdrop */}
                    <div
                        style={{
                            position: "absolute",
                            inset: 0,
                            backgroundColor: "#062E5C",
                            display: "flex",
                            flexDirection: "column",
                        }}
                    >
                        {/* ITS Gold top hairline — institutional framing */}
                        <div style={{ width: "100%", height: 1, backgroundColor: "rgba(255,199,44,0.18)", flexShrink: 0 }} />

                        {/* Overlay interior */}
                        <div style={{ flex: 1, display: "flex", flexDirection: "column", justifyContent: "center", position: "relative", padding: "0 clamp(2rem, 8vw, 6rem)" }}>
                            {/* Visually hidden title for screen readers */}
                            <h2 id="nav-overlay-title" className="sr-only">Site navigation</h2>

                            {/* Close button */}
                            <button
                                ref={closeButtonRef}
                                onClick={handleClose}
                                aria-label="Close navigation"
                                style={{
                                    position: "absolute",
                                    top: "1.5rem",
                                    right: "clamp(2rem, 8vw, 6rem)",
                                    width: 48,
                                    height: 48,
                                    display: "flex",
                                    alignItems: "center",
                                    justifyContent: "center",
                                    color: "rgba(255,255,255,0.55)",
                                    borderRadius: "50%",
                                    border: "1px solid rgba(255,255,255,0.10)",
                                    background: "transparent",
                                    cursor: "pointer",
                                    transition: "color 0.2s, border-color 0.2s",
                                    outline: "none",
                                }}
                                onMouseEnter={(e) => {
                                    e.currentTarget.style.color = "rgba(255,255,255,0.95)";
                                    e.currentTarget.style.borderColor = "rgba(34,211,238,0.35)";
                                }}
                                onMouseLeave={(e) => {
                                    e.currentTarget.style.color = "rgba(255,255,255,0.55)";
                                    e.currentTarget.style.borderColor = "rgba(255,255,255,0.10)";
                                }}
                                onFocus={(e) => {
                                    e.currentTarget.style.borderColor = "rgba(34,211,238,0.60)";
                                    e.currentTarget.style.color = "rgba(255,255,255,0.95)";
                                }}
                                onBlur={(e) => {
                                    e.currentTarget.style.borderColor = "rgba(255,255,255,0.10)";
                                    e.currentTarget.style.color = "rgba(255,255,255,0.55)";
                                }}
                            >
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                    <path
                                        d="M3.5 3.5L14.5 14.5M14.5 3.5L3.5 14.5"
                                        stroke="currentColor"
                                        strokeWidth="1.5"
                                        strokeLinecap="round"
                                    />
                                </svg>
                            </button>

                            {/* Nav links */}
                            <nav aria-label="Main navigation">
                                <ul style={{ listStyle: "none", margin: 0, padding: 0 }} onMouseLeave={handleMenuLeave}>
                                    {navItems.map((item, idx) => (
                                        <li
                                            key={item.href}
                                            style={{ marginBottom: "0.4rem" }}
                                            onMouseEnter={() => handleItemHover(idx)}
                                        >
                                            {/* Overflow mask — clips the sliding inner content */}
                                            <div
                                                ref={(el) => { linkRowRefs.current[idx] = el; }}
                                                style={{
                                                    overflow: "hidden",
                                                    paddingBottom: "0.1em",
                                                    opacity: hoveredItem !== null && hoveredItem !== idx ? 0.12 : 1,
                                                    transition: "opacity 0.35s cubic-bezier(0.25,1,0.5,1)",
                                                }}
                                            >
                                            <div
                                                ref={(el) => { linkInnerRefs.current[idx] = el; }}
                                                style={{
                                                    display: "flex",
                                                    alignItems: "baseline",
                                                    gap: "1.25rem",
                                                }}
                                            >
                                                {/* Index number — activates ITS Gold on hover */}
                                                <span
                                                    aria-hidden="true"
                                                    style={{
                                                        fontFamily: "Inter, ui-sans-serif, system-ui, sans-serif",
                                                        fontSize: "0.7rem",
                                                        fontWeight: 500,
                                                        color: hoveredItem === idx ? "rgba(255,199,44,0.75)" : "rgba(148,163,184,0.5)",
                                                        letterSpacing: "0.12em",
                                                        width: "1.5rem",
                                                        textAlign: "right",
                                                        flexShrink: 0,
                                                        fontVariantNumeric: "tabular-nums",
                                                        transition: "color 0.3s cubic-bezier(0.25,1,0.5,1)",
                                                    }}
                                                >
                                                    {String(idx + 1).padStart(2, "0")}
                                                </span>

                                                {/* The link */}
                                                <a
                                                    href={item.href}
                                                    onClick={handleClose}
                                                    className="group"
                                                    style={{
                                                        position: "relative",
                                                        fontFamily: "'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif",
                                                        fontWeight: 700,
                                                        fontSize: "clamp(2.25rem, 6.5vw, 4.5rem)",
                                                        lineHeight: 1.12,
                                                        letterSpacing: "-0.025em",
                                                        color: "rgba(248,250,252,0.88)",
                                                        textDecoration: "none",
                                                        display: "inline-block",
                                                        outline: "none",
                                                        transition: "color 0.2s ease",
                                                    }}
                                                    onMouseEnter={(e) => {
                                                        e.currentTarget.style.color = "rgba(248,250,252,1)";
                                                    }}
                                                    onMouseLeave={(e) => {
                                                        e.currentTarget.style.color = "rgba(248,250,252,0.88)";
                                                    }}
                                                    onFocus={(e) => {
                                                        e.currentTarget.style.color = "rgba(248,250,252,1)";
                                                    }}
                                                    onBlur={(e) => {
                                                        e.currentTarget.style.color = "rgba(248,250,252,0.88)";
                                                    }}
                                                >
                                                    {item.label}
                                                    {/* Teal underline — scribes left→right, 2px with glow on hover */}
                                                    <span
                                                        aria-hidden="true"
                                                        style={{
                                                            position: "absolute",
                                                            bottom: -4,
                                                            left: 0,
                                                            right: 0,
                                                            height: 2,
                                                            backgroundColor: "#22D3EE",
                                                            transformOrigin: "left center",
                                                            transform: "scaleX(0)",
                                                            transition: "transform 0.5s cubic-bezier(0.25,1,0.5,1), box-shadow 0.3s ease",
                                                        }}
                                                        className="nav-menu-underline"
                                                    />
                                                </a>
                                            </div>
                                            </div>
                                        </li>
                                    ))}
                                </ul>
                            </nav>

                            {/* Institutional hairline seam — scribes in after all links settle */}
                            <div
                                ref={hairlineRef}
                                aria-hidden="true"
                                style={{
                                    position: "absolute",
                                    bottom: "4rem",
                                    left: 0,
                                    right: 0,
                                    height: 1,
                                    backgroundColor: "rgba(34,211,238,0.18)",
                                    transformOrigin: "left center",
                                }}
                            />
                        </div>

                        {/* Footer */}
                        <div style={{ padding: "1.5rem clamp(2rem, 8vw, 6rem) 2rem", flexShrink: 0 }}>
                            <p
                                style={{
                                    fontFamily: "Inter, ui-sans-serif, system-ui, sans-serif",
                                    fontSize: "0.7rem",
                                    fontWeight: 400,
                                    color: "rgba(148,163,184,0.35)",
                                    letterSpacing: "0.04em",
                                    margin: 0,
                                }}
                            >
                                iDIG Health Tech · Medical Technology Laboratory · ITS
                            </p>
                        </div>

                        {/* ITS Gold bottom hairline */}
                        <div style={{ width: "100%", height: 1, backgroundColor: "rgba(255,199,44,0.18)", flexShrink: 0 }} />
                    </div>
                </div>
            </div>

            {/* CSS for hover underline scribe — managed as global style since component is portaled */}
            <style>{`
                a:hover .nav-menu-underline,
                a:focus .nav-menu-underline {
                    transform: scaleX(1) !important;
                    box-shadow: 0 0 12px rgba(34,211,238,0.55), 0 0 24px rgba(34,211,238,0.2);
                }
            `}</style>
        </>
    );
}
