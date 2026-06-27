export interface PublicationsHeroImage {
    src?: string;
    alt: string;
    colorClass?: string; // Tailwind gradient classes for placeholder
}

export interface PublicationsHero {
    eyebrow: string;
    title: string;
    subtitle: string;
    body: string[];
    images: PublicationsHeroImage[];
}

export interface PublicationListItem {
    id: string;
    title: string;
    slug: string;
    thumbnailUrl: string;
    author: string;
    category: string; // e.g. "Journals", "Papers", "Research"
    publishedAt: string; // ISO date string
    viewCount: number;
    href: string; // /publications/{slug}
    // Row-card fields (optional)
    journal?: string;
    pmid?: string;
    isFreeAccess?: boolean;
}

export interface PublicationDetail extends PublicationListItem {
    abstract: string;
    description: string[]; // paragraphs
    pdfUrl: string; // src for native iframe preview
    fileSize?: string;
    doi?: string;
    keywords: string[];
    related: PublicationListItem[];
}
