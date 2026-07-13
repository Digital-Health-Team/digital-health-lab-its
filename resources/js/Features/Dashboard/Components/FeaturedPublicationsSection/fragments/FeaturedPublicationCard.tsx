import { Link } from "@inertiajs/react";
import { type FeaturedPublication } from "@/Features/Dashboard/Types/publication.type";
import { cn } from "@/Core/Utils/utils";

interface FeaturedPublicationCardProps {
    publication: FeaturedPublication;
}

export default function FeaturedPublicationCard({ publication }: FeaturedPublicationCardProps) {
    return (
        <Link
            href={publication.href}
            className={cn(
                "group relative block shrink-0 w-[72%] sm:w-[46%] lg:w-[calc((100%-2rem)/3)] aspect-9/16 rounded-3xl overflow-hidden snap-start max-h-175",
                "bg-linear-to-br from-slate-100 to-slate-200",
                "card-hover-lift focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary-500/50",
            )}
        >
            {/* Cover image */}
            {publication.coverUrl && (
                <img
                    src={publication.coverUrl}
                    alt={publication.title}
                    loading="lazy"
                    className="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
            )}

            {/* Gradient overlay — always visible */}
            <div className="absolute inset-0 bg-linear-to-t from-primary-950/85 via-primary-950/30 to-transparent" />

            {/* Featured ribbon */}
            <div className="absolute top-4 left-4 px-2.5 py-1 rounded-full bg-white/90 backdrop-blur-sm text-[11px] font-semibold text-primary-700 shadow-sm">
                ★ Featured
            </div>

            {/* Title overlay — always visible */}
            <div className="absolute inset-x-0 bottom-0 p-5 translate-y-1 group-hover:translate-y-0 transition-transform duration-300">
                <p className="text-xs font-medium text-secondary-300 uppercase tracking-widest mb-1">
                    {publication.category}
                </p>
                <h3 className="font-display text-lg font-bold text-white leading-tight">
                    {publication.title}
                </h3>
            </div>
        </Link>
    );
}
