import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import ServiceOfferingCard from "./fragments/ServiceOfferingCard";
import { type ServiceOffering } from "@/Features/Services/Types/service.type";

interface AvailableServicesProps {
    offerings: ServiceOffering[];
}

export default function AvailableServices({ offerings }: AvailableServicesProps) {
    return (
        <Box as="section">
            <Box className="mb-6">
                <Heading
                    level={3}
                    className="font-display text-2xl font-bold text-slate-800 mb-1"
                >
                    Available Services
                </Heading>
                <Text className="text-sm text-slate-500">
                    Choose a service to get started with your project
                </Text>
            </Box>

            <Box className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {offerings.map((offering) => (
                    <ServiceOfferingCard key={offering.id} offering={offering} />
                ))}
            </Box>
        </Box>
    );
}
