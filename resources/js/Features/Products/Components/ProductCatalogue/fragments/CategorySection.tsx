import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { cn } from "@/Core/Utils/utils";
import ProductCard from "./ProductCard";
import { type ProductCategory } from "@/Features/Products/Types/product.type";

interface CategorySectionProps {
    category: ProductCategory;
}

export default function CategorySection({ category }: CategorySectionProps) {
    return (
        <Box as="section">
            <Heading
                level={3}
                className="font-display text-base font-semibold text-slate-800 mb-4"
            >
                {category.label}
            </Heading>

            {/* Bento grid: 2-col mobile → 4-col desktop; cards carry their own span */}
            <Box className="grid grid-cols-2 md:grid-cols-4 gap-x-3 gap-y-5 sm:gap-x-5 sm:gap-y-6 items-start">
                {category.cards.map((card) => (
                    <Box
                        key={card.id}
                        className={cn(
                            card.span === 2
                                ? "col-span-2"
                                : "col-span-1",
                        )}
                    >
                        <ProductCard card={card} />
                    </Box>
                ))}
            </Box>
        </Box>
    );
}
