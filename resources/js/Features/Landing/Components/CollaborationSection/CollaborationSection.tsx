import { useRef } from "react";
import { usePage } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { partners } from "../../Data/collaborationSection.data";
import type { CollaborationPartner } from "../../Types/collaborationSection.type";
import { safeJsonParse } from "../../Utils/safeJsonParse";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { useCollaborationSectionAnimation } from "../../Hooks/useCollaborationSectionAnimation";
import PartnerChapter from "./fragments/PartnerChapter";

/** CMS rows are already per-locale; only the bundled defaults go through t(). */
function buildPartner(
    raw: string | undefined,
    fallback: CollaborationPartner,
    t: (k: string) => string,
): CollaborationPartner {
    // Partner and institution names are proper nouns — never translated.
    const translated: CollaborationPartner = {
        ...fallback,
        type: t(fallback.type),
        period: t(fallback.period),
        description: t(fallback.description),
    };
    const parsed = safeJsonParse<Record<string, unknown>>(raw, {});
    if (!parsed.name) return translated;

    const images = Array.isArray(parsed.images)
        ? (parsed.images as string[])
        : [];

    return {
        ...translated,
        name: (parsed.name as string) ?? fallback.name,
        nameLines: [
            (parsed.name_line_1 as string) ?? fallback.nameLines[0],
            (parsed.name_line_2 as string) ?? fallback.nameLines[1],
        ],
        type: (parsed.type as string) ?? translated.type,
        period: (parsed.period as string) ?? translated.period,
        description: (parsed.description as string) ?? translated.description,
        // Photo swaps only — print layout (position/rotation/shadow) stays code-side.
        prints: fallback.prints.map((print, i) => ({
            ...print,
            image: images[i] ?? print.image,
        })),
    };
}

export default function CollaborationSection() {
    const containerRef = useRef<HTMLElement>(null);
    const { t } = useTranslation();

    useCollaborationSectionAnimation(containerRef);

    const lc: Record<string, string> = (usePage().props as any).landingContent ?? {};

    const heading = lc.collaboration_heading ?? t("In Collaboration");
    const subheading = lc.collaboration_subheading ?? t("With Our Partners.");
    const body =
        lc.collaboration_body ??
        t("Every partnership documented — from clinical validation to training programmes, these are the institutions building health technology innovation with us.");

    const derivedPartners = partners.map((partner, i) =>
        buildPartner(lc[`collaboration_chapter_${i + 1}`], partner, t),
    );

    return (
        <Box
            as="section"
            id="collaboration"
            ref={containerRef}
            className="relative bg-primary-900 z-10"
        >
            {/* Ambient glow behind the intro */}
            <Box
                aria-hidden="true"
                className="absolute top-0 left-0 w-[70vw] h-[50vh] pointer-events-none"
                style={{
                    background:
                        "radial-gradient(ellipse at 18% 0%, rgba(0,168,181,0.10), transparent 60%)",
                }}
            />

            <Box className="collab-intro relative z-10 min-h-screen flex flex-col items-center justify-center text-center max-w-6xl mx-auto px-6 md:px-12">
                <Box className="flex items-center gap-3 mb-8">
                    <Box className="w-8 h-px bg-secondary-400/40" />
                    <Text
                        as="span"
                        className="text-[0.68rem] font-body font-semibold tracking-[0.3em] uppercase text-secondary-400/70"
                    >
                        {t("Collaboration & Partnership")}
                    </Text>
                </Box>

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

                <Text className="mt-8 text-lg md:text-xl font-body text-white/70 max-w-[65ch] leading-relaxed text-pretty">
                    {body}
                </Text>
            </Box>

            {/* The cinema stage: pinned + scrubbed on md/motion-safe, plain flow otherwise */}
            <Box className="collab-stage relative md:motion-safe:h-screen md:motion-safe:overflow-hidden">
                {/* Honeycomb pocket */}
                <Box
                    aria-hidden="true"
                    className="absolute inset-y-0 right-0 w-1/2 honeycomb-dark opacity-[0.05] pointer-events-none"
                />

                {/* Ambient glow — lower left of the stage */}
                <Box
                    aria-hidden="true"
                    className="absolute bottom-0 left-0 w-[55vw] h-[50vh] pointer-events-none"
                    style={{
                        background:
                            "radial-gradient(ellipse at 12% 95%, rgba(0,168,181,0.09), transparent 58%)",
                    }}
                />

                {/* Ghost record numerals (cinema mode only) */}
                <Box
                    aria-hidden="true"
                    className="hidden md:motion-safe:block absolute bottom-8 right-8 md:right-14 w-[11rem] h-[7rem] pointer-events-none select-none"
                >
                    {derivedPartners.map((_, i) => (
                        <Text
                            as="span"
                            key={i}
                            className="collab-ghost absolute inset-0 text-right font-display font-extrabold text-[7rem] leading-none text-white/4"
                        >
                            {String(i + 1).padStart(2, "0")}
                        </Text>
                    ))}
                </Box>

                {/* Progress rail (cinema mode only) */}
                <Box className="collab-rail hidden md:motion-safe:block absolute left-[clamp(16px,3.2vw,44px)] top-1/2 -translate-y-1/2 z-20">
                    <Box className="relative h-[min(50vh,420px)] flex flex-col justify-between">
                        <Box
                            aria-hidden="true"
                            className="absolute left-[5px] top-0 bottom-0 w-px bg-white/12"
                        />
                        <Box
                            aria-hidden="true"
                            className="collab-rail-fill absolute left-[5px] top-0 bottom-0 w-px bg-secondary-400 origin-top"
                        />
                        {derivedPartners.map((partner, i) => (
                            <button
                                key={partner.name}
                                type="button"
                                className="collab-rail-node relative flex items-center gap-3 cursor-pointer focus-visible:outline-2 focus-visible:outline-secondary-400 focus-visible:outline-offset-4"
                                aria-label={`${t("View partner")} ${String(i + 1).padStart(2, "0")}: ${partner.name}`}
                            >
                                <Box
                                    as="span"
                                    className="collab-rail-dot block w-[11px] h-[11px] rounded-full border border-white/35 bg-primary-900"
                                />
                                <Text
                                    as="span"
                                    className="collab-rail-num font-display text-[0.7rem] font-bold tracking-[0.18em] text-white/55"
                                >
                                    {String(i + 1).padStart(2, "0")}
                                </Text>
                            </button>
                        ))}
                    </Box>
                </Box>

                {/* Chapters: flow list on mobile/reduced-motion, stacked scenes in cinema mode */}
                <Box className="collab-chapters relative z-10 flex flex-col gap-24 lg:gap-36 px-6 md:px-12 pb-28 md:pb-32 md:motion-safe:block md:motion-safe:h-full md:motion-safe:p-0">
                    {derivedPartners.map((partner, i) => (
                        <PartnerChapter
                            key={partner.name}
                            partner={partner}
                            index={i}
                        />
                    ))}
                </Box>
            </Box>
        </Box>
    );
}
