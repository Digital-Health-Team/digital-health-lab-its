import { useState, useEffect, useRef } from "react";
import { navItems } from "../Constants/navData";

export function useNavbar() {
    const [scrolled, setScrolled] = useState(false);
    const [activeSection, setActiveSection] = useState("discover");

    // Direction A: Fluid Hover Pill State
    const [hoveredIndex, setHoveredIndex] = useState<number | null>(null);
    const [pillStyle, setPillStyle] = useState({
        left: 0,
        width: 0,
        opacity: 0,
    });

    const activeIndex = navItems.findIndex((item) => item.href.slice(1) === activeSection);
    const itemRefs = useRef<(HTMLAnchorElement | null)[]>([]);

    useEffect(() => {
        const onScroll = () => {
            setScrolled(window.scrollY > 48);

            // Scroll spy logic
            const scrollPosition = window.scrollY + window.innerHeight / 3;
            let current = navItems[0].href.slice(1);

            for (const item of navItems) {
                const element = document.getElementById(item.href.slice(1));
                if (element && element.offsetTop <= scrollPosition) {
                    current = item.href.slice(1);
                }
            }
            setActiveSection(current);
        };
        
        window.addEventListener("scroll", onScroll, { passive: true });
        onScroll(); // initial check
        return () => window.removeEventListener("scroll", onScroll);
    }, []);

    // Update the fluid pill position based on hover or active state
    useEffect(() => {
        const updatePill = () => {
            const targetIndex =
                hoveredIndex !== null ? hoveredIndex : activeIndex;

            if (targetIndex !== -1 && itemRefs.current[targetIndex]) {
                const el = itemRefs.current[targetIndex] as HTMLAnchorElement;
                setPillStyle({
                    left: el.offsetLeft,
                    width: el.offsetWidth,
                    opacity: 1,
                });
            } else {
                setPillStyle((prev) => ({ ...prev, opacity: 0 }));
            }
        };

        updatePill();

        // Handle window resize to recalculate pill dimensions
        window.addEventListener("resize", updatePill);
        // Short timeout catches font-load layout shifts
        const timeoutId = setTimeout(updatePill, 150);

        return () => {
            window.removeEventListener("resize", updatePill);
            clearTimeout(timeoutId);
        };
    }, [hoveredIndex, activeIndex]);

    return {
        scrolled,
        hoveredIndex,
        setHoveredIndex,
        activeIndex,
        pillStyle,
        itemRefs,
    };
}
