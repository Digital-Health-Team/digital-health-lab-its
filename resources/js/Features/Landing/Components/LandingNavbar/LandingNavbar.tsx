import { createPortal } from "react-dom";
import { navItems } from "../../Data/landingNavbar.data";
import { useLandingNavbar } from "../../Hooks/useLandingNavbar";
import LandingNavbarPill from "./LandingNavbarPill";
import LandingNavbarMenu from "./LandingNavbarMenu";

export default function LandingNavbar() {
    const {
        navState,
        menuOpen,
        pillRect,
        toggleMenu,
        closeMenu,
        containerRef,
        glassRef,
        heroContentRef,
        pillContentRef,
        pillButtonRef,
        hoveredIndex,
        setHoveredIndex,
        activeIndex,
        pillStyle,
        itemRefs,
    } = useLandingNavbar();

    return (
        <>
            <header className="fixed top-0 left-0 right-0 z-50 px-4 md:px-6 pt-5">
                <div ref={containerRef} className="relative mx-auto max-w-7xl">

                    {/*
                     * Layer 1 — The single glass shell.
                     * GSAP controls all dynamic inline styles (clip-path, background,
                     * border, shadow). No second glass element exists anywhere below.
                     */}
                    <div
                        ref={glassRef}
                        className="absolute inset-0 backdrop-blur-2xl border border-transparent rounded-full pointer-events-none"
                        aria-hidden="true"
                    />

                    {/*
                     * Layer 2 — Hero content (logo · nav · sign-in).
                     * This div establishes the container height.
                     * GSAP fades it to autoAlpha:0 when collapsing to pill state.
                     */}
                    <div
                        ref={heroContentRef}
                        className="relative flex items-center justify-between px-4 md:px-6 py-3"
                    >
                        {/* Logo */}
                        <div className="shrink-0">
                            <img
                                src="/assets/images/logo_idig_htech_white.png"
                                alt="iDIG Health Tech"
                                className="h-10 w-auto object-contain"
                                loading="eager"
                            />
                        </div>

                        {/*
                         * Nav links — plain flex container, NO background/border/shadow.
                         * The one glass surface lives on glassRef above. Links rest
                         * directly on that single glass layer.
                         */}
                        <nav
                            className="hidden md:flex items-center gap-0.5 relative"
                            aria-label="Main navigation"
                            onMouseLeave={() => setHoveredIndex(null)}
                        >
                            {/*
                             * Hover/active indicator — ITS Gold liquid glass pill.
                             * Intentional glass: a single purposeful accent on the active/hovered
                             * link, not decorative default glassmorphism.
                             */}
                            <div
                                className="absolute top-1 bottom-1 rounded-full pointer-events-none z-0 transition-all duration-300 ease-[cubic-bezier(0.25,1,0.5,1)]"
                                style={{
                                    left: pillStyle.left,
                                    width: pillStyle.width,
                                    opacity: pillStyle.opacity,
                                    background: "rgba(255,199,44,0.13)",
                                    border: "1px solid rgba(255,199,44,0.28)",
                                    boxShadow: "0 0 18px rgba(255,199,44,0.15), inset 0 1px 0 rgba(255,255,255,0.12)",
                                    backdropFilter: "blur(8px)",
                                }}
                                aria-hidden="true"
                            />

                            {navItems.map((item, idx) => {
                                const isActive = activeIndex === idx;
                                const isHovered = hoveredIndex === idx;
                                return (
                                    <a
                                        key={item.label}
                                        href={item.href}
                                        ref={(el) => { itemRefs.current[idx] = el; }}
                                        onMouseEnter={() => setHoveredIndex(idx)}
                                        className={`relative z-10 px-5 py-2 text-sm font-body rounded-full transition-all duration-300 ease-[cubic-bezier(0.25,1,0.5,1)] focus:outline-none focus-visible:ring-2 focus-visible:ring-secondary-400/60 ${
                                            isActive
                                                ? "font-semibold text-white"
                                                : isHovered
                                                    ? "font-medium text-white"
                                                    : "font-medium text-white/60"
                                        }`}
                                    >
                                        {item.label}
                                    </a>
                                );
                            })}
                        </nav>

                        {/* Sign In button — conic-gradient border shimmer */}
                        <div className="shrink-0">
                            <a
                                href="/login"
                                className="group relative overflow-hidden px-6 py-2.5 rounded-full font-body font-semibold text-sm flex items-center gap-1.5 text-white transition-all duration-500 ease-[cubic-bezier(0.25,1,0.5,1)] hover:scale-105 active:scale-95 before:absolute before:-inset-1 before:z-0 before:animate-[spin_4s_linear_infinite] before:blur-xs before:opacity-40 before:bg-[conic-gradient(from_0deg,var(--color-primary-600)_0deg,var(--color-accent-400)_120deg,var(--color-primary-600)_240deg,var(--color-accent-400)_360deg)] after:absolute after:inset-px after:z-1 after:rounded-[inherit] after:bg-primary-950/75 after:ring-1 after:ring-inset after:ring-white/15"
                            >
                                <span className="relative z-10 flex items-center gap-1.5">
                                    Masuk
                                    <svg
                                        className="w-3.5 h-3.5 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-rotate-12"
                                        viewBox="0 0 14 14"
                                        fill="none"
                                        aria-hidden="true"
                                    >
                                        <path
                                            d="M2 7H12M9 3.5L12 7L9 10.5"
                                            stroke="currentColor"
                                            strokeWidth="1.5"
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                        />
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>

                    {/*
                     * Layer 3 — Pill content (hamburger + "Menu").
                     * Positioned absolutely over the hero content. GSAP controls autoAlpha
                     * and scale. Starts invisible (opacity:0 inline style as initial state
                     * before GSAP takes over on first render).
                     */}
                    <div
                        ref={pillContentRef}
                        className="absolute inset-0 flex items-center justify-center pointer-events-none"
                        style={{ opacity: 0 }}
                        aria-hidden={navState !== "pill"}
                    >
                        <LandingNavbarPill
                            ref={pillButtonRef}
                            onToggle={toggleMenu}
                            isOpen={menuOpen}
                        />
                    </div>

                </div>
            </header>

            {/* Vault overlay — portal-mounted directly into body */}
            {menuOpen && pillRect
                ? createPortal(
                    <LandingNavbarMenu pillRect={pillRect} onClose={closeMenu} />,
                    document.body,
                )
                : null}
        </>
    );
}
