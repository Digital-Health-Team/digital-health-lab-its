import { Link } from "@inertiajs/react";
import { Package, Star } from "lucide-react";
import Badge from "@/Core/Components/Shared/Badge/Badge";
import { cn } from "@/Core/Utils/utils";
import { type Product, type Service } from "@/Features/Dashboard/Types/product.type";

type ProductCardProps = { item: Product | Service };

function isBadged(item: Product | Service): item is Product {
    return "badge" in item && item.badge !== undefined;
}

export default function ProductCard({ item }: ProductCardProps) {
    return (
        <Link
            href={item.href}
            className="group flex flex-col card-hover-lift rounded-xl p-2 -mx-2 hover:bg-white hover:shadow-md hover:shadow-primary-900/8 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary-500/50 transition-all duration-200"
            style={{ transitionTimingFunction: "cubic-bezier(0.25,1,0.5,1)" }}
        >
            {/* Image */}
            <div className="relative aspect-square rounded-xl overflow-hidden bg-slate-100 mb-3 shrink-0">
                {item.coverUrl ? (
                    <img
                        src={item.coverUrl}
                        alt={item.title}
                        loading="lazy"
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        style={{ transitionTimingFunction: "cubic-bezier(0.25,1,0.5,1)" }}
                    />
                ) : (
                    <div className="w-full h-full flex items-center justify-center bg-slate-100">
                        <Package className="h-10 w-10 text-slate-300" />
                    </div>
                )}
                {isBadged(item) && item.badge && (
                    <span className="absolute top-2 left-2">
                        <Badge variant={item.badge}>{item.badge === "new" ? "New" : "Sale"}</Badge>
                    </span>
                )}
            </div>
            {/* Info */}
            <p className={cn(
                "text-sm font-semibold text-slate-800 line-clamp-1 mb-1",
                "group-hover:text-primary-700 transition-colors duration-200",
            )}>
                {item.title}
            </p>
            <p className="text-xs text-slate-500">
                <span className="text-primary-700 font-semibold">{item.priceLabel}</span>
            </p>
            {/* Rating row */}
            <div className="flex items-center gap-1 mt-1">
                <Star className="h-3 w-3 text-accent-400 fill-accent-400 shrink-0" />
                <span className="text-xs text-slate-600">
                    {item.rating !== null ? item.rating.toFixed(1) : "—"}
                </span>
                <span className="text-xs text-slate-400">· {item.seller}</span>
            </div>
        </Link>
    );
}
