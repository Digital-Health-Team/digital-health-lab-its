export interface ResearchHeroImage {
    src?: string;
    alt: string;
    colorClass?: string; // Tailwind gradient classes for placeholder
}

export interface ResearchHeroCta {
    label: string;
    href: string; // same-page anchor, e.g. "#projects"
    tone: "navy" | "cyan";
}

export interface ResearchHero {
    eyebrow: string;
    title: string;
    subtitle: string;
    body: string[];
    images: ResearchHeroImage[];
    ctas: ResearchHeroCta[];
}
