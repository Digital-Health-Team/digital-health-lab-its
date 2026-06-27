import { type ProjectCard } from "./project.type";

export interface ProjectAuthor {
    name: string;
    nim: string;
    faculty: string;
    avatarUrl?: string;
}

export interface ProjectDetailSpec {
    label: string;
    value: string;
}

export interface ProjectDownloadFile {
    fileUrl: string;
    fileName: string;
    fileType: string;
    fileSize: string;
}

export interface ProjectDetail {
    slug: string;
    title: string;
    category: string;
    breadcrumb: string[];
    coverUrl?: string;
    coverColor?: string;
    description: string[];
    highlights: string[];
    author: ProjectAuthor;
    specs: ProjectDetailSpec[];
    downloadFile: ProjectDownloadFile;
    includes: string[];
    related: ProjectCard[];
}
