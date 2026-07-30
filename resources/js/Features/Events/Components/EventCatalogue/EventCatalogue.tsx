import { useState } from "react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import EmptyStateCard from "@/Features/Dashboard/Components/EmptyStateCard/EmptyStateCard";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { cn } from "@/Core/Utils/utils";
import EventCard from "./fragments/EventCard";
import { statusLabels } from "./fragments/EventStatusBadge";
import { type EventStatus, type EventSummary } from "@/Features/Events/Types/event.type";

interface EventCatalogueProps {
    events: EventSummary[];
}

type Filter = "all" | EventStatus;

/** Chronological reading order: what's on, what's next, what happened. */
const filters: { id: Filter; label: string }[] = [
    { id: "all", label: "All" },
    { id: "ongoing", label: statusLabels.ongoing },
    { id: "upcoming", label: statusLabels.upcoming },
    { id: "past", label: statusLabels.past },
];

export default function EventCatalogue({ events }: EventCatalogueProps) {
    const { t } = useTranslation();
    const [active, setActive] = useState<Filter>("all");

    // Empty tabs are hidden rather than shown dead — with only a handful of
    // events, offering a filter that resolves to nothing is just a dead end.
    const available = filters.filter(
        (f) => f.id === "all" || events.some((e) => e.status === f.id),
    );

    const visible = active === "all" ? events : events.filter((e) => e.status === active);

    return (
        <Box as="section" aria-labelledby="event-catalogue-heading">
            <Box className="mb-6 flex flex-wrap items-end justify-between gap-4">
                <Box>
                    <Heading
                        level={2}
                        id="event-catalogue-heading"
                        className="font-display text-2xl font-bold text-slate-800"
                    >
                        {t("All Events")}
                    </Heading>
                    <Text className="mt-1 text-sm text-slate-500">
                        {t("Every edition the laboratory has run, with the teams behind them.")}
                    </Text>
                </Box>

                {available.length > 1 && (
                    <Box
                        role="tablist"
                        aria-label={t("Filter events by status")}
                        className="scrollbar-none flex max-w-full gap-2 overflow-x-auto pb-1"
                    >
                        {available.map((filter) => {
                            const isActive = active === filter.id;

                            return (
                                <button
                                    key={filter.id}
                                    type="button"
                                    role="tab"
                                    aria-selected={isActive}
                                    onClick={() => setActive(filter.id)}
                                    className={cn(
                                        "shrink-0 cursor-pointer rounded-full px-4 py-1.5 text-sm font-medium whitespace-nowrap",
                                        "transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-secondary-500/50 focus-visible:ring-offset-2 focus-visible:outline-none",
                                        isActive
                                            ? "bg-primary-700 text-white shadow-sm"
                                            : "bg-slate-200 text-slate-600 hover:bg-slate-300",
                                    )}
                                >
                                    {t(filter.label)}
                                </button>
                            );
                        })}
                    </Box>
                )}
            </Box>

            {visible.length > 0 ? (
                <Box className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    {visible.map((event) => (
                        <EventCard key={event.id} event={event} />
                    ))}
                </Box>
            ) : (
                <EmptyStateCard
                    config={{
                        illustrationKey: "no-events",
                        title: t("No events yet"),
                        body: t("New editions are announced here as soon as they are scheduled."),
                    }}
                />
            )}
        </Box>
    );
}
