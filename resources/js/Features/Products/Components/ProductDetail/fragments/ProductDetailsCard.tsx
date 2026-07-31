import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { cn } from "@/Core/Utils/utils";
import { type ProductDetailSpec } from "@/Features/Products/Types/productDetail.type";

interface ProductDetailsCardProps {
    specs: ProductDetailSpec[];
}

export default function ProductDetailsCard({ specs }: ProductDetailsCardProps) {
    const { t } = useTranslation();

    return (
        <Box className="rounded-2xl border border-slate-200 bg-white p-4">
            {/* Section label */}
            <Text className="text-[10px] font-semibold tracking-widest uppercase text-slate-400 mb-3">
                {t("Product Details")}
            </Text>

            {/* 2-col spec grid */}
            <Box className="grid grid-cols-2 gap-x-6 gap-y-3">
                {specs.map((spec) => (
                    <Box key={spec.label}>
                        <Text className="text-[10px] font-semibold uppercase tracking-wider text-slate-400 mb-0.5">
                            {t(spec.label)}
                        </Text>
                        <Text
                            className={cn(
                                "text-sm font-medium",
                                spec.accent
                                    ? "text-secondary-500"
                                    : "text-slate-800",
                            )}
                        >
                            {t(spec.value)}
                        </Text>
                    </Box>
                ))}
            </Box>
        </Box>
    );
}
