export interface Product {
    id: string;
    title: string;
    priceLabel: string;
    coverUrl: string | null;
    badge?: "new" | "sale";
    rating: number | null;
    seller: string;
    href: string;
}

export interface Service {
    id: string;
    title: string;
    priceLabel: string;
    coverUrl: string | null;
    href: string;
    rating: number | null;
    seller: string;
    description?: string;
    iconPath?: string;
    imageGradient?: string;
    ctaLabel?: string;
    variant?: "primary" | "outline";
}
