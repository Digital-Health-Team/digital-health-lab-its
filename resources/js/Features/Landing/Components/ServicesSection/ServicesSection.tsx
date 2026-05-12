import { useRef } from "react";
import { services } from "../../Data/servicesSection.data";
import { useServicesSection } from "../../Hooks/useServicesSection";
import ServiceCard from "./fragments/ServiceCard";

export default function ServicesSection() {
    const containerRef = useRef<HTMLDivElement>(null);

    useServicesSection(containerRef);

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
                        Tiga Pilar Inovasi
                    </strong>
                    <span className="font-light italic tracking-tight block text-[#062e5c]">
                        Laboratorium Kami.
                    </span>
                </h2>
                <p className="mt-10 text-lg md:text-xl font-body text-[#062e5c]/70 max-w-[65ch] leading-relaxed text-balance">
                    Eksplorasi layanan riset, purwarupa medis, dan agenda
                    strategis yang menjadi motor penggerak ekosistem inovasi
                    teknologi kesehatan kami.
                </p>
            </div>

            <div className="max-w-7xl mx-auto flex flex-col gap-20 lg:gap-28 relative z-10">
                {services.map((service) => (
                    <ServiceCard key={service.title} service={service} />
                ))}
            </div>
        </section>
    );
}
