import { Link } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import EventCard from "@/Features/Events/Components/EventCatalogue/fragments/EventCard";
import { type EventSummary } from "@/Features/Events/Types/event.type";

interface RelatedEventsProps {
    related: EventSummary[];
}

export default function RelatedEvents({ related }: RelatedEventsProps) {
    const { t } = useTranslation();

    if (related.length === 0) return null;

    return (
        <Box as="section" aria-labelledby="related-events-heading">
            <Box className="mb-5 flex items-center justify-between gap-4">
                <Heading
                    level={2}
                    id="related-events-heading"
                    className="font-display text-xl font-bold text-slate-800"
                >
                    {t("Other events")}
                </Heading>
                <Link
                    href="/events"
                    className="rounded text-sm font-semibold text-primary-700 transition-colors hover:text-primary-600 focus-visible:ring-2 focus-visible:ring-secondary-500/50 focus-visible:outline-none"
                >
                    {t("View all")}
                </Link>
            </Box>

            <Box className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                {related.map((event) => (
                    <EventCard key={event.id} event={event} />
                ))}
            </Box>
        </Box>
    );
}
