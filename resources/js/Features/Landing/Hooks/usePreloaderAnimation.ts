import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import React from "react";

interface UsePreloaderAnimationProps {
    containerRef: React.RefObject<HTMLDivElement | null>;
    curtainsRef: React.MutableRefObject<(HTMLDivElement | null)[]>;
    contentRef: React.RefObject<HTMLDivElement | null>;
    textStrokeRef: React.RefObject<SVGTextElement | null>;
    textFillRef: React.RefObject<SVGTextElement | null>;
    trackRef: React.RefObject<HTMLDivElement | null>;
    fillRef: React.RefObject<HTMLDivElement | null>;
    onComplete: () => void;
}

export function usePreloaderAnimation({
    containerRef,
    curtainsRef,
    contentRef,
    textStrokeRef,
    textFillRef,
    trackRef,
    fillRef,
    onComplete,
}: UsePreloaderAnimationProps) {
    useGSAP(
        () => {
            if (!containerRef.current) return;

            const tl = gsap.timeline({ onComplete });

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
}
