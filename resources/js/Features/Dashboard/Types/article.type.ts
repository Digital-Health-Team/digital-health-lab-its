export interface TrendingArticle {
    id: string;
    title: string;
    thumbnailUrl: string | null;
    author: string;
    publishedAt: string;
    abstract: string;
    tags: string[];
    href: string;
}
