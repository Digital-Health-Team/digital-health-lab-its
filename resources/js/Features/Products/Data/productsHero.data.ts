import { type ProductsHero } from "../Types/product.type";

export const productsHeroData: ProductsHero = {
    eyebrow: "Made-by-Order 3D Printing",
    title: "Our Products",
    subtitle: "Precision-crafted medical models and assistive devices, made to order.",
    body: [
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam. Lorem ipsum dolor s.",
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam. Lorem ipsum dolor s.",
    ],
    images: [
        {
            src: "/assets/images/hero/products_hero_accent.png",
            alt: "3D-printed anatomical model",
        },
        {
            src: "/assets/images/hero/products_hero_showcase.png",
            alt: "Lab showcase of printed products",
        },
    ],
};
