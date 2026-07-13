import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import type { CollaborationPartner } from "../../../Types/collaborationSection.type";
import PhotoCollage from "../../OrganizationSection/fragments/PhotoCollage";

interface PartnerChapterProps {
    partner: CollaborationPartner;
    index: number;
}

/**
 * One entry of the collaboration record: numbered name-mark + metadata +
 * description on one side, a cluster of archival documentation prints on
 * the other. Sides alternate per `partner.align`.
 *
 * Two layout modes from the same markup:
 * - Mobile / reduced-motion: normal flow, stacked by the chapters wrapper.
 * - Desktop cinema pin (`md:motion-safe:*`, mirrors MEDIA_DESKTOP): chapters
 *   are absolutely stacked inside the pinned `.collab-stage` and scrubbed
 *   in/out by useCollaborationSectionAnimation.
 */
export default function PartnerChapter({ partner, index }: PartnerChapterProps) {
    const isLeft = partner.align === "left";
    const numeral = String(index + 1).padStart(2, "0");

    return (
        <Box
            className="collab-chapter relative md:motion-safe:absolute md:motion-safe:inset-0 md:motion-safe:flex md:motion-safe:items-center md:motion-safe:px-[clamp(88px,8vw,120px)]"
            data-align={partner.align}
        >
            <Box className="collab-chapter-inner grid md:grid-cols-2 gap-12 lg:gap-20 items-center w-full max-w-6xl mx-auto">
                <Box
                    className={`collab-chapter-text flex flex-col ${
                        isLeft ? "md:order-1" : "md:order-2"
                    }`}
                >
                    <Box className="flex items-center gap-4 mb-7">
                        <Text
                            as="span"
                            aria-hidden="true"
                            className="font-display text-sm font-bold tracking-[0.2em] leading-none text-secondary-400/60"
                        >
                            {numeral}
                        </Text>
                        <Box className="h-px w-full max-w-[120px] bg-secondary-400/30" />
                    </Box>

                    <Heading
                        level={3}
                        className="font-display tracking-tight text-balance"
                    >
                        <Text
                            as="span"
                            className="block text-3xl md:text-4xl lg:text-[2.75rem] leading-[1.08] font-extrabold text-white"
                        >
                            {partner.nameLines[0]}
                        </Text>
                        <Text
                            as="span"
                            className="block text-3xl md:text-4xl lg:text-[2.75rem] leading-[1.08] font-light italic text-secondary-400"
                        >
                            {partner.nameLines[1]}
                        </Text>
                    </Heading>

                    <Text
                        as="p"
                        className="mt-5 text-[0.72rem] font-body font-bold tracking-[0.22em] uppercase text-secondary-400"
                    >
                        {partner.type} · {partner.period}
                    </Text>

                    <Text className="mt-6 text-base md:text-lg font-body text-white/70 leading-relaxed max-w-[52ch] text-pretty">
                        {partner.description}
                    </Text>
                </Box>

                <Box
                    className={`collab-chapter-prints relative w-full aspect-[4/3] ${
                        isLeft ? "md:order-2" : "md:order-1"
                    }`}
                >
                    <PhotoCollage
                        items={partner.prints}
                        centerClass="collab-print-center"
                        itemClass="collab-print-item"
                    />
                </Box>
            </Box>
        </Box>
    );
}
