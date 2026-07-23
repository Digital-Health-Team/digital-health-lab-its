export interface NewsListItem {
    slug: string;
    title: string;
    category: string; // e.g. "Workshop", "Kunjungan", "Prestasi", "Kabar Lab", "Kolaborasi"
    date: string; // pre-formatted display date (e.g. "12 Juni 2025")
    excerpt: string;
    image: string;
    imageAlt: string;
    href: string; // /news/{slug}
}

export interface NewsDetail extends NewsListItem {
    body: string[]; // paragraphs
}
