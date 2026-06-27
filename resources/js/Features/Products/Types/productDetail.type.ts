export interface ProductGalleryImage {
    src?: string;
    alt: string;
    colorClass?: string;
}

export interface ProductDetailSpec {
    label: string;
    value: string;
    /** Render the value in cyan (secondary-400) */
    accent?: boolean;
}

export interface ProductStore {
    initial: string;
    name: string;
    type: string;
    location: string;
}

export interface RelatedProduct {
    id: string;
    title: string;
    priceFrom: number;
    href: string;
    thumbnailUrl?: string;
    thumbnailColor?: string;
}

export interface ProductDetail {
    id: string;
    breadcrumb: string[];
    badges: string[];
    galleryCaption: {
        brand: string;
        name: string;
    };
    images: ProductGalleryImage[];
    title: string;
    rating: number;
    reviewCount: number;
    soldCount: number;
    priceLabel: string;
    priceMin: number;
    priceMax: number;
    priceBadges: string[];
    specs: ProductDetailSpec[];
    description: {
        lead: string;
        body: string;
    };
    tags: string[];
    store: ProductStore;
    related: RelatedProduct[];
}
