import { useEffect, useMemo, useState } from "react";
import { Link } from "@inertiajs/react";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { Carousel } from "@/Core/Components/Shared";
import type { Service } from "../../../Types/servicesSection.type";

interface ServiceCardProps {
    service: Service;
}

/** Round carousel is 1:1, so its width tracks the old image slot heights. */
function useCarouselWidth(): number {
    const [width, setWidth] = useState(300);

    useEffect(() => {
        const read = () => {
            const vw = window.innerWidth;
            setWidth(vw >= 1024 ? 440 : vw >= 768 ? 360 : Math.min(300, vw - 120));
        };
        read();
        window.addEventListener("resize", read);
        return () => window.removeEventListener("resize", read);
    }, []);

    return width;
}

/** Tilted, full-bleed service article card for ServicesSection. */
export default function ServiceCard({ service }: ServiceCardProps) {
    const { t } = useTranslation();
    const isLeft = service.align === "left";
    const carouselWidth = useCarouselWidth();

    // title/body/alt arrive already resolved (CMS row or translated default).
    // The CMS image override is just slide 1, so it still leads the carousel.
    const slides = useMemo(
        () =>
            [service.image, ...service.gallery].map((image, i) => ({
                id: image,
                image,
                alt: i === 0 ? service.alt : `${service.title} — ${i + 1}`,
            })),
        [service.image, service.gallery, service.alt, service.title],
    );

    return (
        <div
            className={`service-card-wrapper w-full lg:w-[80%] ${isLeft ? "self-start" : "self-end"}`}
            style={{ transform: `rotate(${service.tilt}deg)` }}
        >
            <article
                className={`relative overflow-hidden rounded-[2.5rem] ${service.gradient} text-white shadow-2xl flex flex-col md:flex-row items-center p-10 lg:p-16 min-h-[400px] lg:min-h-[480px] group border border-white/10 hover:border-white/20 transition-colors duration-500`}
            >
                {/* Blueprint grid background motif */}
                <div className="card-blueprint absolute inset-0 pointer-events-none opacity-30 mix-blend-overlay transition-opacity duration-700 group-hover:opacity-50" />

                {/* Content Side */}
                <div
                    className={`relative z-10 flex-1 flex flex-col justify-center ${isLeft ? "md:order-1" : "md:order-2"} ${isLeft ? "md:pr-12" : "md:pl-12"} mb-12 md:mb-0 text-center md:text-left`}
                >
                    <h3 className="font-display font-black text-5xl lg:text-7xl tracking-[-0.04em] leading-[0.95] text-white drop-shadow-sm text-balance">
                        {service.title}
                    </h3>

                    <p className="mt-8 text-xl lg:text-[1.65rem] font-body font-light text-white/80 leading-relaxed max-w-[35ch] mx-auto md:mx-0 text-balance">
                        {service.body}
                    </p>

                    <div className="mt-12 self-center md:self-start">
                        <Link
                            href={service.href}
                            className="inline-block cursor-pointer px-10 py-4 lg:py-5 rounded-full bg-white text-slate-950 font-display font-bold text-sm lg:text-base tracking-widest uppercase hover:bg-slate-100 hover:scale-[1.03] hover:shadow-[0_0_40px_rgba(255,255,255,0.4)] active:scale-95 transition-all duration-300"
                        >
                            {t("Explore")} {service.title}
                        </Link>
                    </div>
                </div>

                {/* Carousel Side */}
                <div
                    className={`relative z-10 flex-1 flex items-center justify-center ${isLeft ? "md:order-2" : "md:order-1"} w-full`}
                >
                    {/* .service-image is what the section's float tween targets. */}
                    <div className="service-image relative flex items-center justify-center">
                        {/* Subtle glow behind the carousel */}
                        <div className="absolute inset-0 pointer-events-none bg-white/5 rounded-full blur-[80px] scale-90 group-hover:bg-white/15 transition-colors duration-700" />

                        <Carousel
                            items={slides}
                            baseWidth={carouselWidth}
                            round
                            loop
                            autoplay
                            autoplayDelay={4000}
                            pauseOnHover
                            className="border border-white/20 bg-white/[0.04] backdrop-blur-[2px]"
                        />
                    </div>
                </div>
            </article>
        </div>
    );
}
