interface ServiceCardProps {
    service: {
        title: string;
        body: string;
        image: string;
        alt: string;
        gradient: string;
        align: string;
        tilt: number;
    };
}

/** Tilted, full-bleed service article card for ServicesSection. */
export default function ServiceCard({ service }: ServiceCardProps) {
    const isLeft = service.align === "left";

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
                        <button className="cursor-pointer px-10 py-4 lg:py-5 rounded-full bg-white text-slate-950 font-display font-bold text-sm lg:text-base tracking-widest uppercase hover:bg-slate-100 hover:scale-[1.03] hover:shadow-[0_0_40px_rgba(255,255,255,0.4)] active:scale-95 transition-all duration-300">
                            Jelajahi {service.title}
                        </button>
                    </div>
                </div>

                {/* Image Side */}
                <div
                    className={`relative z-10 flex-1 flex items-center justify-center overflow-hidden ${isLeft ? "md:order-2" : "md:order-1"} w-full h-[280px] md:h-[400px] lg:h-[480px] pointer-events-none`}
                >
                    <div className="relative w-full h-full flex items-center justify-center">
                        {/* Subtle glow behind image */}
                        <div className="absolute inset-0 bg-white/5 rounded-full blur-[80px] scale-75 group-hover:bg-white/15 transition-colors duration-700" />

                        <img
                            src={service.image}
                            alt={service.alt}
                            className="service-image h-full w-full object-contain select-none scale-100 group-hover:scale-110 transition-transform duration-700 ease-out drop-shadow-2xl"
                            draggable={false}
                            loading="lazy"
                        />
                    </div>
                </div>
            </article>
        </div>
    );
}
