import { useRef } from "react";
import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

const IMG_BASE = "/assets/images/services";

const SERVICES = [
    {
        title: "Produk & Layanan",
        body: "Jelajahi desain 3D, purwarupa medis, dan fabrikasi khusus sesuai kebutuhan Anda.",
        image: `${IMG_BASE}/Products%20%26%20Services%20-%20Hand%20PNG%20-%20Landing%20Page.png`,
        alt: "3D printed prosthetic hand prototype",
        gradient:
            "bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900",
        align: "left",
        tilt: -1.5,
    },
    {
        title: "Riset & Inovasi",
        body: "Temukan informasi dari koleksi jurnal, publikasi, dan penelitian terbaru kami.",
        image: `${IMG_BASE}/Research%20Card%20Journal%20PNG%20-%20Landing%20Page.png`,
        alt: "Open biomedical engineering journal pages",
        gradient: "bg-gradient-to-br from-teal-600 via-teal-800 to-slate-900",
        align: "right",
        tilt: 1.5,
    },
    {
        title: "Agenda & Acara",
        body: "Ikuti perkembangan terbaru mengenai acara, webinar, dan berita dari komunitas kami.",
        image: `${IMG_BASE}/Events%20-%20Booth%20PNG%20-%20Landing%20Page.png`,
        alt: "IDIG conference booth with branded displays",
        gradient: "bg-gradient-to-br from-rose-700 via-rose-900 to-fuchsia-950",
        align: "left",
        tilt: -1.5,
    },
];

export default function ServiceCards() {
    const containerRef = useRef<HTMLDivElement>(null);

    useGSAP(
        () => {
            const prefersReducedMotion = window.matchMedia(
                "(prefers-reduced-motion: reduce)",
            ).matches;

            gsap.from(".service-intro > *", {
                opacity: 0,
                y: 20,
                duration: 0.8,
                stagger: 0.15,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: ".service-intro",
                    start: "top 85%",
                },
            });

            const wrappers = gsap.utils.toArray<HTMLElement>(
                ".service-card-wrapper",
            );

            wrappers.forEach((wrapper, i) => {
                const align = SERVICES[i].align;
                const xOffset = align === "left" ? -80 : 80;

                if (!prefersReducedMotion) {
                    gsap.from(wrapper, {
                        opacity: 0,
                        x: xOffset,
                        y: 60,
                        rotation: SERVICES[i].tilt * 2, // slightly exaggerated starting tilt
                        duration: 1.4,
                        ease: "power3.out",
                        scrollTrigger: {
                            trigger: wrapper,
                            start: "top 80%",
                            toggleActions: "play none none reverse",
                        },
                    });
                } else {
                    gsap.from(wrapper, {
                        opacity: 0,
                        y: 20,
                        duration: 1,
                        scrollTrigger: {
                            trigger: wrapper,
                            start: "top 85%",
                        },
                    });
                }
            });

            // Floating 3D assets
            if (!prefersReducedMotion) {
                const floatingImages =
                    gsap.utils.toArray<HTMLElement>(".service-image");
                floatingImages.forEach((img, i) => {
                    gsap.to(img, {
                        y: "-=20",
                        rotation: (i % 2 === 0 ? 1 : -1) * 1.5,
                        duration: 3.5 + i * 0.4,
                        ease: "sine.inOut",
                        yoyo: true,
                        repeat: -1,
                    });
                });
            }
        },
        { scope: containerRef },
    );

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
                {SERVICES.map((service, i) => {
                    const isLeft = service.align === "left";

                    return (
                        <div
                            key={service.title}
                            className={`service-card-wrapper w-full lg:w-[80%] ${
                                isLeft ? "self-start" : "self-end"
                            }`}
                            style={{
                                transform: `rotate(${service.tilt}deg)`,
                            }}
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
                })}
            </div>
        </section>
    );
}
