import { type Course, type InstructorWithStudents } from "./course.type";

export interface TrainingCurriculumModule {
    module: string;
    lessons: string[];
}

export interface TrainingDetail {
    slug: string;
    title: string;
    subtitle: string;
    breadcrumb: string[];
    previewImageUrl: string;
    rating: number;
    ratingCount: number;
    students: string;
    level: "Beginner" | "Intermediate" | "Advanced";
    duration: string;
    language: string;
    price: number;
    instructor: InstructorWithStudents;
    whatYouWillLearn: string[];
    description: string;
    curriculum: TrainingCurriculumModule[];
    includes: string[];
    related: Course[];
}
