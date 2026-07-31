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
    id: number;
    slug: string;
    href: string;
    title: string;
    instructor: Instructor;
    thumbnailUrl?: string;
    rating?: number;
    ratingCount?: number;
    level: "Beginner" | "Intermediate" | "Advanced";
    students?: string;
    duration?: string;
    category?: string;
    extraTags?: number;
    staffPick?: boolean;
    price: number;
    isPaid: boolean;
    date?: string;
    location?: string;
    instructorName?: string;
    instructorAvatarUrl?: string;
    participantsCount?: number;
}

export interface StaffPickFeature {
    id: number;
    slug: string;
    href: string;
    title: string;
    subtitle?: string;
    description?: string;
    instructor: InstructorWithStudents;
    thumbnailUrl?: string;
    duration?: string;
    price: number;
    isPaid: boolean;
}

export interface TrainingHero {
    title: string;
    subtitle: string;
    ctaLabel: string;
    ctaHref: string;
    backgroundUrl: string;
}
