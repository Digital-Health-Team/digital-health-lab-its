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
                "group relative block aspect-square rounded-3xl overflow-hidden",
                "bg-gradient-to-br from-slate-100 to-slate-200",
                "card-hover-lift focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary-500/50",
            )}
        >
            {/* Cover image */}
            <img
                src={publication.coverUrl}
                alt={publication.title}
                loading="lazy"
                className="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            />

            {/* Gradient overlay — visible on hover */}
            <div className="absolute inset-0 bg-gradient-to-t from-primary-950/80 via-primary-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />

            {/* Featured ribbon */}
            <div className="absolute top-4 left-4 px-2.5 py-1 rounded-full bg-white/90 backdrop-blur-sm text-[11px] font-semibold text-primary-700 shadow-sm">
                ★ Featured
            </div>

            {/* Title overlay — shown on hover */}
            <div className="absolute inset-x-0 bottom-0 p-6 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                <p className="text-xs font-medium text-secondary-300 uppercase tracking-widest mb-1">
                    {publication.category}
                </p>
                <h3 className="font-display text-xl font-bold text-white leading-tight">
                    {publication.title}
                </h3>
            </div>
        </Link>
    );
}
