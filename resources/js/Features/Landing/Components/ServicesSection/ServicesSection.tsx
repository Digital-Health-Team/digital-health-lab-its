import { useRef } from "react";
import { usePage } from "@inertiajs/react";
import { services } from "../../Data/servicesSection.data";
import type { Service } from "../../Types/servicesSection.type";
import { safeJsonParse } from "../../Utils/safeJsonParse";
import { useServicesSectionAnimation } from "../../Hooks/useServicesSectionAnimation";
import ServiceCard from "./fragments/ServiceCard";

function buildService(raw: string | undefined, fallback: Service): Service {
    const parsed = safeJsonParse<Record<string, string>>(raw, {});
    if (!parsed.title) return fallback;
    return {
        ...fallback,
        title: parsed.title ?? fallback.title,
        body: parsed.body ?? fallback.body,
        image: parsed.image_url ?? fallback.image,
        gradient: parsed.gradient ?? fallback.gradient,
    };
}

export default function ServicesSection() {
    const containerRef = useRef<HTMLDivElement>(null);

    useServicesSectionAnimation(containerRef);

    const lc: Record<string, string> = (usePage().props as any).landingContent ?? {};

    const heading = lc.services_heading ?? "Tiga Pilar Inovasi";
    const subheading = lc.services_subheading ?? "Laboratorium Kami.";
    const body = lc.services_body ?? "Eksplorasi layanan riset, purwarupa medis, dan agenda strategis yang menjadi motor penggerak ekosistem inovasi teknologi kesehatan kami.";

    const derivedServices = [
        buildService(lc.services_card_1, services[0]),
        buildService(lc.services_card_2, services[1]),
        buildService(lc.services_card_3, services[2]),
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
                        Layanan Kami
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
                {derivedServices.map((service) => (
                    <ServiceCard key={service.title} service={service} />
                ))}
            </div>
        </section>
    );
}
