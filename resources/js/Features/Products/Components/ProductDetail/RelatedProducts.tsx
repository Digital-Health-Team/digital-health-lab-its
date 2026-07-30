import { Link } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import RelatedProductCard from "./fragments/RelatedProductCard";
import { type RelatedProduct, type ProductStore } from "@/Features/Products/Types/productDetail.type";

interface RelatedProductsProps {
    related: RelatedProduct[];
    store: ProductStore;
}

export default function RelatedProducts({ related, store }: RelatedProductsProps) {
    const { t } = useTranslation();

    return (
        <Box as="section" className="mt-2">
            {/* Section header */}
            <Box className="flex items-end justify-between mb-6">
                <Box>
                    <Heading
                        level={2}
                        className="font-display text-xl font-bold text-slate-800 leading-tight"
                    >
                        {t("Other Products")}
                    </Heading>
                    <Text className="text-xs text-slate-400 mt-0.5">
                        More products from {store.name}
                    </Text>
                </Box>

                {/* View All button */}
                <Link
                    href="/products"
                    className="shrink-0 px-4 py-1.5 rounded-full border border-primary-700 text-primary-700 text-xs font-semibold hover:bg-primary-700 hover:text-white transition-colors"
                >
                    View All
                </Link>
            </Box>

            {/* 4-col card grid */}
            <Box className="grid grid-cols-2 md:grid-cols-4 gap-x-5 gap-y-8">
                {related.map((product) => (
                    <RelatedProductCard key={product.id} product={product} />
                ))}
            </Box>
        </Box>
    );
}
