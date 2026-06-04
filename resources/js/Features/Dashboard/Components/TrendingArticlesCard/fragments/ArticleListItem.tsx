import { type TrendingArticle } from "@/Features/Dashboard/Types/article.type";
import { cn } from "@/Core/Utils/utils";

interface ArticleListItemProps {
    article: TrendingArticle;
}

const tagColors: Record<string, string> = {
    "Free PMC article": "text-emerald-600 font-medium",
    "Clinical Trial": "text-blue-600 font-medium",
    "Review": "text-violet-600 font-medium",
    "No abstract available": "text-slate-400",
};

export default function ArticleListItem({ article }: ArticleListItemProps) {
    return (
        <a
            href={article.href}
            className="group block space-y-1.5 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary-500/50 rounded-lg p-1 -m-1"
        >
            <p className={cn(
                "text-[15px] font-semibold leading-snug line-clamp-2",
                "text-primary-600 group-hover:text-secondary-500 group-hover:underline underline-offset-2 transition-colors duration-150",
            )}>
                {article.title}
            </p>
            <p className="text-xs text-slate-500">
                {article.author} · {new Date(article.publishedAt).getFullYear()}
            </p>
            <p className="text-[13px] text-slate-600 line-clamp-2 leading-relaxed">
                {article.abstract}
            </p>
            {article.tags.length > 0 && (
                <div className="flex flex-wrap gap-2">
                    {article.tags.map((tag) => (
                        <span key={tag} className={cn("text-xs", tagColors[tag] ?? "text-slate-500")}>
                            {tag}.
                        </span>
                    ))}
                </div>
            )}
        </a>
    );
}
