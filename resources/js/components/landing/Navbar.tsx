import { useState, useEffect } from "react";
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

    useEffect(() => {
        const onScroll = () => setScrolled(window.scrollY > 48);
        window.addEventListener("scroll", onScroll, { passive: true });
        return () => window.removeEventListener("scroll", onScroll);
    }, []);

    return (
        <header className="fixed top-0 left-0 right-0 z-50 px-4 md:px-6 pt-3">
            <div
                className={`nav-island px-5 md:px-6 py-3 flex items-center justify-between ${
                    scrolled ? "nav-island-scrolled" : ""
                }`}
            >
                {/* Left — Logo */}
                <img
                    src="/assets/images/logo_idig_htech_white.png"
                    alt="iDIG Health Tech Logo"
                    className="h-12 w-auto object-contain"
                />

                {/* Center — Liquid glass pill nav */}
                <nav className="glass-pill rounded-full px-2 py-1.5 hidden md:flex items-center gap-0.5">
                    {navItems.map((item) => (
                        <a
                            key={item.label}
                            href={item.href}
                            className={`px-5 py-2 text-sm font-body font-medium rounded-full transition-all duration-200 ${
                                item.active
                                    ? "bg-white/15 text-white"
                                    : "text-white/70 hover:text-white hover:bg-white/8"
                            }`}
                        >
                            {item.label}
                        </a>
                    ))}
                </nav>

                {/* Right — Sign In */}
                <Link
                    href="/login"
                    className="nav-signin-btn bg-primary-600 hover:bg-primary-700 text-white font-body font-semibold text-sm px-6 py-2.5 rounded-xl border border-secondary-400/55"
                >
                    Sign In
                </Link>
            </div>
        </header>
    );
}
