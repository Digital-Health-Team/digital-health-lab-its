import { Head } from "@inertiajs/react";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import ProductsHero from "@/Features/Products/Components/ProductsHero/ProductsHero";
import ProductCatalogue from "@/Features/Products/Components/ProductCatalogue/ProductCatalogue";
import { productsHeroData } from "@/Features/Products/Data/productsHero.data";
import { productCatalogueData } from "@/Features/Products/Data/productCatalogue.data";

export default function ProductsPage() {
    return (
        <>
            <Head title="Products" />
            <DashboardLayout>
                {/* 1. Hero banner */}
                <ProductsHero data={productsHeroData} />

                {/* 2. Product Catalogue with tab filter */}
                <ProductCatalogue categories={productCatalogueData} />
            </DashboardLayout>
        </>
    );
}
