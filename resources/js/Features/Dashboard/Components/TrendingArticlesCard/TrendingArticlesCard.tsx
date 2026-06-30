import { Link } from "@inertiajs/react";
import { BookOpen } from "lucide-react";
import { Card, CardHeader, CardTitle, CardBody, Button } from "@/Core/Components/Shared";
import ArticleListItem from "./fragments/ArticleListItem";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { type TrendingArticle } from "@/Features/Dashboard/Types/article.type";

interface TrendingArticlesCardProps {
    articles: TrendingArticle[];
}

export default function TrendingArticlesCard({ articles }: TrendingArticlesCardProps) {
    const { t } = useTranslation();

    return (
        <Card className="flex flex-col">
            <CardHeader className="pb-4 border-b border-slate-100">
                <CardTitle>{t("Trending Articles")}</CardTitle>
                <p className="text-xs text-slate-500 mt-1">
                    {t("Articles with recent increases in activity")}
                </p>
            </CardHeader>
            <CardBody className="flex-1 space-y-5">
                {articles.length > 0 ? (
                    articles.map((article) => (
                        <ArticleListItem key={article.id} article={article} />
                    ))
                ) : (
                    <div className="flex flex-col items-center justify-center py-10 text-center">
                        <div className="w-14 h-14 rounded-full bg-gradient-to-br from-primary-900/10 via-secondary-500/10 to-primary-800/10 border border-primary-700/15 flex items-center justify-center mb-3">
                            <BookOpen className="h-6 w-6 text-secondary-500/70" />
                        </div>
                        <p className="text-sm text-slate-500 font-medium">{t("No trending articles yet.")}</p>
                        <p className="text-xs text-slate-400 mt-1">{t("Check back later.")}</p>
                    </div>
                )}
            </CardBody>
            <div className="px-6 pb-6 pt-2 border-t border-slate-100">
                <Link href="/publications">
                    <Button variant="primary" size="md" className="w-full justify-center">
                        {t("See more trending articles")}
                    </Button>
                </Link>
            </div>
        </Card>
    );
}
