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
            alt: "3D-printed anatomical model",
            colorClass: "bg-linear-to-br from-secondary-300 to-secondary-500",
        },
        {
            alt: "Lab showcase of printed products",
            colorClass: "bg-linear-to-br from-primary-700 to-primary-950",
        },
    ],
};
