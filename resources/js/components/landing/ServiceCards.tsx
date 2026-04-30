import { useRef, useEffect } from "react";
import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

const IMG_BASE = "/assets/images/services";

const SERVICES = [
    {
        title: "Research",
        body: "Find information from our collection of journals, papers, and researches.",
        image: `${IMG_BASE}/Research%20Card%20Journal%20PNG%20-%20Landing%20Page.png`,
        alt: "Open biomedical engineering journal pages",
        gradient: "bg-gradient-to-b from-teal-600 via-teal-800 to-slate-900",
        featured: false,
    },
    {
        title: "Products & Services",
        body: "Explore our 3D designs, prototypes, and made-to-order fabrication.",
        image: `${IMG_BASE}/Products%20%26%20Services%20-%20Hand%20PNG%20-%20Landing%20Page.png`,
        alt: "3D printed prosthetic hand prototype",
        gradient:
            "bg-gradient-to-b from-primary-600 via-primary-700 to-primary-900",
        featured: true,
    },
    {
        title: "Events",
        body: "Join our annual symposia and visit the events that spark your interests.",
        image: `${IMG_BASE}/Events%20-%20Booth%20PNG%20-%20Landing%20Page.png`,
        alt: "IDIG conference booth with branded displays",
        gradient: "bg-gradient-to-b from-rose-700 via-rose-900 to-fuchsia-950",
        featured: false,
    },
];

// Entry: outer cards lean on their outer axis, center drops straight in
const LEAN_ANGLES = [-15, 0, 15];

// Horizontal positions for the section background glow (as plain numbers for @property)
const BG_GLOW_X = ["22", "50", "78"];

