import { Clock, Eye, Tag, User } from "lucide-react";
import { Link } from "@inertiajs/react";
import Badge from "@/Core/Components/Shared/Badge/Badge";
import { type PublicationRow } from "@/Features/Dashboard/Types/publication.type";
import { cn } from "@/Core/Utils/utils";

interface PublicationRowItemProps {
    publication: PublicationRow;
}

function timeAgo(dateStr: string): string {
    const diffMs = Date.now() - new Date(dateStr).getTime();
    const days = Math.floor(diffMs / 86400000);
    if (days < 1) return "today";
    if (days === 1) return "1 day ago";
    if (days < 30) return `${days} days ago`;
    const months = Math.floor(days / 30);
    return `${months} month${months > 1 ? "s" : ""} ago`;
}

export default function PublicationRowItem({ publication }: PublicationRowItemProps) {
    return (
        <div className="flex items-center gap-4 px-5 py-4 hover:bg-slate-50 transition-colors duration-150">
            {/* Thumbnail */}
            <div className="shrink-0 w-12 h-12 rounded-xl overflow-hidden bg-slate-100">
                <img
                    src={publication.thumbnailUrl}
                    alt=""
                    aria-hidden="true"
                    loading="lazy"
                    className="w-full h-full object-cover"
                />
            </div>

            {/* Body */}
            <div className="flex-1 min-w-0">
                <p className="text-sm font-semibold text-slate-800 line-clamp-1 mb-1">
                    {publication.title}
                </p>
                <div className="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-500">
                    <span className="flex items-center gap-1">
                        <Clock className="h-3 w-3 shrink-0" />
                        {timeAgo(publication.publishedAt)}
                    </span>
                    <span className="flex items-center gap-1">
                        <Eye className="h-3 w-3 shrink-0" />
                        {publication.viewCount.toLocaleString()}
                    </span>
                    <span className="flex items-center gap-1">
                        <Tag className="h-3 w-3 shrink-0" />
                        {publication.category}
                    </span>
                    <span className="flex items-center gap-1">
                        <User className="h-3 w-3 shrink-0" />
                        {publication.author}
                    </span>
                </div>
            </div>

            {/* Status badge + view link */}
            <div className={cn("shrink-0 flex flex-col items-end gap-2")}>
                <Badge variant={publication.status}>
                    {publication.status}
                </Badge>
                <Link
                    href={publication.href}
                    className="text-xs font-semibold text-secondary-600 hover:text-secondary-700 inline-flex items-center gap-1 transition-colors duration-150"
                >
                    View Detail →
                </Link>
            </div>
        </div>
    );
}
