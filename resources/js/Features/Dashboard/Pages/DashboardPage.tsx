import { Head } from "@inertiajs/react";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import HeroBannerCard from "@/Features/Dashboard/Components/HeroBannerCard/HeroBannerCard";
import CategoryQuickAccess from "@/Features/Dashboard/Components/CategoryQuickAccess/CategoryQuickAccess";
import NewProductsCard from "@/Features/Dashboard/Components/NewProductsCard/NewProductsCard";
import EmptyStateCard from "@/Features/Dashboard/Components/EmptyStateCard/EmptyStateCard";
import FeaturedPublicationsSection from "@/Features/Dashboard/Components/FeaturedPublicationsSection/FeaturedPublicationsSection";
import TrendingArticlesCard from "@/Features/Dashboard/Components/TrendingArticlesCard/TrendingArticlesCard";
import PubMedUpdatesCard from "@/Features/Dashboard/Components/PubMedUpdatesCard/PubMedUpdatesCard";
import FeaturedPublicationsList from "@/Features/Dashboard/Components/FeaturedPublicationsList/FeaturedPublicationsList";
import { emptyEventsConfig } from "@/Features/Dashboard/Data/emptyEvents.data";

export default function DashboardPage() {
    return (
        <>
            <Head title="Dashboard" />
            <DashboardLayout>
                {/* 1. Hero Banner */}
                <HeroBannerCard />

                {/* 2. Category Quick Access */}
                <CategoryQuickAccess />

                {/* 3. New Products + No Ongoing Events */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div className="lg:col-span-2">
                        <NewProductsCard />
                    </div>
                    <EmptyStateCard config={emptyEventsConfig} />
                </div>

                {/* 4. Explore Our Projects */}
                <FeaturedPublicationsSection />

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
