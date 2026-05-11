import { useState, useRef, useEffect } from "react";
import { useGSAP } from "@gsap/react";
import gsap from "gsap";

export default function Preloader() {
    const [isMounted, setIsMounted] = useState(true);
    const [numCurtains] = useState(() =>
        typeof window !== "undefined" && window.innerWidth < 768 ? 6 : 9,
    );
    const containerRef = useRef<HTMLDivElement>(null);
    const curtainsRef = useRef<(HTMLDivElement | null)[]>([]);
    const contentRef = useRef<HTMLDivElement>(null);
    const textStrokeRef = useRef<SVGTextElement>(null);
    const textFillRef = useRef<SVGTextElement>(null);
    const trackRef = useRef<HTMLDivElement>(null);
    const fillRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (!isMounted) return;
        const prev = document.body.style.overflow;
        document.body.style.overflow = "hidden";
        return () => {
            document.body.style.overflow = prev;
        };
    }, [isMounted]);

    useGSAP(
        () => {
            const tl = gsap.timeline({
                onComplete: () => setIsMounted(false),
            });

            // Set initial states
            gsap.set(trackRef.current, {
                scaleX: 0,
                transformOrigin: "center",
            });
            gsap.set(textFillRef.current, { opacity: 0 });
            // 2000 ensures it's longer than the stroke perimeter of most characters
            gsap.set(textStrokeRef.current, {
                strokeDasharray: 2000,
                strokeDashoffset: 2000,
            });
            gsap.set(fillRef.current, { width: "0%" });

            // Phase 1 (Tracing & Track Intro)
            tl.to(trackRef.current, {
                scaleX: 1,
                duration: 0.6,
                ease: "power3.inOut",
            })
                .to(
                    textStrokeRef.current,
                    {
                        strokeDashoffset: 0,
                        duration: 1.5,
                        ease: "power2.inOut",
                    },
                    "<", // start simultaneously with track appearance
                )
                // Phase 2 (Resolving & Loading)
                .to(
                    textFillRef.current,
                    {
                        opacity: 1,
                        duration: 0.8,
                        ease: "power2.out",
                    },
                    ">", // wait for drawing to complete before filling
                )
                .to(
                    fillRef.current,
                    {
                        width: "100%",
                        duration: 1.0,
                        ease: "power2.inOut",
                        boxShadow: "0 0 15px rgba(255,199,44,0.8)",
                    },
                    "<0.2", // start filling the bar right as text starts resolving
                )
                // Phase 3 (Exit Curtain Reveal)
                .to(
                    contentRef.current,
                    {
                        opacity: 0,
                        duration: 0.3,
                    },
                    ">0.2", // wait a beat after everything is fully loaded
                )
                .to(
                    curtainsRef.current,
                    {
                        yPercent: -100,
                        duration: 0.7,
                        stagger: 0.05,
                        ease: "power4.inOut",
                        transformOrigin: "top",
                    },
                    "<", // simultaneous with the fade-out of inner elements
                );
        },
        { scope: containerRef },
    );

    if (!isMounted) return null;

    return (
        <div
            ref={containerRef}
            className="fixed inset-0 z-9999 pointer-events-auto"
            aria-hidden
        >
            {/* The Curtains Background */}
            <div className="absolute inset-0 w-full h-full overflow-hidden">
                {Array.from({ length: numCurtains }).map((_, i) => (
                    <div
                        key={i}
                        ref={(el) => {
                            curtainsRef.current[i] = el;
                        }}
                        className="absolute top-0 bottom-0 bg-[#00426D]"
                        style={{
                            left: `${(i / numCurtains) * 100}%`,
                            width: `calc(${100 / numCurtains}% + 2px)`,
                            willChange: "transform",
                        }}
                    />
                ))}
            </div>

            {/* The Content Overlay */}
            <div
                ref={contentRef}
                className="absolute inset-0 flex flex-col items-center justify-center gap-8 pointer-events-none"
                style={{ willChange: "opacity" }}
            >
                {/* The SVG HUD Overlay */}
                <div className="relative w-full max-w-5xl px-4 flex justify-center">
                    <svg
                        viewBox="0 0 1000 150"
                        className="w-full h-auto drop-shadow-[0_0_15px_rgba(0,168,181,0.6)]"
                    >
                        {/* Stroke Tracing Text */}
                        <text
                            ref={textStrokeRef}
                            x="50%"
                            y="50%"
                            dominantBaseline="middle"
                            textAnchor="middle"
                            fontSize="80"
                            fontWeight="900"
                            letterSpacing="0.05em"
                            className="uppercase"
                            style={{
                                fill: "transparent",
                                stroke: "#00A8B5",
                                strokeWidth: "2px",
                                willChange: "stroke-dashoffset",
                            }}
                        >
                            IDIG Laboratory
                        </text>

                        {/* Solid Fill Text */}
                        <text
                            ref={textFillRef}
                            x="50%"
                            y="50%"
                            dominantBaseline="middle"
                            textAnchor="middle"
                            fontSize="80"
                            fontWeight="900"
                            letterSpacing="0.05em"
                            className="uppercase"
                            style={{
                                fill: "white",
                                willChange: "opacity",
                            }}
                        >
                            IDIG Laboratory
                        </text>
                    </svg>
                </div>

                {/* HUD Progress Track */}
                <div
                    ref={trackRef}
                    className="relative w-64 md:w-96 h-1 bg-[#00426D]/50 border border-[#00A8B5]/30 overflow-hidden"
                    style={{ willChange: "transform" }}
                >
                    {/* HUD Fill */}
                    <div
                        ref={fillRef}
                        className="absolute top-0 left-0 h-full bg-[#FFC72C]"
                        style={{ willChange: "width, box-shadow" }}
                    />
                </div>

                {/* Minimal HUD Decorators */}
                <div className="absolute bottom-8 right-8 text-[#00A8B5]/50 font-mono text-xs tracking-widest uppercase">
                    SYS.INIT.SEQ // ACTIVE
                </div>
                <div className="absolute top-8 left-8 text-[#00A8B5]/50 font-mono text-xs tracking-widest uppercase">
                    V. 2.0.4 // IDIG
                </div>
            </div>
        </div>
    );
}
