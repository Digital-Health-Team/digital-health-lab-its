export interface ArticleEntry {
    title: string;
    author: string;
    category: string;
    year: string;
    href: string;
}

export interface ArticleFeature extends ArticleEntry {
    image: string;
    imageAlt: string;
}
