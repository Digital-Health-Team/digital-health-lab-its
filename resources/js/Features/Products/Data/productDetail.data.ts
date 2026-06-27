import { type ProductDetail } from "../Types/productDetail.type";

export const productDetailData: ProductDetail = {
    id: "am1",
    breadcrumb: ["Home", "Products", "Anatomical Models", "Craniosynostosis 3D Skull Model"],
    badges: ["NEW", "CLINICAL GRADE"],
    galleryCaption: {
        brand: "Medical Technology Lab",
        name: "Anatomical Model",
    },
    images: [
        {
            src: "/assets/images/products/am1.png",
            alt: "Craniosynostosis 3D Skull Model - Main Frontal View",
        },
        {
            src: "/assets/images/products/am4.png",
            alt: "Internal anatomical features - Brain Hemisphere Model",
        },
        {
            src: "/assets/images/products/am3.png",
            alt: "Anatomical cross-section comparison - Heart Anatomy",
        },
        {
            src: "/assets/images/products/am2.png",
            alt: "Mounted skeletal support - Full Spine Model",
        },
        {
            src: "/assets/images/hero/products_hero_accent.png",
            alt: "Biomedical lab quality control inspection view",
        },
    ],
    title: "Craniosynostosis 3D Skull Model",
    rating: 4.8,
    reviewCount: 34,
    soldCount: 89,
    priceLabel: "ESTIMASI HARGA",
    priceMin: 150000,
    priceMax: 250000,
    priceBadges: ["High Accuracy", "CT-Scan Based"],
    specs: [
        { label: "KONDISI", value: "Baru" },
        { label: "BERAT ESTIMASI", value: "350 g" },
        { label: "MIN. BELI", value: "1 Buah" },
        { label: "KATEGORI", value: "Anatomical Models", accent: true },
        { label: "ETALASE", value: "Surgical Training Models", accent: true },
        { label: "BAHAN", value: "Medical-Grade PLA" },
    ],
    description: {
        lead: "Craniosynostosis Skull Anatomical Model (MUSEUM QUALITY):",
        body: "Highly detailed, 3D-printed skull replica showcasing premature fusion of cranial sutures (craniosynostosis). Created using medical-grade PLA filament from clinical CT scans. Perfect for medical students, anatomical teaching, surgical simulation prep, and patient consultation. Features exceptionally smooth surfaces, realistic bone textures, and high dimensional accuracy.",
    },
    tags: ["3D Printed", "Anatomical Model", "CT Scan Replica", "Medical Grade PLA"],
    store: {
        initial: "M",
        name: "ITS Medical Lab Store",
        type: "Official Lab Store",
        location: "Surabaya",
    },
    related: [
        {
            id: "am2",
            title: "Full Spine Teaching Model",
            priceFrom: 200000,
            href: "/products",
            thumbnailUrl: "/assets/images/products/am2.png",
        },
        {
            id: "am3",
            title: "Heart Anatomy Cross-Section",
            priceFrom: 175000,
            href: "/products",
            thumbnailUrl: "/assets/images/products/am3.png",
        },
        {
            id: "am4",
            title: "Brain Hemisphere Model",
            priceFrom: 180000,
            href: "/products",
            thumbnailUrl: "/assets/images/products/am4.png",
        },
        {
            id: "pr1",
            title: "Prosthetic Hand Prototype",
            priceFrom: 500000,
            href: "/products",
            thumbnailUrl: "/assets/images/products/pr1.png",
        },
    ],
};
