import { Link } from "@inertiajs/react";
import { cn } from "@/Core/Utils/utils";
import { type Category } from "@/Features/Dashboard/Types/category.type";

interface CategoryTileProps {
    category: Category;
    active?: boolean;
}

export default function CategoryTile({ category, active = false }: CategoryTileProps) {
    return (
        <Link
            href={category.href}
            className="group flex flex-col items-center gap-2 min-w-18 focus-visible:outline-none"
        >
            <div
                className={cn(
                    "w-16 h-16 rounded-2xl flex items-center justify-center transition-all duration-200 overflow-hidden",
                    "group-hover:scale-105 group-focus-visible:scale-105",
                    active
                        ? "bg-white border-2 border-primary-600 shadow-[0_8px_20px_-4px_rgba(0,66,109,0.25)]"
                        : "bg-slate-50 border border-slate-200/60 group-hover:bg-white group-hover:border-secondary-500 group-hover:shadow-[0_8px_20px_-4px_rgba(0,168,181,0.2)]",
                )}
            >
                <img
                    src={category.icon}
                    alt={category.label}
                    loading="lazy"
                    className="w-full h-full object-cover"
                />
            </div>
            <span
                className={cn(
                    "text-xs font-medium text-center leading-tight max-w-18",
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
