export interface FeaturedPublication {
    id: string;
    title: string;
    coverUrl: string;
    category: string;
    href: string;
    publishedAt: string;
}

export interface PublicationRow {
    id: string;
    title: string;
    thumbnailUrl: string;
    author: string;
    publishedAt: string;
    viewCount: number;
    status: "verified" | "pending" | "rejected";
    category: string;
    href: string;
}
