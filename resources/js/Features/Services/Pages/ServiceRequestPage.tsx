import { Head } from "@inertiajs/react";
import { Link } from "@inertiajs/react";
import { ArrowLeft } from "lucide-react";
import Preloader from "@/Core/Components/Shared/Preloader/Preloader";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import ServicesHero from "@/Features/Services/Components/ServicesHero/ServicesHero";
import ServiceRequestForm from "@/Features/Services/Components/ServiceRequestForm/ServiceRequestForm";
import { servicesHeroData } from "@/Features/Services/Data/servicesHero.data";
import { serviceRequestConfigs } from "@/Features/Services/Data/serviceRequests.data";
import { type FilamentOption, type ColorOption } from "@/Features/Services/Types/serviceRequest.type";

interface ServiceRequestPageProps {
    service: string;
    serviceId: number;
    isAuthenticated: boolean;
    filaments: FilamentOption[];
    colors: ColorOption[];
}

export default function ServiceRequestPage({ service, serviceId, isAuthenticated, filaments, colors }: ServiceRequestPageProps) {
    const config = serviceRequestConfigs[service];

    return (
        <>
            <Head title={config?.title ?? "Service Request"} />
            <Preloader />
            <DashboardLayout>
                {/* 1. Reuse the shared hero */}
                <ServicesHero data={servicesHeroData} />

                {/* 2. Back-nav breadcrumb */}
                <Box>
                    <Link
                        href="/services"
                        className="inline-flex items-center gap-1.5 text-sm font-semibold text-primary-700 hover:text-primary-600 transition-colors"
                    >
                        <ArrowLeft className="h-4 w-4" />
                        <Text as="span" className="text-sm font-semibold text-primary-700 hover:text-primary-600">
                            Back to Services
                        </Text>
                    </Link>
                </Box>

                {/* 3. Config-driven request form */}
                {config ? (
                    <ServiceRequestForm
                        config={config}
                        serviceId={serviceId}
                        isAuthenticated={isAuthenticated}
                        filaments={filaments}
                        colors={colors}
                    />
                ) : (
                    <Box className="py-12 text-center">
                        <Text className="text-slate-500">Service not found.</Text>
                    </Box>
                )}
            </DashboardLayout>
        </>
    );
}
