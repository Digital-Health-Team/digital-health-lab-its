import { Link } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { cn } from "@/Core/Utils/utils";
import { formatIDR } from "@/Core/Utils/locale";
import { type RelatedProduct } from "@/Features/Products/Types/productDetail.type";

interface RelatedProductCardProps {
    product: RelatedProduct;
}

export default function RelatedProductCard({ product }: RelatedProductCardProps) {
    return (
        <Link href={product.href} className="block group">
            {/* Thumbnail */}
            <Box className="relative w-full aspect-[4/3] rounded-2xl overflow-hidden shadow-card-soft card-hover-lift">
                {product.thumbnailUrl ? (
                    <Box
                        className="absolute inset-0"
                        style={{
                            backgroundImage: `url(${product.thumbnailUrl})`,
                            backgroundSize: "cover",
                            backgroundPosition: "center",
                        }}
                    />
                ) : (
                    <Box
                        className={cn(
                            "absolute inset-0",
                            product.thumbnailColor ??
                                "bg-gradient-to-br from-slate-200 to-slate-400",
                        )}
                    />
                )}
            </Box>

            {/* Title */}
            <Text className="text-sm font-bold text-slate-800 mt-2 line-clamp-2 pl-0.5 group-hover:text-primary-700 transition-colors">
                {product.title}
            </Text>

            {/* Price */}
            <Box className="flex items-baseline gap-1 mt-0.5 pl-0.5">
                <Text className="text-xs text-slate-500">Starts from</Text>
                <Text className="text-xs font-semibold text-primary-700">
                    {formatIDR(product.priceFrom)}
                </Text>
            </Box>
        </Link>
    );
}
