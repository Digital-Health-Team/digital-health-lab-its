export interface Service {
    title: string;
    body: string;
    image: string;
    /** Extra slides shown after `image` in the card carousel. */
    gallery: string[];
    alt: string;
    gradient: string;
    align: "left" | "right";
    tilt: number;
    href: string;
}
