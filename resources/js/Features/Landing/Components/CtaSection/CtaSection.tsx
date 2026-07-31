import { useRef } from "react";
import { usePage } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Image } from "@/Core/Components/Common/Image";
import { Text } from "@/Core/Components/Common/Text";
import { products, register } from "@/routes";
import { useCtaSectionAnimation } from "../../Hooks/useCtaSectionAnimation";
import EcgLine from "../WisdomSection/fragments/EcgLine";

/**
 * Closing CTA — the "midnight prelude" to ContactSection. Shares Contact's
 * near-midnight world (primary-950 photo overlay → Contact's rgba(3,16,38,…)
 * gradient) but owns the typographic statement + ECG heartbeat horizon;
 * Contact owns the form.
 */
export default function CtaSection() {
    const containerRef = useRef<HTMLElement>(null);

    useCtaSectionAnimation(containerRef);

    const lc: Record<string, string> = (usePage().props as any).landingContent ?? {};

    const heading = lc.cta_heading ?? "Masih Ingin Tahu";
    const subheading = lc.cta_subheading ?? "Lebih Dalam?";
    const body =
        lc.cta_body ??
        "Buat akun untuk mengarsipkan karya Anda, memesan layanan fabrikasi, dan menjadi bagian dari ekosistem inovasi teknologi kesehatan ITS.";
    const primaryLabel = lc.cta_primary_label ?? "Daftar Sekarang";
    const secondaryLabel = lc.cta_secondary_label ?? "Jelajahi Produk";

    return (
        <Box
            as="section"
            id="explore"
            ref={containerRef}
            className="relative py-[clamp(96px,16vh,160px)] px-6 md:px-12 overflow-hidden"
        >
            {/* Background photo */}
            <Image
                src="/assets/images/hero/prosthetic_hand_banner.png"
                alt=""
                aria-hidden="true"
                objectFit="cover"
                className="absolute inset-0 w-full h-full"
            />

            {/* Primary blue overlay */}
            <Box
                aria-hidden="true"
                className="absolute inset-0 bg-primary-950/85"
            />

            {/* Honeycomb whisper — same family as ContactSection's 0.035 */}
            <Box
                aria-hidden="true"
                className="absolute inset-0 honeycomb-dark opacity-[0.04] pointer-events-none"
            />

            {/* Ambient teal glow — upper right */}
            <Box
                aria-hidden="true"
                className="absolute top-0 right-0 w-[60vw] h-[60vh] pointer-events-none"
                style={{
                    background:
                        "radial-gradient(ellipse at 85% 10%, rgba(0,168,181,0.10), transparent 60%)",
                }}
            />

            <Box className="relative z-10 max-w-4xl mx-auto flex flex-col items-center text-center">
                <Box className="cta-intro flex flex-col items-center">
                    <Heading
                        level={2}
                        className="font-display tracking-tight text-balance"
                    >
                        <Text
                            as="span"
                            className="block text-4xl md:text-6xl leading-[1.05] font-extrabold text-white"
                        >
                            {heading}
                        </Text>
                        <Text
                            as="span"
                            className="block text-4xl md:text-6xl leading-[1.05] font-light italic text-secondary-400"
                        >
                            {subheading}
                        </Text>
                    </Heading>

                    <Text className="mt-7 text-lg md:text-xl font-body text-white/70 max-w-[65ch] leading-relaxed text-pretty">
                        {body}
                    </Text>
                </Box>

                {/* ECG horizon — the heartbeat between statement and action */}
                <Box className="cta-ecg relative w-full h-24 md:h-28 my-6 md:my-8">
                    <EcgLine />
                </Box>

                <Box className="cta-actions flex flex-col sm:flex-row items-center gap-6 sm:gap-9">
                    {/* Wrappers take the GSAP reveal; anchors keep CSS hover
                        transitions — never both on one element. */}
                    <Box>
                    <a
                        href={register().url}
                        className="group relative overflow-hidden px-10 py-[19px] rounded-full font-body font-bold text-base border-2 border-transparent flex items-center text-white transition-transform duration-500 ease-[cubic-bezier(0.25,1,0.5,1)] hover:scale-105 active:scale-95 focus-visible:outline-2 focus-visible:outline-secondary-400 focus-visible:outline-offset-4 before:absolute before:-inset-1 before:z-0 before:animate-[spin_4s_linear_infinite] before:blur-sm before:opacity-60 hover:before:opacity-90 before:transition-opacity before:duration-500 before:bg-[conic-gradient(from_0deg,var(--color-primary-600)_0deg,var(--color-accent-400)_120deg,var(--color-primary-600)_240deg,var(--color-accent-400)_360deg)] after:absolute after:inset-px after:z-1 after:rounded-[inherit] after:bg-primary-900/80 after:ring-1 after:ring-inset after:ring-white/15"
                    >
                        <Text
                            as="span"
                            className="relative z-10 flex items-center gap-2 font-body font-bold text-base text-white leading-none"
                        >
                            {primaryLabel}
                            <svg
                                className="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1 group-hover:-rotate-12"
                                viewBox="0 0 14 14"
                                fill="none"
                                aria-hidden="true"
                            >
                                <path
                                    d="M2 7H12M9 3.5L12 7L9 10.5"
                                    stroke="currentColor"
                                    strokeWidth="1.5"
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                />
                            </svg>
                        </Text>
                    </a>
                    </Box>

                    <Box>
                    <a
                        href={products().url}
                        className="group px-10 py-4 rounded-full font-body font-bold text-base text-secondary-400 border-2 border-secondary-400 flex items-center transition-all duration-500 ease-[cubic-bezier(0.25,1,0.5,1)] hover:scale-105 hover:bg-secondary-400/10 active:scale-95 focus-visible:outline-2 focus-visible:outline-secondary-400 focus-visible:outline-offset-4"
                    >
                        <Text
                            as="span"
                            className="flex items-center gap-2 font-body font-bold text-base text-secondary-400 leading-none"
                        >
                            {secondaryLabel}
                            <Text
                                as="span"
                                aria-hidden="true"
                                className="text-base text-inherit leading-none transition-transform duration-300 group-hover:translate-x-1"
                            >
                                →
                            </Text>
                        </Text>
                    </a>
                    </Box>
                </Box>
            </Box>
        </Box>
    );
}
