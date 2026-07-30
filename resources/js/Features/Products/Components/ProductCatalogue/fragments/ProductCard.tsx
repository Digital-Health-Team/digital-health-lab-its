import { Link } from "@inertiajs/react";
import { ShoppingCart } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { cn } from "@/Core/Utils/utils";
import { formatIDR } from "@/Core/Utils/locale";
import { type ProductCard } from "@/Features/Products/Types/product.type";

interface ProductCardProps {
    card: ProductCard;
}

function formatPriceRange(min: number, max: number): string {
    if (min === max) return formatIDR(min);
    return `${formatIDR(min)} – ${formatIDR(max)}`;
}

export default function ProductCard({ card }: ProductCardProps) {
    return (
        <Link href={card.href} className="block group">
            {/* ── Fluid image (bento card) ── */}
            <Box
                className="relative w-full h-40 sm:h-48 md:h-56 rounded-2xl overflow-hidden shadow-card-soft card-hover-lift"
            >
                {/* Thumbnail */}
                {card.thumbnailUrl ? (
                    <Box
                        className="absolute inset-0"
                        style={{
                            backgroundImage: `url(${card.thumbnailUrl})`,
                            backgroundSize: "cover",
                            backgroundPosition: "center",
                        }}
                    />
                ) : (
                    <Box
                        className={cn(
                            "absolute inset-0",
                            card.thumbnailColor ??
                                "bg-linear-to-br from-slate-200 to-slate-400",
                        )}
                    />
                )}

                {/* Order action button — top-left */}
                <Box
                    className="absolute top-2.5 left-2.5 z-10"
                    onClick={(e: React.MouseEvent) => e.preventDefault()}
                >
                    <Box className="h-6 w-6 rounded-md bg-primary-700 flex items-center justify-center shadow-sm">
                        <ShoppingCart className="h-3 w-3 text-white" />
                    </Box>
                </Box>
            </Box>

            {/* ── Title, Caption + price below the image ── */}
            <Text className="text-sm font-bold text-slate-800 mt-2 line-clamp-1 pl-0.5 group-hover:text-primary-700 transition-colors">
                {card.title}
            </Text>
            <Text className="text-xs text-slate-500 leading-snug line-clamp-2 mt-0.5 pl-0.5">
                {card.caption}
            </Text>
            <Text className="text-xs font-semibold text-primary-800 mt-1 pl-0.5">
                {formatPriceRange(card.priceMin, card.priceMax)}
            </Text>
        </Link>
    );
}
