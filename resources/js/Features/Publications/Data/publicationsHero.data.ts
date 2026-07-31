import { type PublicationsHero } from "@/Features/Publications/Types/publication.type";

export const publicationsHeroData: PublicationsHero = {
    eyebrow: "Medical Research",
    title: "Publications",
    subtitle: "Peer-reviewed journals and research papers from ITS Medical Technology students.",
    body: [
        "Discover cutting-edge research in medical device fabrication, rehabilitation technology, and biocompatible materials developed right here at ITS.",
        "Our student and faculty publications span additive manufacturing, wearable sensors, prosthetics, orthotics, and IoT-enabled health monitoring systems.",
    ],
    images: [
        {
            src: "/assets/images/publications/publication_hero_secondary.png",
            alt: "Researcher reviewing a medical journal",
        },
        {
            src: "/assets/images/publications/publication_hero_main.png",
            alt: "Students in a medical technology lab",
        },
    ],
};
