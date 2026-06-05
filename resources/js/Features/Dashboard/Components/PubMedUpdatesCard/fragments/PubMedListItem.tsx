// TODO(v2): wire to App\Actions\Articles\FetchPubMedFeedAction
import { type PubMedItem } from "@/Features/Dashboard/Types/pubmed.type";
import { cn } from "@/Core/Utils/utils";

interface PubMedListItemProps {
    item: PubMedItem;
}

export default function PubMedListItem({ item }: PubMedListItemProps) {
    const dateStr = new Date(item.publishedAt).toLocaleDateString("en-US", {
        month: "long",
        day: "numeric",
        year: "numeric",
    });

    return (
        <a
            href={item.href}
            target="_blank"
            rel="noopener noreferrer"
            className="group block space-y-1.5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary-500/50 rounded-lg p-1 -m-1"
        >
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
        </a>
    );
}
