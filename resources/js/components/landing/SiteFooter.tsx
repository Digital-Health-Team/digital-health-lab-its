import { MapPin, Phone, Mail } from "lucide-react";

function YoutubeIcon() {
    return (
        <svg viewBox="0 0 24 24" fill="currentColor" className="w-4 h-4">
            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
        </svg>
    );
}

function InstagramIcon() {
    return (
        <svg viewBox="0 0 24 24" fill="currentColor" className="w-4 h-4">
            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z" />
        </svg>
    );
}

export default function SiteFooter() {
    return (
        <footer className="bg-primary-950 pt-16 pb-6 px-6 md:px-12">
            <div className="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
                {/* Col 1 — Logo Card */}
                <div className="flex flex-col gap-4">
                    <div className="inline-flex w-fit">
                        <img
                            src="/assets/images/logo_idig_htech_white.png"
                            alt="iDIG Health Tech Logo"
                            className="h-20 w-auto object-contain"
                        />
                    </div>
                    <p className="text-white/60 font-body text-sm leading-relaxed max-w-[220px]">
                        Repository & Publication of Medical Engineering
                        Technology ITS. Advancing innovation through science.
                    </p>
                </div>

                {/* Col 2 — Contact */}
                <div className="md:border-l md:border-white/10 md:pl-12">
                    <h4 className="text-white font-display font-semibold text-base mb-5">
                        Contact
                    </h4>
                    <ul className="space-y-3 text-sm font-body text-white/70">
                        <li className="flex items-start gap-3">
                            <MapPin
                                size={15}
                                className="shrink-0 mt-0.5 text-secondary-400"
                            />
                            <span className="leading-relaxed">
                                Dept. of Medical Engineering Technology,
                                <br />
                                ITS Campus, Surabaya, East Java 60111
                            </span>
                        </li>
                        <li className="flex items-center gap-3">
                            <Phone
                                size={15}
                                className="shrink-0 text-secondary-400"
                            />
                            <span>+62 31 5994251</span>
                        </li>
                        <li className="flex items-center gap-3">
                            <Mail
                                size={15}
                                className="shrink-0 text-secondary-400"
                            />
                            <span>idig@its.ac.id</span>
                        </li>
                    </ul>
                </div>

                {/* Col 3 — Social & Links */}
                <div className="md:border-l md:border-white/10 md:pl-12">
                    <h4 className="text-white font-display font-semibold text-base mb-5">
                        Follow Us
                    </h4>
                    <div className="flex items-center gap-4">
                        <a
                            href="#"
                            className="w-10 h-10 rounded-full bg-white/10 border border-white/15 flex items-center justify-center text-white/70 hover:text-white hover:bg-white/20 transition-all duration-200"
                            aria-label="YouTube"
                        >
                            <YoutubeIcon />
                        </a>
                        <a
                            href="#"
                            className="w-10 h-10 rounded-full bg-white/10 border border-white/15 flex items-center justify-center text-white/70 hover:text-white hover:bg-white/20 transition-all duration-200"
                            aria-label="Instagram"
                        >
                            <InstagramIcon />
                        </a>
                    </div>
                    <div className="mt-6">
                        <h4 className="text-white font-display font-semibold text-sm mb-3">
                            Quick Links
                        </h4>
                        <ul className="space-y-1.5 text-sm font-body text-white/60">
                            {[
                                "Research",
                                "Products & Services",
                                "Events",
                                "About",
                            ].map((link) => (
                                <li key={link}>
                                    <a
                                        href="#"
                                        className="hover:text-secondary-400 transition-colors duration-200"
                                    >
                                        {link}
                                    </a>
                                </li>
                            ))}
                        </ul>
                    </div>
                </div>

                {/* Col 4 — Visitor Stats */}
                <div className="md:border-l md:border-white/10 md:pl-12">
                    <h4 className="text-white font-display font-semibold text-base mb-5">
                        Visitor Stats
                    </h4>
                    <div className="space-y-4">
                        <div>
                            <p className="text-white/50 text-xs font-body uppercase tracking-widest mb-1">
                                Today
                            </p>
                            <p className="text-white font-display font-bold text-3xl">
                                0
                            </p>
                        </div>
                        <div className="border-t border-white/10 pt-4">
                            <p className="text-white/50 text-xs font-body uppercase tracking-widest mb-1">
                                Total
                            </p>
                            <p className="text-secondary-400 font-display font-bold text-3xl">
                                45,007
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {/* Bottom bar */}
            <div className="mt-12 pt-6 border-t border-white/10 max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-2">
                <p className="text-xs font-body text-white/40">
                    &copy; {new Date().getFullYear()} iDIG Health Tech — ITS
                    Medical Engineering Technology. All rights reserved.
                </p>
                <p className="text-xs font-body text-white/30">
                    Built with care for medical innovation
                </p>
            </div>
        </footer>
    );
}
