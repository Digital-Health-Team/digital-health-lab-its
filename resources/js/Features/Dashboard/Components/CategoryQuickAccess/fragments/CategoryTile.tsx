import { Link } from "@inertiajs/react";
import { cn } from "@/Core/Utils/utils";
import { type Category } from "@/Features/Dashboard/Types/category.type";

interface CategoryTileProps {
    category: Category;
    active?: boolean;
}

export default function CategoryTile({ category, active = false }: CategoryTileProps) {
    const Icon = category.icon;

    return (
        <Link
            href={category.href}
            className="group flex flex-col items-center gap-2 min-w-[72px] focus-visible:outline-none"
        >
            <div
                className={cn(
                    "w-16 h-16 rounded-2xl flex items-center justify-center transition-all duration-200",
                    "group-hover:scale-105 group-focus-visible:scale-105",
                    active
                        ? "bg-primary-700 text-white shadow-[0_8px_20px_-4px_rgba(0,66,109,0.5)]"
                        : "bg-slate-100 text-slate-600 group-hover:bg-secondary-500 group-hover:text-white group-hover:shadow-[0_8px_20px_-4px_rgba(0,168,181,0.4)]",
                )}
            >
                <Icon className="h-7 w-7 shrink-0" />
            </div>
            <span
                className={cn(
                    "text-xs font-medium text-center leading-tight max-w-[72px]",
                    active
                        ? "text-primary-700 font-semibold"
                        : "text-slate-700 group-hover:text-secondary-600",
                )}
            >
                {category.label}
            </span>
        </Link>
    );
}
