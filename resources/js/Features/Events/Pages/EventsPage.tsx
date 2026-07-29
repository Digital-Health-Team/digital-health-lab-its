import { Head, usePage } from "@inertiajs/react";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import EventsHero from "@/Features/Events/Components/EventsHero/EventsHero";
import EventSpotlightCard from "@/Features/Events/Components/EventSpotlight/EventSpotlight";
import EventCatalogue from "@/Features/Events/Components/EventCatalogue/EventCatalogue";
import {
    type EventsHeroData,
    type EventSpotlight,
    type EventSummary,
} from "@/Features/Events/Types/event.type";

const heroData: EventsHeroData = {
    title: "Agenda & Events",
    subtitle:
        "Kompetisi, pameran, dan hari terbuka laboratorium — beserta tim mahasiswa dan karya yang lahir dari setiap edisi.",
    ctaLabel: "Lihat semua agenda",
    ctaHref: "#event-catalogue-heading",
    // Chosen for having no baked-in headline of its own — the showcase poster art
    // fights the H1 for the same reading position.
    backgroundUrl: "/assets/images/projects/projects_hero_main.png",
};

interface EventsPageProps {
    events: EventSummary[];
    spotlight: EventSpotlight | null;
    [key: string]: unknown;
}

export default function EventsPage() {
    const { props } = usePage<EventsPageProps>();
    const { events, spotlight } = props;

    // `events` is the complete archive — the spotlight is a promotion of one of
    // its members, so counting it separately would double it.
    const ongoingCount = events.filter((e) => e.status === "ongoing").length;
    const upcomingCount = events.filter((e) => e.status === "upcoming").length;

    return (
        <>
            <Head title="Events" />
            <DashboardLayout>
                <EventsHero
                    data={heroData}
                    ongoingCount={ongoingCount}
                    upcomingCount={upcomingCount}
                />
                {spotlight && <EventSpotlightCard event={spotlight} />}
                <EventCatalogue events={events} />
            </DashboardLayout>
        </>
    );
}
