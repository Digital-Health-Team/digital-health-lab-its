import React from "react";
import { Box } from "@/Core/Components/Common/Box";
import { Container } from "@/Core/Components/Common/Container";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { pameranData } from "@/Features/Pameran/Data/pameran.data";

export default function PameranIntro(): React.JSX.Element {
    const { intro } = pameranData;

    return (
        <Box
            as="section"
            id="pameran-intro"
            className="relative bg-[#062E5C] overflow-hidden"
        >
            {/* Honeycomb texture — lighter than the hero stage */}
            <Box
                className="absolute inset-0 honeycomb-dark opacity-[0.06] pointer-events-none"
                aria-hidden
            />

            {/* Subtle top edge blend from hero */}
            <Box
                className="absolute top-0 left-0 right-0 h-16 pointer-events-none"
                aria-hidden
                style={{
                    background: "linear-gradient(to bottom, #031026, transparent)",
                }}
            />

            <Container className="relative z-10 py-24 md:py-32" style={{ maxWidth: "800px" }}>
                {/* Eyebrow */}
                <Box className="flex items-center gap-3 mb-8">
                    <Box className="h-px w-6 bg-[#22D3EE]/55" aria-hidden />
                    <Text
                        as="span"
                        className="font-body font-medium text-[#22D3EE] uppercase tracking-[0.36em] text-xs"
                    >
                        {intro.eyebrow}
                    </Text>
                </Box>

                {/* Section heading */}
                <Heading
                    level={2}
                    className="font-display font-bold text-[#F8FAFC] leading-[1.15] p-0 m-0 mb-8"
                    style={{
                        fontSize: "clamp(1.75rem, 4.5vw, 3rem)",
                        letterSpacing: "-0.01em",
                        maxWidth: "18ch",
                    }}
                >
                    {intro.heading}
                </Heading>

                {/* Body copy — generous leading, capped at ~65ch */}
                <Text
                    className="font-body text-[#94A3B8] leading-relaxed"
                    style={{ fontSize: "1.0625rem", maxWidth: "65ch" }}
                >
                    {intro.body}
                </Text>

                {/* English tag — bilingual by design */}
                <Box
                    className="mt-8 pt-8 flex items-center gap-3.5"
                    style={{
                        borderTop: "1px solid rgba(34,211,238,0.10)",
                        maxWidth: "65ch",
                    }}
                >
                    <Box className="h-4 w-0.5 bg-[#22D3EE]/55 shrink-0" aria-hidden />
                    <Text
                        as="span"
                        className="font-body italic text-[#22D3EE]/65 text-sm"
                    >
                        {intro.tag}
                    </Text>
                </Box>
            </Container>
        </Box>
    );
}
