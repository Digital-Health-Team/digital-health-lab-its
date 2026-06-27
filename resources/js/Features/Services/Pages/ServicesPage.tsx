import { Head } from "@inertiajs/react";
import Preloader from "@/Core/Components/Shared/Preloader/Preloader";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import ServicesHero from "@/Features/Services/Components/ServicesHero/ServicesHero";
import AvailableServices from "@/Features/Services/Components/AvailableServices/AvailableServices";
import LatestOrders from "@/Features/Services/Components/LatestOrders/LatestOrders";
import { servicesHeroData } from "@/Features/Services/Data/servicesHero.data";
import { serviceOfferingsData } from "@/Features/Services/Data/serviceOfferings.data";
import { serviceOrdersData } from "@/Features/Services/Data/serviceOrders.data";

export default function ServicesPage() {
    return (
        <>
            <Head title="Services" />
            <Preloader />
            <DashboardLayout>
                {/* 1. Hero banner */}
                <ServicesHero data={servicesHeroData} />

                {/* 2. Available Services — 3-card grid */}
                <AvailableServices offerings={serviceOfferingsData} />

                {/* 3. Latest Service Orders table */}
                <LatestOrders orders={serviceOrdersData} />
            </DashboardLayout>
        </>
    );
}
