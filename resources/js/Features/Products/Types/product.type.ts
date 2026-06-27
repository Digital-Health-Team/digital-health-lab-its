export interface ProductCard {
    id: string;
    title: string;
    caption: string;
    thumbnailColor?: string; // Tailwind gradient classes used as placeholder
    thumbnailUrl?: string;
    href: string;
    priceMin: number; // IDR
    priceMax: number; // IDR
    /** Number of grid columns the card occupies in the bento layout (default 1) */
    span?: 1 | 2;
    /** Tailwind aspect-ratio class, e.g. "aspect-[3/4]", "aspect-square", "aspect-[16/9]" */
    aspect?: string;
}

export interface ProductCategory {
    id: string;
    label: string;
    cards: ProductCard[];
}

export interface ProductsHeroImage {
    src?: string;
    alt: string;
    colorClass?: string; // Tailwind gradient classes for placeholder
}

export interface ProductsHero {
    eyebrow: string;
    title: string;
    subtitle: string;
    body: string[];
    images: ProductsHeroImage[];
}
