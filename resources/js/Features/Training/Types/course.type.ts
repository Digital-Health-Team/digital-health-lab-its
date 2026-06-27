export interface Instructor {
    name: string;
    avatarUrl?: string;
    verified?: boolean;
    title?: string;
}

export interface InstructorWithStudents extends Instructor {
    students: string;
}

export interface Course {
    id: string;
    title: string;
    instructor: Instructor;
    thumbnailUrl: string;
    rating: number;
    ratingCount: number;
    level: "Beginner" | "Intermediate" | "Advanced";
    students: string;
    duration: string;
    category: string;
    extraTags?: number;
    staffPick?: boolean;
    href: string;
}

export interface StaffPickFeature {
    id: string;
    title: string;
    instructor: InstructorWithStudents;
    thumbnailUrl: string;
    duration: string;
    href: string;
}

export interface TrainingHero {
    title: string;
    subtitle: string;
    ctaLabel: string;
    ctaHref: string;
    backgroundUrl: string;
}
