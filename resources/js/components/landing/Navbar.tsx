import { useState, useEffect, useRef } from "react";
import { Link } from "@inertiajs/react";

const navItems = [
    { label: "Beranda", href: "#", active: true },
    { label: "Tentang Kami", href: "#", active: false },
    { label: "Produk & Layanan", href: "#", active: false },
    { label: "Struktur Organisasi", href: "#", active: false },
    { label: "Hubungi Kami", href: "#", active: false },
];

export default function LandingNavbar() {
    const [scrolled, setScrolled] = useState(false);
    
    // Direction A: Fluid Hover Pill State
    const [hoveredIndex, setHoveredIndex] = useState<number | null>(null);
    const [pillStyle, setPillStyle] = useState({ left: 0, width: 0, opacity: 0 });
    
    const activeIndex = navItems.findIndex(item => item.active);
    const itemRefs = useRef<(HTMLAnchorElement | null)[]>([]);

    useEffect(() => {
        const onScroll = () => setScrolled(window.scrollY > 48);
        window.addEventListener("scroll", onScroll, { passive: true });
        return () => window.removeEventListener("scroll", onScroll);
    }, []);

    // Update the fluid pill position based on hover or active state
    useEffect(() => {
        const updatePill = () => {
            const targetIndex = hoveredIndex !== null ? hoveredIndex : activeIndex;
            
            if (targetIndex !== -1 && itemRefs.current[targetIndex]) {
                const el = itemRefs.current[targetIndex] as HTMLAnchorElement;
                setPillStyle({
                    left: el.offsetLeft,
                    width: el.offsetWidth,
                    opacity: 1
                });
            } else {
                setPillStyle(prev => ({ ...prev, opacity: 0 }));
            }
        };

        updatePill();
        
        // Handle window resize to recalculate pill dimensions
        window.addEventListener('resize', updatePill);
        // Short timeout catches font-load layout shifts
        const timeoutId = setTimeout(updatePill, 150);
        
        return () => {
            window.removeEventListener('resize', updatePill);
            clearTimeout(timeoutId);
        };
    }, [hoveredIndex, activeIndex]);

    return (
        <header className="fixed top-0 left-0 right-0 z-50 px-4 md:px-6 pt-5">
            <div
                className={`mx-auto max-w-7xl px-4 md:px-6 py-2.5 flex items-center justify-between rounded-full border ring-1 ring-inset transition-all duration-700 ease-[cubic-bezier(0.25,1,0.5,1)] ${
                    scrolled
                        ? "bg-primary-950/70 backdrop-blur-2xl border-white/20 ring-white/10 shadow-[0_8px_32px_rgba(3,16,38,0.5)]"
                        : "bg-white/2 backdrop-blur-xl border-white/15 ring-white/5 shadow-lg"
                }`}
            >
                {/* Left — Logo */}
                <div className="shrink-0">
                    <img
                        src="/assets/images/logo_idig_htech_white.png"
                        alt="iDIG Health Tech Logo"
                        className="h-10 w-auto object-contain"
                    />
                </div>

                {/* Center — Liquid glass pill nav */}
                <nav
                    className={`hidden md:flex items-center gap-1 rounded-full px-2 py-1.5 transition-all duration-700 ease-[cubic-bezier(0.25,1,0.5,1)] relative ${
                        scrolled
                            ? "bg-transparent border-transparent shadow-none"
                            : "bg-white/5 border border-white/10 shadow-[inset_0_1px_1px_rgba(255,255,255,0.05)]"
                    }`}
                    onMouseLeave={() => setHoveredIndex(null)}
                >
                    {/* The fluid gliding pill background */}
                    <div 
                        className="absolute top-1.5 bottom-1.5 rounded-full bg-white/10 ring-1 ring-inset ring-white/20 transition-all duration-500 ease-[cubic-bezier(0.25,1,0.5,1)] pointer-events-none z-0"
                        style={{
                            left: pillStyle.left,
                            width: pillStyle.width,
                            opacity: pillStyle.opacity,
                        }}
                    />

                    {navItems.map((item, idx) => (
                        <a
                            key={item.label}
                            href={item.href}
                            ref={(el) => { itemRefs.current[idx] = el; }}
                            onMouseEnter={() => setHoveredIndex(idx)}
                            className={`relative z-10 px-5 py-2 text-sm font-body font-medium rounded-full transition-colors duration-500 ease-[cubic-bezier(0.25,1,0.5,1)] ${
                                item.active || hoveredIndex === idx
                                    ? "text-white"
                                    : "text-white/70"
                            }`}
                        >
                            {item.label}
                        </a>
                    ))}
                </nav>

                {/* Right — Sign In */}
                <div className="shrink-0">
                    <Link
                        href="/login"
                        className={`group relative overflow-hidden px-6 py-2.5 rounded-full font-body font-semibold text-sm flex items-center gap-1.5 transition-all duration-700 ease-[cubic-bezier(0.25,1,0.5,1)] hover:scale-105 active:scale-95 before:absolute before:-inset-1 before:z-0 before:animate-[spin_4s_linear_infinite] before:blur-xs group-hover:before:opacity-100 before:transition-opacity before:duration-700 before:bg-[conic-gradient(from_0deg,var(--color-blue-500)_0deg,var(--color-yellow-400)_120deg,var(--color-blue-500)_240deg,var(--color-yellow-400)_360deg)] after:absolute after:inset-px after:z-1 after:rounded-[inherit] after:backdrop-blur-xl after:ring-1 after:ring-inset after:ring-white/20 after:transition-colors after:duration-700 after:ease-[cubic-bezier(0.25,1,0.5,1)] ${
                            scrolled
                                ? "text-primary-950 before:opacity-20 after:bg-white/95"
                                : "text-white before:opacity-50 after:bg-primary-950/70"
                        }`}
                    >
                        <span className="relative z-10 flex items-center gap-1.5">
                            Masuk
                            <svg
                                className="w-3.5 h-3.5 transition-transform duration-300 ease-[cubic-bezier(0.25,1,0.5,1)] group-hover:translate-x-0.5 group-hover:-rotate-12"
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
                    </Link>
                </div>
            </div>
        </header>
    );
}
