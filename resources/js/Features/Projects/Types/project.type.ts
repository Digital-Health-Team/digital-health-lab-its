export interface ProjectCard {
    id: string;
    title: string;
    caption: string;
    thumbnailUrl?: string;
    thumbnailColor?: string; // Tailwind gradient classes used when no thumbnailUrl
    href: string;
    /** Number of grid columns the card occupies in the bento layout (default 1) */
    span?: 1 | 2;
    /** Tailwind aspect-ratio class, e.g. "aspect-[3/4]", "aspect-square", "aspect-[16/9]" */
    aspect?: string;
}

export interface ProjectCategory {
    id: string;
    label: string;
    cards: ProjectCard[];
}
