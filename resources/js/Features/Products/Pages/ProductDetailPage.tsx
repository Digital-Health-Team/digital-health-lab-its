import { Head } from "@inertiajs/react";
import { usePage } from "@inertiajs/react";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import ProductBreadcrumb from "@/Features/Products/Components/ProductDetail/ProductBreadcrumb";
import ProductGallery from "@/Features/Products/Components/ProductDetail/ProductGallery";
import ProductStoreCard from "@/Features/Products/Components/ProductDetail/ProductStoreCard";
import ProductInfo from "@/Features/Products/Components/ProductDetail/ProductInfo";
import RelatedProducts from "@/Features/Products/Components/ProductDetail/RelatedProducts";
import { productDetailData } from "@/Features/Products/Data/productDetail.data";
import { Box } from "@/Core/Components/Common/Box";

interface ProductDetailPageProps {
    productId: string;
    [key: string]: unknown;
}

export default function ProductDetailPage() {
    // productId from Inertia prop — available for future dynamic binding
    const { props } = usePage<ProductDetailPageProps>();
    const _productId = props.productId;

    // Static mock — all content comes from productDetailData
    const product = productDetailData;

    return (
        <>
            <Head title={product.title} />
            <DashboardLayout>
                {/* Breadcrumb */}
                <ProductBreadcrumb breadcrumb={product.breadcrumb} />

                {/* ── Two-column body ──────────────────────────────── */}
                <Box className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    {/* LEFT — Gallery + Store */}
                    <Box className="lg:col-span-6 flex flex-col gap-4">
                        <ProductGallery
                            images={product.images}
                            badges={product.badges}
                            caption={product.galleryCaption}
                        />
                        {/* <ProductStoreCard store={product.store} /> */}
                    </Box>

                    {/* RIGHT — Product info (no shadow, bordered cards) */}
                    <Box className="lg:col-span-6">
                        <ProductInfo product={product} />
                    </Box>
                </Box>

                {/* ── Related products ─────────────────────────────── */}
                <RelatedProducts related={product.related} store={product.store} />
            </DashboardLayout>
        </>
    );
}
