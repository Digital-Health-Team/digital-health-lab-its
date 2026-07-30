import { useState } from "react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import EmptyStateCard from "@/Features/Dashboard/Components/EmptyStateCard/EmptyStateCard";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { cn } from "@/Core/Utils/utils";
import CourseCard from "@/Features/Training/Components/FeaturedClasses/fragments/CourseCard";
import EventCard from "./fragments/EventCard";
import { statusLabels } from "./fragments/EventStatusBadge";
import {
    type CatalogueItem,
    type EventCategory,
    type EventStatus,
} from "@/Features/Events/Types/event.type";

interface EventCatalogueProps {
    items: CatalogueItem[];
}

type CategoryFilter = "all" | EventCategory;
type StatusFilter = "all" | EventStatus;

/**
 * Plural in the UI, singular in the database — mirrors Event::CATEGORIES.
 * Each row names its own reset pill: two stacked rows both reading "All" gives
 * no clue which axis you just cleared.
 */
const categoryFilters: { id: CategoryFilter; label: string }[] = [
    { id: "all", label: "All categories" },
    { id: "Exhibition", label: "Exhibitions" },
    { id: "Seminar", label: "Seminars" },
    { id: "Workshop", label: "Workshops" },
];

/** Chronological reading order: what's on, what's next, what happened. */
const statusFilters: { id: StatusFilter; label: string }[] = [
    { id: "all", label: "Any date" },
    { id: "ongoing", label: statusLabels.ongoing },
    { id: "upcoming", label: statusLabels.upcoming },
    { id: "past", label: statusLabels.past },
];

interface TabRowProps<T extends string> {
    label: string;
    tabs: { id: T; label: string }[];
    active: T;
    onSelect: (id: T) => void;
}

function TabRow<T extends string>({ label, tabs, active, onSelect }: TabRowProps<T>) {
    const { t } = useTranslation();

    return (
        <Box
            role="tablist"
            aria-label={label}
            className="scrollbar-none flex max-w-full gap-2 overflow-x-auto pb-1"
        >
            {tabs.map((tab) => {
                const isActive = active === tab.id;

                return (
                    <button
                        key={tab.id}
                        type="button"
                        role="tab"
                        aria-selected={isActive}
                        onClick={() => onSelect(tab.id)}
                        className={cn(
                            "shrink-0 cursor-pointer rounded-full px-4 py-1.5 text-sm font-medium whitespace-nowrap",
                            "transition-colors duration-200 focus-visible:ring-2 focus-visible:ring-secondary-500/50 focus-visible:ring-offset-2 focus-visible:outline-none",
                            isActive
                                ? "bg-primary-700 text-white shadow-sm"
                                : "bg-slate-200 text-slate-600 hover:bg-slate-300",
                        )}
                    >
                        {t(tab.label)}
                    </button>
                );
            })}
        </Box>
    );
}

export default function EventCatalogue({ items }: EventCatalogueProps) {
    const { t } = useTranslation();
    const [category, setCategory] = useState<CategoryFilter>("all");
    const [status, setStatus] = useState<StatusFilter>("all");

    // Empty tabs are hidden rather than shown dead — offering a filter that
    // resolves to nothing is just a dead end.
    const availableCategories = categoryFilters.filter(
        (f) => f.id === "all" || items.some((i) => i.group === f.id),
    );
    const availableStatuses = statusFilters.filter(
        (f) => f.id === "all" || items.some((i) => i.status === f.id),
    );

    const visible = items
        .filter((i) => category === "all" || i.group === category)
        .filter((i) => status === "all" || i.status === status);

    return (
        <Box as="section" aria-labelledby="event-catalogue-heading">
            {/* Filters sit under the title, not beside it — two rows alongside a
                heading only fit at the widest breakpoint and wrap unevenly below it. */}
            <Box className="mb-6">
                <Heading
                    level={2}
                    id="event-catalogue-heading"
                    className="font-display text-2xl font-bold text-slate-800"
                >
                    {t("All Events")}
                </Heading>
                <Text className="mt-1 text-sm text-slate-500">
                    {t("Exhibitions, seminars and workshops the laboratory runs, past and upcoming.")}
                </Text>

                <Box className="mt-4 flex flex-col gap-2">
                    {availableCategories.length > 1 && (
                        <TabRow
                            label={t("Filter events by category")}
                            tabs={availableCategories}
                            active={category}
                            onSelect={setCategory}
                        />
                    )}
                    {availableStatuses.length > 1 && (
                        <TabRow
                            label={t("Filter events by status")}
                            tabs={availableStatuses}
                            active={status}
                            onSelect={setStatus}
                        />
                    )}
                </Box>
            </Box>

            {visible.length > 0 ? (
                // items-start so a short course card doesn't stretch to an event card's height.
                <Box className="grid grid-cols-1 items-start gap-6 md:grid-cols-2 lg:grid-cols-3">
                    {visible.map((item) =>
                        item.kind === "event" ? (
                            <EventCard key={`event-${item.id}`} event={item} />
                        ) : (
                            <CourseCard key={`training-${item.id}`} course={item} />
                        ),
                    )}
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
