export interface Product {
    id: string;
    title: string;
    priceLabel: string;
    coverUrl: string;
    badge?: "new" | "sale";
    rating: number;
    seller: string;
    href: string;
}

export interface Service {
    id: string;
    title: string;
    priceLabel: string;
    coverUrl: string;
    href: string;
    rating: number;
    seller: string;
}
