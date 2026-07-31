import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { useTranslation } from "@/Core/Hooks/useTranslation";

interface EventOverviewProps {
    description?: string | null;
}

export default function EventOverview({ description }: EventOverviewProps) {
    const { t } = useTranslation();

    if (!description?.trim()) return null;

    const paragraphs = description.split(/\n\s*\n/).filter((p) => p.trim());

    return (
        <Box
            as="section"
            aria-labelledby="event-overview-heading"
            className="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm sm:p-6"
        >
            <Heading
                level={2}
                id="event-overview-heading"
                className="font-display mb-4 text-lg font-bold text-slate-800"
            >
                {t("About this event")}
            </Heading>

            <Box className="flex flex-col gap-4">
                {paragraphs.map((paragraph, i) => (
                    <Text
                        key={i}
                        className="max-w-[68ch] text-sm leading-relaxed text-pretty text-slate-600"
                    >
                        {paragraph.trim()}
                    </Text>
                ))}
            </Box>
        </Box>
    );
}
