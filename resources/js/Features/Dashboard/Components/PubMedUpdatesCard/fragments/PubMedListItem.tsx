// TODO(v2): wire to App\Actions\Articles\FetchPubMedFeedAction
import { FileText } from "lucide-react";
import { type PubMedItem } from "@/Features/Dashboard/Types/pubmed.type";
import { cn } from "@/Core/Utils/utils";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { localeTag } from "@/Core/Utils/locale";

interface PubMedListItemProps {
    item: PubMedItem;
}

export default function PubMedListItem({ item }: PubMedListItemProps) {
    const { lang } = useTranslation();
    const dateStr = new Date(item.publishedAt).toLocaleDateString(localeTag(lang), {
        month: "long",
        day: "numeric",
        year: "numeric",
    });

    return (
        <a
            href={item.href}
            target="_blank"
            rel="noopener noreferrer"
            className="group flex gap-4 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary-500/50 rounded-lg p-1 -m-1"
        >
            {/* Branded placeholder — PubMed items have no cover image */}
            <div className="shrink-0 w-16 sm:w-20 aspect-[3/4] rounded-md overflow-hidden ring-1 ring-primary-900/10 flex flex-col items-center justify-center gap-1.5 bg-gradient-to-br from-primary-900 via-primary-800 to-secondary-600/80">
                <FileText className="h-5 w-5 text-white/60" />
                {item.journal && (
                    <span className="text-white/40 text-[9px] font-mono text-center px-1 line-clamp-2 leading-tight">
                        {item.journal.slice(0, 14)}
                    </span>
                )}
            </div>

            {/* Content */}
            <div className="flex-1 min-w-0 space-y-1.5">
                <p className={cn(
                    "text-[15px] font-semibold leading-snug line-clamp-2",
                    "text-primary-600 group-hover:text-secondary-500 group-hover:underline underline-offset-2 transition-colors duration-150",
                )}>
                    {item.title}
                </p>
                <time dateTime={item.publishedAt} className="text-xs text-slate-500 block">
                    {dateStr}
                </time>
                <p className="text-[13px] text-slate-600 line-clamp-2 leading-relaxed">
                    {item.abstract}
                </p>
                <span className="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-mono bg-slate-100 text-slate-500">
                    PMID: {item.pmid}
                </span>
            </div>
        </a>
    );
}
