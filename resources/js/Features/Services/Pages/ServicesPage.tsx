import { Head, usePage } from "@inertiajs/react";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import ServicesHero from "@/Features/Services/Components/ServicesHero/ServicesHero";
import AvailableServices from "@/Features/Services/Components/AvailableServices/AvailableServices";
import LatestOrders from "@/Features/Services/Components/LatestOrders/LatestOrders";
import { servicesHeroData } from "@/Features/Services/Data/servicesHero.data";
import { serviceOfferingsData } from "@/Features/Services/Data/serviceOfferings.data";
import { serviceOrdersData } from "@/Features/Services/Data/serviceOrders.data";
import type { ServiceOffering } from "@/Features/Services/Types/service.type";

interface DbService {
    id: number;
    name: string;
    description: string | null;
    service_type: string;
    priceLabel: string;
}

interface ServicesPageProps {
    dbServices?: DbService[];
    [key: string]: unknown;
}

export default function ServicesPage() {
    const { dbServices } = usePage<ServicesPageProps>().props;

    // Merge real DB data (description/name) into the static card definitions
    const offerings: ServiceOffering[] = serviceOfferingsData.map((offering) => {
        const dbRecord = dbServices?.find((s) => s.service_type === offering.id);
        if (!dbRecord) return offering;
        return {
            ...offering,
            title: dbRecord.name ?? offering.title,
            description: dbRecord.description ?? offering.description,
        };
    });

    return (
        <>
            <Head title="Services" />
            <DashboardLayout>
                {/* 1. Hero banner */}
                <ServicesHero data={servicesHeroData} />

                {/* 2. Available Services — 3-card grid */}
                <AvailableServices offerings={offerings} />

                {/* 3. Latest Service Orders table */}
                <LatestOrders orders={serviceOrdersData} />
            </DashboardLayout>
        </>
    );
}
