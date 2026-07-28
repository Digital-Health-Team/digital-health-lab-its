import { Capability, AboutHeadlineWord } from "../Types/aboutSection.type";

export const capabilities: Capability[] = [
    {
        tag: "3D Innovation",
        title: "High-Precision 3D Printing",
        description:
            "Design and fabrication of implants, prosthetics, and anatomical models using additive manufacturing with biocompatible materials.",
        accent: "#00A8B5",
        image: "https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=400&h=400&fit=crop&crop=center",
        imageAlt: "3D printer fabricating a precision object",
    },
    {
        tag: "Custom Order",
        title: "Custom Design & Production Services",
        description:
            "Made-to-order services for hospitals, clinics, and educational institutions — from digital concept to a finished physical product.",
        accent: "#FFC72C",
        image: "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=400&h=400&fit=crop&crop=center",
        imageAlt: "Engineer working on a custom product design",
    },
    {
        tag: "Digital Repository",
        title: "Centralised Publication Repository",
        description:
            "Journals, research reports, and technical documentation in one open platform that supports cross-disciplinary access and collaboration.",
        accent: "#22D3EE",
        image: "https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=400&fit=crop&crop=center",
        imageAlt: "Research library with open books and journals",
    },
];

/**
 * Source sentence for the animated headline. " / " marks the line break and the
 * accent word is highlighted — parseHeadlineWords() in AboutSection turns this
 * into the per-word array GSAP staggers over, so translations never have to be
 * hand-split into words.
 */
export const HEADLINE_SENTENCE = "Bridging Innovation / Health and Engineering.";
export const HEADLINE_ACCENT = "Engineering.";
