import { type Course, type InstructorWithStudents } from "./course.type";

export interface TrainingCurriculumModule {
    module: string;
    lessons: string[];
}

export interface TrainingDetail {
    id: number;
    slug: string;
    title: string;
    subtitle?: string;
    previewImageUrl?: string;
    thumbnailUrl?: string;
    rating?: number;
    ratingCount?: number;
    students?: string;
    level: "Beginner" | "Intermediate" | "Advanced";
    duration?: string;
    language?: string;
    price: number;
    isPaid: boolean;
    date?: string;
    location?: string;
    participantsCount: number;
    isFull: boolean;
    maxParticipants: number | null;
    instructor: InstructorWithStudents;
    whatYouWillLearn: string[];
    description?: string;
    curriculum: TrainingCurriculumModule[];
    includes: string[];
}
