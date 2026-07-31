import { Link } from "@inertiajs/react";
import { Eye, Calendar } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import Badge from "@/Core/Components/Shared/Badge/Badge";
import { cn } from "@/Core/Utils/utils";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { localeTag } from "@/Core/Utils/locale";
import { type PublicationListItem } from "@/Features/Publications/Types/publication.type";

interface PublicationCardProps {
    publication: PublicationListItem;
}

function formatDate(iso: string, tag: string): string {
    return new Date(iso).toLocaleDateString(tag, {
        day: "numeric",
        month: "short",
        year: "numeric",
    });
}

function formatViews(n: number): string {
    return n >= 1000 ? `${(n / 1000).toFixed(1)}k` : String(n);
}

export default function PublicationCard({ publication }: PublicationCardProps) {
    const { lang } = useTranslation();
    return (
        <Link href={publication.href} className="block group">
            {/* ── Thumbnail ── */}
            <Box className="relative w-full aspect-3/4 rounded-2xl overflow-hidden shadow-card-soft card-hover-lift bg-slate-50 border border-slate-100">
                {publication.thumbnailUrl ? (
                    <Box
                        className="absolute inset-0"
                        style={{
                            backgroundImage: `url(${publication.thumbnailUrl})`,
                            backgroundSize: "cover",
                            backgroundPosition: "center top",
                        }}
                    />
                ) : (
                    <Box className="absolute inset-0 bg-linear-to-br from-primary-100 to-secondary-200" />
                )}

                {/* Category badge overlay — top-left */}
                <Box className="absolute top-2.5 left-2.5 z-10">
                    <Badge variant="tag">{publication.category}</Badge>
                </Box>
            </Box>

            {/* ── Meta below image ── */}
            <Box className="mt-3 px-0.5">
                <Text
                    className={cn(
                        "text-sm font-bold text-slate-800 line-clamp-2 leading-snug mb-1",
                        "group-hover:text-primary-700 transition-colors",
                    )}
                >
                    {publication.title}
                </Text>

                <Text className="text-xs text-slate-500 line-clamp-1 mb-2">
                    {publication.author}
                </Text>

                <Box className="flex items-center gap-3">
                    <Box className="flex items-center gap-1 text-slate-400">
                        <Calendar className="h-3 w-3 shrink-0" />
                        <Text as="span" className="text-[11px]">
                            {formatDate(publication.publishedAt, localeTag(lang))}
                        </Text>
                    </Box>
                    <Box className="flex items-center gap-1 text-slate-400">
                        <Eye className="h-3 w-3 shrink-0" />
                        <Text as="span" className="text-[11px]">
                            {formatViews(publication.viewCount)} views
                        </Text>
                    </Box>
                </Box>
            </Box>
        </Link>
    );
}
