import { useRef } from "react";
import { usePage } from "@inertiajs/react";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { services } from "../../Data/servicesSection.data";
import type { Service } from "../../Types/servicesSection.type";
import { safeJsonParse } from "../../Utils/safeJsonParse";
import { useServicesSectionAnimation } from "../../Hooks/useServicesSectionAnimation";
import ServiceCard from "./fragments/ServiceCard";

/**
 * CMS rows are already stored per locale, so they render verbatim. Only the
 * bundled English defaults are looked up in the translation map.
 */
function buildService(raw: string | undefined, fallback: Service, t: (k: string) => string): Service {
    const translated: Service = {
        ...fallback,
        title: t(fallback.title),
        body: t(fallback.body),
        alt: t(fallback.alt),
    };
    const parsed = safeJsonParse<Record<string, string>>(raw, {});
    if (!parsed.title) return translated;
    return {
        ...translated,
        title: parsed.title ?? translated.title,
        body: parsed.body ?? translated.body,
        image: parsed.image_url ?? fallback.image,
        gradient: parsed.gradient ?? fallback.gradient,
    };
}

export default function ServicesSection() {
    const containerRef = useRef<HTMLDivElement>(null);
    const { t } = useTranslation();

    useServicesSectionAnimation(containerRef);

    const lc: Record<string, string> = (usePage().props as any).landingContent ?? {};

    // CMS values are already locale-specific (see LandingPageController), so only
    // the bundled defaults go through t().
    const heading = lc.services_heading ?? t("Three Pillars of Innovation");
    const subheading = lc.services_subheading ?? t("Our Laboratory.");
    const body = lc.services_body ?? t("Explore the research services, medical prototypes, and strategic agenda driving our health technology innovation ecosystem.");

    const derivedServices = [
        buildService(lc.services_card_1, services[0], t),
        buildService(lc.services_card_2, services[1], t),
        buildService(lc.services_card_3, services[2], t),
    ];

    return (
        <section
            id="categories"
            ref={containerRef}
            className="relative mt-8 px-6 md:px-12 pb-32 z-10 overflow-hidden"
        >
            <div className="section-bg-overlay" />

            <div className="service-intro relative z-10 text-center pt-32 mb-32 max-w-3xl mx-auto px-6 flex flex-col items-center">
                <div className="inline-flex items-center gap-3 px-5 py-2.5 rounded-full bg-white/5 border border-white/10 mb-10 backdrop-blur-md">
                    <div className="w-2 h-2 rounded-full bg-cyan-400 animate-pulse" />
                    <span className="text-xs font-body font-bold tracking-[0.25em] uppercase text-secondary">
                        {t("Our Services")}
                    </span>
                </div>
                <h2 className="font-display text-5xl md:text-7xl lg:text-[5.5rem] tracking-tighter leading-none text-black text-balance">
                    <strong className="font-extrabold block mb-2">
                        {heading}
                    </strong>
                    <span className="font-light italic tracking-tight block text-[#062e5c]">
                        {subheading}
                    </span>
                </h2>
                <p className="mt-10 text-lg md:text-xl font-body text-[#062e5c]/70 max-w-[65ch] leading-relaxed text-balance">
                    {body}
                </p>
            </div>

            <div className="max-w-7xl mx-auto flex flex-col gap-20 lg:gap-28 relative z-10">
                {/* Keyed by href, not title — a translated title would change the key and remount. */}
                {derivedServices.map((service) => (
                    <ServiceCard key={service.href} service={service} />
                ))}
            </div>
        </section>
    );
}
