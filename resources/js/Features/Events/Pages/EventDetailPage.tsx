import { Head, usePage } from "@inertiajs/react";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import { Box } from "@/Core/Components/Common/Box";
import Breadcrumb from "@/Core/Components/Shared/Breadcrumb/Breadcrumb";
import EventPreview from "@/Features/Events/Components/EventDetail/EventPreview";
import EventOverview from "@/Features/Events/Components/EventDetail/EventOverview";
import EventTeams from "@/Features/Events/Components/EventDetail/EventTeams";
import EventInfoCard from "@/Features/Events/Components/EventDetail/EventInfoCard";
import RelatedEvents from "@/Features/Events/Components/EventDetail/RelatedEvents";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { type EventDetail, type EventSummary } from "@/Features/Events/Types/event.type";

interface EventDetailPageProps {
    event: EventDetail;
    related: EventSummary[];
    [key: string]: unknown;
}

export default function EventDetailPage() {
    const { props } = usePage<EventDetailPageProps>();
    const { event, related } = props;
    const { t } = useTranslation();

    return (
        <>
            <Head title={event.name} />
            <DashboardLayout>
                <Breadcrumb
                    items={[
                        { label: t("Home"), href: "/" },
                        { label: t("Events"), href: "/events" },
                        { label: event.name },
                    ]}
                />

                <Box className="grid grid-cols-1 gap-6 lg:grid-cols-12 lg:gap-8">
                    <Box className="order-1 lg:order-none lg:col-span-8 lg:col-start-1 lg:row-start-1">
                        <EventPreview event={event} />
                    </Box>

                    <Box className="order-2 lg:order-none lg:col-span-4 lg:col-start-9 lg:row-span-2 lg:row-start-1">
                        <EventInfoCard event={event} />
                    </Box>

                    <Box className="order-3 flex flex-col gap-6 lg:order-none lg:col-span-8 lg:col-start-1 lg:row-start-2">
                        <EventOverview description={event.description} />
                        <EventTeams teams={event.teams} />
                    </Box>
                </Box>

                <RelatedEvents related={related} />
            </DashboardLayout>
        </>
    );
}
