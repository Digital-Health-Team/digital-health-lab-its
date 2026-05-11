import { useRef } from "react";
import { usePreloader } from "../../Hooks/usePreloader";

export default function Preloader(): React.JSX.Element | null {
    const containerRef = useRef<HTMLDivElement>(null);
    const curtainsRef = useRef<(HTMLDivElement | null)[]>([]);
    const contentRef = useRef<HTMLDivElement>(null);
    const textStrokeRef = useRef<SVGTextElement>(null);
    const textFillRef = useRef<SVGTextElement>(null);
    const trackRef = useRef<HTMLDivElement>(null);
    const fillRef = useRef<HTMLDivElement>(null);

    const { isMounted, numCurtains } = usePreloader({
        containerRef,
        curtainsRef,
        contentRef,
        textStrokeRef,
        textFillRef,
        trackRef,
        fillRef,
    });

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
