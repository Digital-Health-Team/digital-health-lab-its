import { Head, usePage } from "@inertiajs/react";
import Preloader from "@/Core/Components/Shared/Preloader/Preloader";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import HeroBannerCard from "@/Features/Dashboard/Components/HeroBannerCard/HeroBannerCard";
import CategoryQuickAccess from "@/Features/Dashboard/Components/CategoryQuickAccess/CategoryQuickAccess";
import NewProductsCard from "@/Features/Dashboard/Components/NewProductsCard/NewProductsCard";
import EmptyStateCard from "@/Features/Dashboard/Components/EmptyStateCard/EmptyStateCard";
import OngoingEventCard from "@/Features/Dashboard/Components/OngoingEventCard/OngoingEventCard";
import FeaturedPublicationsSection from "@/Features/Dashboard/Components/FeaturedPublicationsSection/FeaturedPublicationsSection";
import TrendingArticlesCard from "@/Features/Dashboard/Components/TrendingArticlesCard/TrendingArticlesCard";
import PubMedUpdatesCard from "@/Features/Dashboard/Components/PubMedUpdatesCard/PubMedUpdatesCard";
import FeaturedPublicationsList from "@/Features/Dashboard/Components/FeaturedPublicationsList/FeaturedPublicationsList";
import { type Product, type Service } from "@/Features/Dashboard/Types/product.type";
import { type FeaturedPublication } from "@/Features/Dashboard/Types/publication.type";
import { type ActiveEvent } from "@/Features/Dashboard/Types/event.type";
import { useTranslation } from "@/Core/Hooks/useTranslation";

interface DashboardPageProps {
    products: Product[];
    services: Service[];
    openSourceProjects: FeaturedPublication[];
    activeEvent: ActiveEvent | null;
}

export default function DashboardPage() {
    const { products, services, openSourceProjects, activeEvent } = usePage<DashboardPageProps>().props;
    const { t } = useTranslation();

    const emptyEventsConfig = {
        illustrationKey: "no-events" as const,
        title: t("No Ongoing Events"),
        body: t("Check back later for upcoming Innovatech events from the IDIG laboratory."),
    };

    return (
        <>
            <Head title="Dashboard" />
            <Preloader />
            <DashboardLayout>
                {/* 1. Hero Banner */}
                <HeroBannerCard />

                {/* 2. Category Quick Access */}
                <CategoryQuickAccess />

                {/* 3. New Products + No Ongoing Events */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div className="lg:col-span-2">
                        <NewProductsCard products={products} services={services} />
                    </div>
                    {activeEvent
                        ? <OngoingEventCard event={activeEvent} />
                        : <EmptyStateCard config={emptyEventsConfig} />}
                </div>

                {/* 4. Explore Our Projects */}
                <FeaturedPublicationsSection publications={openSourceProjects} />

                {/* 5. Trending Articles + PubMed Updates */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <TrendingArticlesCard />
                    <PubMedUpdatesCard />
                </div>

                {/* 6. Featured Publications List */}
                <FeaturedPublicationsList />
            </DashboardLayout>
        </>
    );
}
