import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { useTranslation } from "@/Core/Hooks/useTranslation";

interface ResearchSectionHeaderProps {
    /** Short label above the title. Sentence case — matches the hero's eyebrow voice. */
    eyebrow: string;
    title: string;
}

/**
 * Chapter break between the two halves of the Research page.
 * The cyan lives on the accent rule, not the eyebrow text: secondary-400/500 on the
 * light canvas is ~2.8:1, well under the 4.5:1 AA floor for label-sized text.
 */
export default function ResearchSectionHeader({ eyebrow, title }: ResearchSectionHeaderProps) {
    const { t } = useTranslation();

    return (
        <Box className="mb-6">
            <Text
                as="span"
                className="block text-[11px] italic font-medium text-slate-600 tracking-[0.2em] mb-1.5"
            >
                {t(eyebrow)}
            </Text>

            <Heading
                level={2}
                className="font-display text-2xl md:text-3xl font-bold text-slate-800 text-balance mb-3"
            >
                {t(title)}
            </Heading>

            {/* Accent rule — decorative, so the cyan carries no information */}
            <Box className="flex items-center" aria-hidden="true">
                <Box className="h-0.5 w-10 rounded-full bg-secondary-500" />
                <Box className="h-px flex-1 bg-slate-200" />
            </Box>
        </Box>
    );
}
