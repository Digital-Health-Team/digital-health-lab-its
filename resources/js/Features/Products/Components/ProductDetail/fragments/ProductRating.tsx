import { Star } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { cn } from "@/Core/Utils/utils";

interface ProductRatingProps {
    rating: number;
    reviewCount: number;
    soldCount: number;
}

function StarIcon({ filled, half }: { filled?: boolean; half?: boolean }) {
    if (half) {
        return (
            <Box className="relative inline-flex shrink-0">
                {/* empty base */}
                <Star className="h-3.5 w-3.5 text-slate-300" />
                {/* filled left half */}
                <Box className="absolute inset-0 overflow-hidden w-1/2">
                    <Star className="h-3.5 w-3.5 fill-accent-400 text-accent-400" />
                </Box>
            </Box>
        );
    }
    return (
        <Star
            className={cn(
                "h-3.5 w-3.5 shrink-0",
                filled
                    ? "fill-accent-400 text-accent-400"
                    : "text-slate-300",
            )}
        />
    );
}

export default function ProductRating({
    rating,
    reviewCount,
    soldCount,
}: ProductRatingProps) {
    const fullStars = Math.floor(rating);
    const hasHalf = rating % 1 >= 0.3;

    return (
        <Box className="flex items-center gap-3">
            {/* Stars */}
            <Box className="flex items-center gap-0.5">
                {Array.from({ length: 5 }, (_, i) => {
                    if (i < fullStars) return <StarIcon key={i} filled />;
                    if (i === fullStars && hasHalf) return <StarIcon key={i} half />;
                    return <StarIcon key={i} />;
                })}
            </Box>

            {/* Rating value + reviews */}
            <Text className="text-xs text-slate-500">
                <Text as="span" className="font-semibold text-slate-700">
                    {rating.toFixed(1)}
                </Text>{" "}
                ({reviewCount} reviews)
            </Text>

            {/* Separator */}
            <Box className="h-3.5 w-px bg-slate-200 shrink-0" />

            {/* Sold */}
            <Text className="text-xs text-slate-500">
                <Text as="span" className="font-semibold text-slate-700">
                    {soldCount}
                </Text>{" "}
                sold
            </Text>
        </Box>
    );
}
