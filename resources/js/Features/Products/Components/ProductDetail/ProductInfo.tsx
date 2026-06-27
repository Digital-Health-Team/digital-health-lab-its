import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import ProductRating from "./fragments/ProductRating";
import ProductPriceCard from "./fragments/ProductPriceCard";
import ProductDetailsCard from "./fragments/ProductDetailsCard";
import ProductDescriptionCard from "./fragments/ProductDescriptionCard";
import { type ProductDetail } from "@/Features/Products/Types/productDetail.type";

interface ProductInfoProps {
    product: ProductDetail;
}

export default function ProductInfo({ product }: ProductInfoProps) {
    return (
        <Box className="flex flex-col gap-4">
            {/* Title */}
            <Box>
                <Heading
                    level={1}
                    className="font-display text-2xl font-bold text-slate-900 leading-snug mb-2"
                >
                    {product.title}
                </Heading>

                {/* Rating + sold */}
                <ProductRating
                    rating={product.rating}
                    reviewCount={product.reviewCount}
                    soldCount={product.soldCount}
                />
            </Box>

            {/* Price card — dark navy, no outer shadow */}
            <ProductPriceCard
                label={product.priceLabel}
                priceMin={product.priceMin}
                priceMax={product.priceMax}
                badges={product.priceBadges}
            />

            {/* Product Details — white, bordered, no shadow */}
            <ProductDetailsCard specs={product.specs} />

            {/* Description — white, bordered, no shadow */}
            <ProductDescriptionCard
                lead={product.description.lead}
                body={product.description.body}
                tags={product.tags}
            />
        </Box>
    );
}
