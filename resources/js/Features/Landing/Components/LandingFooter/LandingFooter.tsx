import { Mail, MapPin, Phone } from "lucide-react";
import { usePage } from "@inertiajs/react";
import { CONTACT_INFO, QUICK_LINKS, SOCIAL_LINKS } from "../../Constants/landingFooter.const";
import FooterWatermark from "./fragments/FooterWatermark";
import SocialLinks from "./fragments/SocialLinks";

export default function LandingFooter() {
    const lc: Record<string, string> = (usePage().props as any).landingContent ?? {};

    const tagline = lc.footer_tagline ?? "Repository & Publication of Medical Engineering Technology ITS. Advancing innovation through science.";
    const rawAddress = lc.footer_address ?? null;
    const addressLines = rawAddress
        ? rawAddress.split("\n")
        : CONTACT_INFO.address;
    const phone = lc.footer_phone ?? CONTACT_INFO.phone;
    const email = lc.footer_email ?? CONTACT_INFO.email;

    const socialLinks = [
        { name: "YouTube",   href: lc.footer_youtube_url   ?? SOCIAL_LINKS[0].href },
        { name: "Instagram", href: lc.footer_instagram_url ?? SOCIAL_LINKS[1].href },
        { name: "Facebook",  href: lc.footer_facebook_url  ?? SOCIAL_LINKS[2].href },
        { name: "LinkedIn",  href: lc.footer_linkedin_url  ?? SOCIAL_LINKS[3].href },
    ];

    return (
        <footer
            className="relative bg-primary-950 pt-16 pb-6 px-6 md:px-12 overflow-hidden"
        >
            <div className="relative z-10 max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
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
                        {tagline}
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
                                {addressLines.map((line, i) => (
                                    <span key={i}>
                                        {line}
                                        {i < addressLines.length - 1 && <br />}
                                    </span>
                                ))}
                            </span>
                        </li>
                        <li className="flex items-center gap-3">
                            <Phone
                                size={15}
                                className="shrink-0 text-secondary-400"
                            />
                            <span>{phone}</span>
                        </li>
                        <li className="flex items-center gap-3">
                            <Mail
                                size={15}
                                className="shrink-0 text-secondary-400"
                            />
                            <span>{email}</span>
                        </li>
                    </ul>
                </div>

                {/* Col 3 — Quick Links */}
                <div className="md:border-l md:border-white/10 md:pl-12">
                    <h4 className="text-white font-display font-semibold text-base mb-5">
                        Quick Links
                    </h4>
                    <ul className="space-y-3 text-sm font-body text-white/70">
                        {QUICK_LINKS.map((link) => (
                            <li key={link.label}>
                                <a
                                    href={link.href}
                                    className="hover:text-secondary-400 transition-colors duration-200"
                                >
                                    {link.label}
                                </a>
                            </li>
                        ))}
                    </ul>
                </div>

                {/* Col 4 — Social */}
                <div className="md:border-l md:border-white/10 md:pl-12">
                    <h4 className="text-white font-display font-semibold text-base mb-5">
                        Follow Us
                    </h4>
                    <SocialLinks links={socialLinks} />
                </div>
            </div>

            {/* The Giant Brand Text */}
            <FooterWatermark />

            {/* Bottom bar */}
            <div className="relative z-10 mt-8 pt-6 border-t border-white/10 max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-2">
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
