import { usePage } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { categories } from "@/Features/Dashboard/Data/categories.data";
import CategoryTile from "./fragments/CategoryTile";

export default function CategoryQuickAccess() {
    const { url } = usePage();
    const { t } = useTranslation();

    return (
        <Box>
            <Heading
                level={2}
                className="font-display mb-4 text-center text-2xl font-bold text-slate-800"
            >
                {t("Our Catalogue")}
            </Heading>
            <Box className="scrollbar-thin -mx-1 overflow-x-auto pb-1">
                <Box className="flex min-w-max gap-4 px-1 lg:grid lg:min-w-0 lg:grid-cols-7">
                    {categories.map((cat) => (
                        // Inertia's `url` carries the query string, so matching the whole
                        // href keeps the four /products tiles from all lighting up at once.
                        <CategoryTile
                            key={cat.id}
                            category={cat}
                            active={url.startsWith(cat.href)}
                        />
                    ))}
                </Box>
            </Box>
        </Box>
    );
}
