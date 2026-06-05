export interface PubMedItem {
    id: string;
    pmid: string;
    title: string;
    authors: string[];
    journal: string;
    publishedAt: string;
    abstract: string;
    tags: string[];
    href: string;
}
