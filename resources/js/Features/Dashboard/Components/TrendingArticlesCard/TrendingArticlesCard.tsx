import { Link } from "@inertiajs/react";
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
                {articles.map((article) => (
                    <ArticleListItem key={article.id} article={article} />
                ))}
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
