import { Link } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Image } from "@/Core/Components/Common/Image";
import { Text } from "@/Core/Components/Common/Text";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { cn } from "@/Core/Utils/utils";
import { type Category } from "@/Features/Dashboard/Types/category.type";

interface CategoryTileProps {
    category: Category;
    active?: boolean;
}

export default function CategoryTile({ category, active = false }: CategoryTileProps) {
    const { t } = useTranslation();
    const label = t(category.label);

    return (
        <Link
            href={category.href}
            className="group flex min-w-20 flex-col items-center gap-2 focus-visible:outline-none"
        >
            <Box
                className={cn(
                    "flex h-16 w-16 items-center justify-center overflow-hidden rounded-2xl transition-all duration-200",
                    "group-hover:scale-105 group-focus-visible:scale-105",
                    active
                        ? "border-2 border-primary-600 bg-white shadow-[0_8px_20px_-4px_rgba(0,66,109,0.25)]"
                        : "border border-slate-200/60 bg-slate-50 group-hover:border-secondary-500 group-hover:bg-white group-hover:shadow-[0_8px_20px_-4px_rgba(0,168,181,0.2)]",
                )}
            >
                <Image
                    src={category.icon}
                    alt={label}
                    objectFit="cover"
                    className="h-full w-full"
                />
            </Box>
            {/* Competency names run to two or three words, so the label cap is
                wider than the 64px icon above it. */}
            <Text
                as="span"
                className={cn(
                    "max-w-24 text-center text-xs leading-tight font-medium text-balance",
                    active
                        ? "font-semibold text-primary-700"
                        : "text-slate-700 group-hover:text-secondary-600",
                )}
            >
                {label}
            </Text>
        </Link>
    );
}
