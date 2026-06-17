import { useRef } from "react";
import React from "react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { usePameranPreloader } from "@/Features/Pameran/Hooks/usePameranPreloader";
import { usePameranPreloaderAnimation } from "@/Features/Pameran/Hooks/usePameranPreloaderAnimation";
import { pameranData } from "@/Features/Pameran/Data/pameran.data";

/** How many frames from reelImages to cycle through. */
const PRELOADER_IMAGE_COUNT = 7;
const preloaderImages = pameranData.reelImages.slice(0, PRELOADER_IMAGE_COUNT);

export default function PameranPreloader(): React.JSX.Element | null {
    const containerRef = useRef<HTMLDivElement>(null);
    const imagesRef = useRef<(HTMLDivElement | null)[]>([]);
    const greetingRef = useRef<HTMLDivElement>(null);
    const counterRef = useRef<HTMLDivElement>(null);

    const { isMounted, prefersReducedMotion, handleAnimationComplete } =
        usePameranPreloader();

    usePameranPreloaderAnimation({
        containerRef,
        imagesRef,
        greetingRef,
        counterRef,
        prefersReducedMotion,
        onComplete: handleAnimationComplete,
    });

    if (!isMounted) return null;

    return (
        <Box
            ref={containerRef}
            className="fixed inset-0 z-[9999] bg-black overflow-hidden pointer-events-auto"
            role="status"
            aria-label="Memuat halaman pameran InnovaTech 2026"
        >
            {/*
             * Layer 1: Image stack — background-image divs stacked absolutely.
             * Each frame cross-fades in over the previous while slowly drifting
             * (Ken Burns scale via GSAP). willChange hints GPU compositing.
             */}
            {preloaderImages.map((img, i) => (
                <Box
                    key={i}
                    ref={(el: HTMLDivElement | null) => {
                        imagesRef.current[i] = el;
                    }}
                    className="absolute inset-0 bg-cover bg-center"
                    style={{
                        backgroundImage: `url(${img.src})`,
                        opacity: 0,
                        willChange: "transform, opacity",
                    }}
                    aria-hidden
                />
            ))}

            {/*
             * Layer 2: Midnight radial scrim — keeps the centered greeting legible
             * over bright photo regions. Intentional depth per design system, not
             * glassmorphism.
             */}
            <Box
                className="absolute inset-0 pointer-events-none"
                style={{
                    background:
                        "radial-gradient(ellipse 70% 60% at 50% 45%, rgba(3,16,38,0.72) 0%, rgba(3,16,38,0.35) 55%, transparent 100%)",
                }}
                aria-hidden
            />

            {/*
             * Layer 3: Centered greeting — eyebrow + display.
             * Italic Display Rule: "InnovaTech 2026" set in Plus Jakarta Sans
             * extrabold italic. Electric Teal (#22D3EE) is the signal color on dark.
             * Items tagged .greeting-item for GSAP stagger targeting within scope.
             */}
            <Box
                ref={greetingRef}
                className="absolute inset-0 flex flex-col items-center justify-center gap-3 pointer-events-none"
                aria-hidden
            >
                {/* Eyebrow */}
                <Text
                    as="span"
                    className="greeting-item font-body font-medium uppercase text-[#22D3EE]"
                    style={{
                        fontSize: "clamp(0.65rem, 1.5vw, 0.875rem)",
                        letterSpacing: "0.3em",
                        opacity: 0,
                    }}
                >
                    Selamat Datang di
                </Text>

                {/* Display — Italic Display Rule */}
                <Heading
                    level={1}
                    className="greeting-item font-display font-extrabold italic text-[#22D3EE] text-center p-0 m-0"
                    style={{
                        fontSize: "clamp(2.2rem, 7vw, 5rem)",
                        letterSpacing: "-0.02em",
                        lineHeight: 1.05,
                        textRendering: "optimizeLegibility",
                        WebkitFontSmoothing: "antialiased",
                        textShadow:
                            "0 0 40px rgba(34,211,238,0.45), 0 0 90px rgba(34,211,238,0.18)",
                        opacity: 0,
                    } as React.CSSProperties}
                >
                    InnovaTech 2026
                </Heading>
            </Box>

            {/*
             * Layer 4: Percentage counter — mix-blend-difference keeps it legible
             * regardless of image brightness. Text updated imperatively by GSAP.
             */}
            <Box
                ref={counterRef}
                className="absolute bottom-8 left-8 font-display font-extrabold text-white mix-blend-difference select-none pointer-events-none"
                style={{
                    fontSize: "clamp(4rem, 18vw, 12rem)",
                    lineHeight: 1,
                    fontFeatureSettings: '"tnum"',
                    WebkitFontSmoothing: "antialiased",
                } as React.CSSProperties}
                aria-hidden
            >
                00
            </Box>
        </Box>
    );
}
