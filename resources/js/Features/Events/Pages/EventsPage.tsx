import { Head, usePage } from "@inertiajs/react";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import EventsHero from "@/Features/Events/Components/EventsHero/EventsHero";
import EventSpotlightCard from "@/Features/Events/Components/EventSpotlight/EventSpotlight";
import EventCatalogue from "@/Features/Events/Components/EventCatalogue/EventCatalogue";
import StaffPickFeatureCard from "@/Features/Training/Components/StaffPickFeature/StaffPickFeature";
import { type StaffPickFeature } from "@/Features/Training/Types/course.type";
import {
    type CatalogueItem,
    type EventsHeroData,
    type EventSpotlight,
} from "@/Features/Events/Types/event.type";

// Copy is authored in English because t() keys ARE the English source string and
// fall back to the key — an Indonesian source would render Indonesian in `en`.
const heroData: EventsHeroData = {
    title: "Agenda & Events",
    subtitle:
        "Exhibitions, seminars, and hands-on workshops from the laboratory — along with the student teams and the work that came out of each edition.",
    ctaLabel: "See all events",
    ctaHref: "#event-catalogue-heading",
    // Chosen for having no baked-in headline of its own — the showcase poster art
    // fights the H1 for the same reading position.
    backgroundUrl: "/assets/images/projects/projects_hero_main.png",
};

interface EventsPageProps {
    items: CatalogueItem[];
    spotlight: EventSpotlight | null;
    staffPick: StaffPickFeature | null;
    [key: string]: unknown;
}

export default function EventsPage() {
    const { props } = usePage<EventsPageProps>();
    const { items, spotlight, staffPick } = props;
    const { t } = useTranslation();

    // `items` is the complete archive — the promoted card is one of its members,
    // so counting it separately would double it.
    const ongoingCount = items.filter((i) => i.status === "ongoing").length;
    const upcomingCount = items.filter((i) => i.status === "upcoming").length;

    return (
        <>
            <Head title={t("Events")} />
            <DashboardLayout>
                <EventsHero
                    data={heroData}
                    ongoingCount={ongoingCount}
                    upcomingCount={upcomingCount}
                />
                {/* One promo slot: a featured event wins it, a staff-pick workshop fills it otherwise. */}
                {spotlight ? (
                    <EventSpotlightCard event={spotlight} />
                ) : (
                    staffPick && <StaffPickFeatureCard data={staffPick} />
                )}
                <EventCatalogue items={items} />
            </DashboardLayout>
        </>
    );
}
