import { type ResearchHero } from "@/Features/Research/Types/research.type";

// ponytail: hero prose stays hardcoded like the rest of this page; add lang keys when the copy is finalised.
export const researchHeroData: ResearchHero = {
    eyebrow: "Medical Innovation & Research",
    title: "Research",
    subtitle:
        "Student projects and peer-reviewed publications from ITS Medical Technology, in one place.",
    body: [
        "Discover cutting-edge work in medical device fabrication, rehabilitation technology, and biocompatible materials developed right here at ITS — from early prototypes to published findings.",
        "Our student and faculty output spans additive manufacturing, wearable sensors, prosthetics, orthotics, and IoT-enabled health monitoring systems.",
    ],
    images: [
        {
            src: "/assets/images/projects/projects_hero_secondary.png",
            alt: "Medical device innovation",
        },
        {
            src: "/assets/images/projects/projects_hero_main.png",
            alt: "Students working on whiteboard",
        },
    ],
    ctas: [
        { label: "View All Projects", href: "#projects", tone: "navy" },
        { label: "View All Publications", href: "#publications", tone: "cyan" },
    ],
};
