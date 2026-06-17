import React from "react";
import { ArrowDown } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Container } from "@/Core/Components/Common/Container";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { pameranData } from "@/Features/Pameran/Data/pameran.data";

export default function PameranHero(): React.JSX.Element {
    const { hero } = pameranData;

    return (
        <Box
            as="section"
            id="hero"
            className="relative min-h-screen flex items-center justify-center overflow-hidden bg-[#031026]"
        >
            {/* Honeycomb domain texture */}
            <Box
                className="absolute inset-0 honeycomb-dark opacity-[0.10] pointer-events-none"
                aria-hidden
            />

            {/* Radial teal light source — authority signal, not decoration */}
            <Box
                className="absolute inset-0 pointer-events-none"
                aria-hidden
                style={{
                    background:
                        "radial-gradient(ellipse 70% 60% at 50% 48%, rgba(0,168,181,0.10) 0%, rgba(0,168,181,0.03) 50%, transparent 75%)",
                }}
            />

            {/* Bottom section blend */}
            <Box
                className="absolute bottom-0 left-0 right-0 h-32 pointer-events-none"
                aria-hidden
                style={{
                    background: "linear-gradient(to bottom, transparent, #031026)",
                }}
            />

            <Container
                centerContent
                className="relative z-10 py-40 text-center"
                style={{ maxWidth: "760px" }}
            >
                {/* ITS institution label */}
                <Box className="flex items-center justify-center gap-3 mb-10">
                    <Box className="h-px w-8 bg-[#22D3EE]/40" aria-hidden />
                    <Text
                        as="span"
                        className="font-body font-medium text-[#22D3EE] uppercase tracking-[0.36em] text-xs"
                    >
                        {hero.preTitle}
                    </Text>
                    <Box className="h-px w-8 bg-[#22D3EE]/40" aria-hidden />
                </Box>

                {/* Exhibition type tag */}
                <Text
                    as="span"
                    className="font-body text-[#94A3B8] uppercase tracking-[0.24em] text-xs md:text-sm block mb-5"
                >
                    {hero.subtitle}
                </Text>

                {/* Primary title — extrabold italic per the Italic Display Rule */}
                <Heading
                    level={1}
                    className="font-display font-extrabold italic text-[#F8FAFC] leading-[1.0] p-0 m-0"
                    style={{
                        fontSize: "clamp(3.5rem, 10vw, 7.5rem)",
                        letterSpacing: "-0.02em",
                        textShadow:
                            "0 0 80px rgba(34,211,238,0.22), 0 0 160px rgba(34,211,238,0.08)",
                    }}
                >
                    {hero.title}
                </Heading>

                {/* Separator — measured restraint */}
                <Box
                    className="mt-8 mb-7 h-px mx-auto bg-[#22D3EE]/25"
                    style={{ width: "80px" }}
                    aria-hidden
                />

                {/* Description */}
                <Text
                    className="font-body text-[#94A3B8] leading-relaxed mx-auto"
                    style={{ fontSize: "1.0625rem", maxWidth: "52ch" }}
                >
                    {hero.description}
                </Text>

                {/* CTA */}
                <Box className="mt-10">
                    <a
                        href={hero.ctaHref}
                        className="inline-flex items-center gap-2.5 font-body font-semibold text-[#F8FAFC] uppercase tracking-[0.18em] px-10 py-4 rounded-2xl border border-[#22D3EE]/22 transition-all duration-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#22D3EE]/60 focus-visible:ring-offset-2 focus-visible:ring-offset-[#031026]"
                        style={{
                            fontSize: "0.8125rem",
                            backgroundColor: "rgba(34,211,238,0.07)",
                            boxShadow: "0 0 40px rgba(34,211,238,0.12)",
                        }}
                        onMouseEnter={(e) => {
                            (e.currentTarget as HTMLElement).style.backgroundColor =
                                "rgba(34,211,238,0.14)";
                            (e.currentTarget as HTMLElement).style.borderColor =
                                "rgba(34,211,238,0.45)";
                        }}
                        onMouseLeave={(e) => {
                            (e.currentTarget as HTMLElement).style.backgroundColor =
                                "rgba(34,211,238,0.07)";
                            (e.currentTarget as HTMLElement).style.borderColor =
                                "rgba(34,211,238,0.22)";
                        }}
                    >
                        {hero.ctaText}
                        <ArrowDown className="w-4 h-4 opacity-60" aria-hidden />
                    </a>
                </Box>

                {/* Institutional date stamp */}
                <Text
                    as="span"
                    className="font-body text-[#475569] uppercase tracking-[0.28em] block mt-16"
                    style={{ fontSize: "11px" }}
                >
                    {hero.date}
                </Text>
            </Container>
        </Box>
    );
}