export default function ServiceCards() {
    const containerRef = useRef<HTMLDivElement>(null);
    const bgRef = useRef<HTMLDivElement>(null);

    // Per-card element refs
    const cardRefs = useRef<(HTMLElement | null)[]>([null, null, null]);
    const ringRefs = useRef<(HTMLDivElement | null)[]>([null, null, null]);
    const scannerRefs = useRef<(HTMLDivElement | null)[]>([null, null, null]);

    // GSAP spring tilt functions, initialized after mount
    const tiltXTo = useRef<((v: number) => void)[]>([]);
    const tiltYTo = useRef<((v: number) => void)[]>([]);

    const prefersReducedMotion = useRef(
        typeof window !== "undefined"
            ? window.matchMedia("(prefers-reduced-motion: reduce)").matches
            : false,
    );

    // Set up quickTo springs after DOM is ready
    useEffect(() => {
        cardRefs.current.forEach((card, i) => {
            if (!card) return;
            tiltXTo.current[i] = gsap.quickTo(card, "rotateX", {
                duration: 0.55,
                ease: "power3.out",
            });
            tiltYTo.current[i] = gsap.quickTo(card, "rotateY", {
                duration: 0.55,
                ease: "power3.out",
            });
        });
        return () => {
            cardRefs.current.forEach((card) => {
                if (card) gsap.killTweensOf(card);
            });
        };
    }, []);

    const handleMouseMove =
        (i: number) => (e: React.MouseEvent<HTMLElement>) => {
            if (prefersReducedMotion.current) return;
            const card = cardRefs.current[i];
            if (!card) return;
            const rect = card.getBoundingClientRect();
            const rotY =
                ((e.clientX - (rect.left + rect.width / 2)) /
                    (rect.width / 2)) *
                9;
            const rotX =
                -(
                    (e.clientY - (rect.top + rect.height / 2)) /
                    (rect.height / 2)
                ) * 7;
            tiltXTo.current[i]?.(rotX);
            tiltYTo.current[i]?.(rotY);
        };

    const handleMouseEnter = (i: number) => () => {
        const ring = ringRefs.current[i];
        const scanner = scannerRefs.current[i];

        // Fade in the persistent outline ring
        if (ring)
            gsap.to(ring, { opacity: 1, duration: 0.35, ease: "power2.out" });

        // Sweep the scanner arc around the border once, then dissolve it
        if (scanner && !prefersReducedMotion.current) {
            gsap.killTweensOf(scanner);
            gsap.fromTo(
                scanner,
                { "--border-angle-num": 0, opacity: 1 },
                {
                    "--border-angle-num": 360,
                    opacity: 1,
                    duration: 1.3,
                    ease: "power2.out",
                    onComplete: () =>
                        gsap.to(scanner, { opacity: 0, duration: 0.5 }),
                },
            );
        }

        // Shift the section background glow toward this card
        if (bgRef.current) {
            bgRef.current.style.setProperty("--bg-glow-num", BG_GLOW_X[i]);
        }
    };

    const handleMouseLeave = (i: number) => () => {
        const ring = ringRefs.current[i];
        const scanner = scannerRefs.current[i];

        // Spring tilt back to resting position
        if (!prefersReducedMotion.current) {
            tiltXTo.current[i]?.(0);
            tiltYTo.current[i]?.(0);
        }

        if (ring)
            gsap.to(ring, { opacity: 0, duration: 0.35, ease: "power2.in" });
        if (scanner) {
            gsap.killTweensOf(scanner);
            gsap.to(scanner, { opacity: 0, duration: 0.2 });
        }

        // Return background glow to center
        if (bgRef.current) {
            bgRef.current.style.setProperty("--bg-glow-num", "50");
        }
    };

    useGSAP(
        () => {
            gsap.from(".service-intro > *", {
                opacity: 0,
                y: 16,
                duration: 0.6,
                stagger: 0.1,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: ".service-intro",
                    start: "top 88%",
                },
            });

            const wrappers =
                containerRef.current?.querySelectorAll(".card-tilt-wrapper");
            if (!wrappers?.length) return;

            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: containerRef.current,
                    start: "top 78%",
                },
            });

            // Cards lean on their outside axis and spring to flat
            wrappers.forEach((wrapper, i) => {
                const card = wrapper.querySelector(".service-card");
                if (!card) return;
                tl.from(
                    card,
                    {
                        rotateY: LEAN_ANGLES[i],
                        opacity: 0,
                        y: 20,
                        duration: 1.0,
                        ease: "back.out(1.2)",
                    },
                    i * 0.16,
                );
            });
        },
        { scope: containerRef },
    );

    return (
        <section
            id="categories"
            ref={containerRef}
            className="relative mt-8 px-6 md:px-12 pb-24 z-10"
        >
            {/* Radial glow that drifts toward the hovered card */}
            <div ref={bgRef} className="section-bg-overlay" />

            <div className="service-intro relative z-10 text-center pt-16 mb-14">
                <span className="text-xs font-body font-medium tracking-[0.14em] uppercase text-secondary-400">
                    Layanan Kami
                </span>
                <h2 className="mt-3 pb-10 font-display font-bold text-4xl md:text-5xl leading-tight">
                    Tiga Pilar Inovasi Laboratorium Kami
                </h2>
            </div>

            <div className="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-10 items-center relative z-10">
                {SERVICES.map((service, i) => {
                    const heightClass = service.featured
                        ? "h-[600px]"
                        : "h-[520px]";

                    // Scale/position on the wrapper so GSAP tilt on the article is clean
                    const wrapperClass = service.featured
                        ? "card-tilt-wrapper [perspective:900px] md:scale-[1.04] md:-mt-10 md:mb-2 z-10 group"
                        : "card-tilt-wrapper [perspective:900px] group";

                    const articleClass = `service-card relative overflow-hidden rounded-3xl ${service.gradient} text-white ${
                        service.featured
                            ? "service-card-featured-ring"
                            : "shadow-card-soft"
                    }`;

                    return (
                        <div key={service.title} className={wrapperClass}>
                            <article
                                ref={(el) => {
                                    cardRefs.current[i] = el;
                                }}
                                className={articleClass}
                                onMouseMove={handleMouseMove(i)}
                                onMouseEnter={handleMouseEnter(i)}
                                onMouseLeave={handleMouseLeave(i)}
                            >
                                {/* Blueprint grid */}
                                <div className="card-blueprint absolute inset-0 pointer-events-none" />

                                {/* Persistent orbital ring (opacity driven by GSAP) */}
                                <div
                                    ref={(el) => {
                                        ringRefs.current[i] = el;
                                    }}
                                    className="ring-persistent"
                                    style={{ opacity: 0 }}
                                />

                                {/* Scanning arc (--border-angle-num driven by GSAP) */}
                                <div
                                    ref={(el) => {
                                        scannerRefs.current[i] = el;
                                    }}
                                    className="ring-scanner"
                                    style={{ opacity: 0 }}
                                />

                                <div
                                    className={`relative z-10 ${heightClass} flex flex-col items-center text-center px-7 pt-10`}
                                >
                                    <h3
                                        className={`font-display font-bold text-white ${
                                            service.featured
                                                ? "text-3xl"
                                                : "text-2xl"
                                        }`}
                                    >
                                        {service.title}
                                    </h3>

                                    <p className="mt-3 text-sm font-body text-white/85 leading-relaxed max-w-[26ch]">
                                        {service.body}
                                    </p>

                                    <button className="mt-6 px-8 py-2.5 rounded-full bg-white/15 backdrop-blur-sm border border-white/30 text-white text-sm font-body font-medium hover:bg-white/25 active:scale-95 transition-all duration-200 cursor-pointer">
                                        Explore
                                    </button>

                                    <div className="flex-1 flex items-end justify-center w-full mt-4 overflow-hidden">
                                        <img
                                            src={service.image}
                                            alt={service.alt}
                                            className="max-h-full max-w-full object-contain select-none group-hover:scale-110 transition-all duration-500"
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
