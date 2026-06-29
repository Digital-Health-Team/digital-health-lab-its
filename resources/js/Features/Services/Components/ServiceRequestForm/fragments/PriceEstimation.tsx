import { DollarSign } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { Heading } from "@/Core/Components/Common/Heading";
import { type FilamentOption } from "@/Features/Services/Types/serviceRequest.type";

interface PriceEstimationProps {
    filament: FilamentOption | undefined;
}

export default function PriceEstimation({ filament }: PriceEstimationProps) {
    if (!filament) return null;

    return (
        <Box className="flex items-start gap-3 rounded-xl border border-blue-100 bg-blue-50/60 px-4 py-3.5">
            {/* Icon */}
            <Box className="mt-0.5 w-8 h-8 rounded-lg bg-[#00426D]/10 flex items-center justify-center shrink-0">
                <DollarSign className="h-4 w-4 text-[#00426D]" strokeWidth={2} />
            </Box>

            {/* Text */}
            <Box>
                <Heading
                    level={6}
                    className="text-sm font-semibold text-slate-700 mb-0.5"
                >
                    Price Estimation
                </Heading>
                <Text as="p" className="text-sm text-slate-600">
                    Based on{" "}
                    <Text as="span" className="font-semibold text-[#00426D]">
                        {filament.name}
                    </Text>{" "}
                    filament at{" "}
                    <Text as="span" className="font-semibold text-[#00426D]">
                        {filament.priceLabel} / gram
                    </Text>
                </Text>
                <Text as="p" className="mt-0.5 text-xs text-slate-400">
                    Final price will be calculated based on the total weight of your print
                </Text>
            </Box>
        </Box>
    );
}
