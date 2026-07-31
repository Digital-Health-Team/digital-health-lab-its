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
            className="group flex gap-4 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary-500/50 rounded-lg p-1 -m-1"
        >
            {/* Portrait cover thumbnail */}
            <div className="shrink-0 w-16 sm:w-20 aspect-[3/4] rounded-md overflow-hidden ring-1 ring-primary-900/10">
                {article.thumbnailUrl ? (
                    <div
                        className="w-full h-full"
                        style={{
                            backgroundImage: `url(${article.thumbnailUrl})`,
                            backgroundSize: "cover",
                            backgroundPosition: "center top",
                        }}
                    />
                ) : (
                    <div className="w-full h-full bg-gradient-to-br from-primary-900 to-secondary-600/80" />
                )}
            </div>

            {/* Content */}
            <div className="flex-1 min-w-0 space-y-1.5">
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
            </div>
        </a>
    );
}
