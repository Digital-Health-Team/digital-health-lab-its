import { type FeaturedPublication } from "../Types/publication.type";

export const featuredPublications: FeaturedPublication[] = [
    {
        id: "fp1",
        title: "Adaptive Prosthetic Hand Design",
        coverUrl: "https://picsum.photos/seed/fp-prosthetic-hand/600/600",
        category: "Projects",
        href: "/projects/adaptive-prosthetic-hand",
        publishedAt: "2025-11-10",
    },
    {
        id: "fp2",
        title: "3D Bioprinting of Cartilage Scaffolds",
        coverUrl: "https://picsum.photos/seed/fp-cartilage-scaffold/600/600",
        category: "Research",
        href: "/projects/bioprinting-cartilage",
        publishedAt: "2025-10-22",
    },
    {
        id: "fp3",
        title: "Smart Knee Brace with IoT Monitoring",
        coverUrl: "https://picsum.photos/seed/fp-smart-knee/600/600",
        category: "Innovation",
        href: "/projects/smart-knee-brace",
        publishedAt: "2025-09-15",
    },
];
