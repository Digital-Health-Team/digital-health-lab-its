import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";

interface ProductPriceCardProps {
    label: string;
    priceMin: number;
    priceMax: number;
    badges: string[];
}

function formatIDR(amount: number): string {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
}

export default function ProductPriceCard({
    label,
    priceMin,
    priceMax,
    badges,
}: ProductPriceCardProps) {
    return (
        <Box className="rounded-2xl bg-primary-900 p-4">
            {/* Eyebrow label */}
            <Text className="text-[10px] font-semibold tracking-widest uppercase text-secondary-300 mb-2">
                {label}
            </Text>

            {/* Price range */}
            <Box className="flex items-baseline gap-2 mb-3">
                <Heading
                    level={3}
                    className="font-display text-xl font-bold text-secondary-200"
                >
                    {formatIDR(priceMin)}
                </Heading>
                {priceMin !== priceMax && (
                    <>
                        <Text as="span" className="text-secondary-300 font-semibold text-sm">
                            –
                        </Text>
                        <Heading
                            level={3}
                            className="font-display text-xl font-bold text-secondary-400"
                        >
                            {formatIDR(priceMax)}
                        </Heading>
                    </>
                )}
            </Box>

            {/* Badges */}
            <Box className="flex items-center gap-2 flex-wrap">
                {badges.map((badge) => (
                    <Box
                        key={badge}
                        className="px-2.5 py-0.5 rounded-full bg-secondary-400/20 border border-secondary-400/40 text-secondary-300 text-[10px] font-semibold"
                    >
                        {badge}
                    </Box>
                ))}
            </Box>
        </Box>
    );
}
