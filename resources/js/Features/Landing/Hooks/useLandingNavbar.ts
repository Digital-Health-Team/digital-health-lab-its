import { useState, useEffect, useRef } from "react";
import gsap from "gsap";
import { navItems } from "../Data/landingNavbar.data";
import type { NavbarState } from "../Types/landingNavbar.type";

export function useLandingNavbar() {
    // ─── DOM refs ────────────────────────────────────────────
    const containerRef = useRef<HTMLDivElement>(null);
    const glassRef = useRef<HTMLDivElement>(null);
    const heroContentRef = useRef<HTMLDivElement>(null);
    const pillContentRef = useRef<HTMLDivElement>(null);
    const pillButtonRef = useRef<HTMLButtonElement>(null);
    const itemRefs = useRef<(HTMLAnchorElement | null)[]>([]);

    // ─── Nav state (lazy init: detect scroll position on mount) ──
    const [navState, setNavState] = useState<NavbarState>(() => {
        if (typeof window === "undefined") return "hero";
        return window.scrollY >= 100 ? "pill" : "hero";
    });

    // ─── Menu overlay ─────────────────────────────────────────
    const [menuOpen, setMenuOpen] = useState(false);
    const [pillRect, setPillRect] = useState<DOMRect | null>(null);

    // ─── Scroll spy ───────────────────────────────────────────
    const [activeSection, setActiveSection] = useState("discover");
    const activeIndex = navItems.findIndex(
        (item) => item.href.slice(1) === activeSection,
    );

    // ─── Hover indicator pill ─────────────────────────────────
    const [hoveredIndex, setHoveredIndex] = useState<number | null>(null);
    const [pillStyle, setPillStyle] = useState({ left: 0, width: 0, opacity: 0 });

    // ─── Hysteretic scroll listener ───────────────────────────
    useEffect(() => {
        const onScroll = () => {
            const y = window.scrollY;
            setNavState((prev) => {
                if (prev === "hero" && y >= 100) return "pill";
                if (prev === "pill" && y < 80) return "hero";
                return prev;
            });

            const scrollPosition = y + window.innerHeight / 3;
            let current = navItems[0].href.slice(1);
            for (const item of navItems) {
                const el = document.getElementById(item.href.slice(1));
                if (el && el.offsetTop <= scrollPosition) current = item.href.slice(1);
            }
            setActiveSection(current);
        };

        window.addEventListener("scroll", onScroll, { passive: true });
        return () => window.removeEventListener("scroll", onScroll);
    }, []);

    // ─── GSAP morph animation ─────────────────────────────────
    const isFirstRender = useRef(true);

    useEffect(() => {
        const glass = glassRef.current;
        const hero = heroContentRef.current;
        const pill = pillContentRef.current;
        if (!glass || !hero || !pill) return;

        const reducedMotion = window.matchMedia(
            "(prefers-reduced-motion: reduce)",
        ).matches;
        const animate = !isFirstRender.current && !reducedMotion;
        isFirstRender.current = false;

        gsap.killTweensOf([glass, hero, pill]);

        const containerWidth = containerRef.current?.offsetWidth ?? window.innerWidth;
        const halfInset = Math.max(0, containerWidth / 2 - 78);
        const pillClip = `inset(6px ${halfInset}px 6px ${halfInset}px round 9999px)`;
        const heroClip = "inset(0px 0px 0px 0px round 9999px)";

        if (navState === "pill") {
            const props = {
                clipPath: pillClip,
                backgroundColor: "rgba(3,16,38,0.72)",
                borderColor: "rgba(255,255,255,0.18)",
                boxShadow:
                    "inset 0 1px 0 rgba(255,255,255,0.10), 0 8px 32px rgba(3,16,38,0.50)",
            };
            if (animate) {
                const tl = gsap.timeline();
                tl.to(glass, { ...props, duration: 0.7, ease: "power3.out" });
                tl.to(hero, { autoAlpha: 0, y: -6, duration: 0.35, ease: "power3.out" }, "<");
                tl.to(pill, { autoAlpha: 1, scale: 1, duration: 0.45, ease: "power3.out" }, "<0.12");
            } else {
                gsap.set(glass, props);
                gsap.set(hero, { autoAlpha: 0, y: -6 });
                gsap.set(pill, { autoAlpha: 1, scale: 1 });
            }
        } else {
            const props = {
                clipPath: heroClip,
                backgroundColor: "rgba(255,255,255,0.035)",
                borderColor: "rgba(255,255,255,0.13)",
                boxShadow:
                    "inset 0 1px 0 rgba(255,255,255,0.06), 0 4px 20px rgba(3,16,38,0.18)",
            };
            if (animate) {
                const tl = gsap.timeline();
                tl.to(glass, { ...props, duration: 0.65, ease: "power3.out" });
                tl.to(hero, { autoAlpha: 1, y: 0, duration: 0.5, ease: "power3.out" }, "<0.05");
                tl.to(pill, { autoAlpha: 0, scale: 0.9, duration: 0.25, ease: "power3.in" }, "<");
            } else {
                gsap.set(glass, props);
                gsap.set(hero, { autoAlpha: 1, y: 0 });
                gsap.set(pill, { autoAlpha: 0, scale: 0.9 });
            }
        }

        return () => {
            gsap.killTweensOf([glass, hero, pill]);
        };
    }, [navState]);

    // ─── Resize: recompute pill clip-path when in pill state ──
    useEffect(() => {
        if (navState !== "pill") return;
        const onResize = () => {
            if (!glassRef.current || !containerRef.current) return;
            const halfInset = Math.max(0, containerRef.current.offsetWidth / 2 - 78);
            gsap.set(glassRef.current, {
                clipPath: `inset(6px ${halfInset}px 6px ${halfInset}px round 9999px)`,
            });
        };
        window.addEventListener("resize", onResize);
        return () => window.removeEventListener("resize", onResize);
    }, [navState]);

    // ─── Hover indicator position ─────────────────────────────
    useEffect(() => {
        const update = () => {
            const target = hoveredIndex !== null ? hoveredIndex : activeIndex;
            if (target !== -1 && itemRefs.current[target]) {
                const el = itemRefs.current[target]!;
                setPillStyle({ left: el.offsetLeft, width: el.offsetWidth, opacity: 1 });
            } else {
                setPillStyle((p) => ({ ...p, opacity: 0 }));
            }
        };
        update();
        window.addEventListener("resize", update);
        const tid = setTimeout(update, 150);
        return () => {
            window.removeEventListener("resize", update);
            clearTimeout(tid);
        };
    }, [hoveredIndex, activeIndex]);

    // ─── Menu controls ────────────────────────────────────────
    const toggleMenu = () => {
        if (!menuOpen && pillButtonRef.current) {
            setPillRect(pillButtonRef.current.getBoundingClientRect());
        }
        setMenuOpen((o) => !o);
    };

    const closeMenu = () => setMenuOpen(false);

    return {
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
    };
}
