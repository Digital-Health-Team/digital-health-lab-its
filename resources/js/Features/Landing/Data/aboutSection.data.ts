import { Capability, AboutHeadlineWord, AboutMediaItem } from "../Types/aboutSection.type";

const IMG_BASE = "/assets/images";

/** Act 1's image stack. Order is paint order — the second circle overlaps the first. */
export const ABOUT_MEDIA: AboutMediaItem[] = [
    {
        image: `${IMG_BASE}/projects/craniosynostosis_ct_detection.png`,
        alt: "Craniosynostosis suture analysis on a clinical imaging workstation",
        accent: "#22D3EE",
    },
    {
        image: `${IMG_BASE}/projects/biomedical_signal_ml.png`,
        alt: "Neural network classifying ECG and EEG signals in real time",
        accent: "#FFC72C",
    },
];

/** Zigzag is authored here, not derived from the index — same convention as `align` in servicesSection.data.ts. */
export const capabilities: Capability[] = [
    {
        tag: "Medical Simulation",
        title: "Anatomical Models & Educational Manikins",
        description:
            "Development and manufacture of anatomical models and medical simulation manikins — catalogue or fully custom — to improve the effectiveness of clinical training and medical education.",
        accent: "#00A8B5",
        image: `${IMG_BASE}/categories/educational.png`,
        imageAlt: "Anatomical teaching model used in clinical training",
        imageSide: "right",
    },
    {
        tag: "Surgical Technology",
        title: "Precision Implants & Surgical Guides",
        description:
            "Manufacture of biocompatible implants and personalised or standard surgical guides, engineered to precision with 3D printing for optimal surgical accuracy.",
        accent: "#FFC72C",
        image: `${IMG_BASE}/categories/prosthetics.png`,
        imageAlt: "Biocompatible implant and surgical guide components",
        imageSide: "left",
    },
    {
        tag: "Rehabilitation Technology",
        title: "Recovery Devices & Assistive Aids",
        description:
            "Design and production of medical rehabilitation manikins and assistive devices — standard or custom — matched to each patient's physical recovery and therapy needs.",
        accent: "#22D3EE",
        image: `${IMG_BASE}/categories/aid_bands.png`,
        imageAlt: "Assistive rehabilitation aids for physical recovery",
        imageSide: "right",
    },
    {
        tag: "Medical Instrumentation",
        title: "Medical Device Development & Prototyping",
        description:
            "Fabrication of medical instruments and technical test models, from our product line or to custom specification, supporting functional testing and the development of current health technology.",
        accent: "#00A8B5",
        image: `${IMG_BASE}/projects/wearable_sensor_housing.png`,
        imageAlt: "Prototype housing for a wearable medical sensor",
        imageSide: "left",
    },
    {
        tag: "Innovation Services",
        title: "Health Technology Consulting & Solutions",
        description:
            "Comprehensive end-to-end support from research and design through to prototyping, accelerating and commercialising innovation in the medical field.",
        accent: "#FFC72C",
        image: `${IMG_BASE}/categories/3d_designs.png`,
        imageAlt: "Digital 3D design of a medical device in progress",
        imageSide: "right",
    },
    {
        tag: "Research & Collaboration",
        title: "Project Partnerships & Joint Development",
        description:
            "A strategic collaboration space for academics, medical practitioners, and industry to run applied research projects that produce the health solutions of the future.",
        accent: "#22D3EE",
        image: `${IMG_BASE}/categories/projects.png`,
        imageAlt: "Applied research project carried out in partnership",
        imageSide: "left",
    },
    {
        tag: "Education & Training",
        title: "Medical Technology Workshops & Training",
        description:
            "Scientific events, seminars, and certified hands-on training that build the skills of medical professionals and researchers in applying modern health technology.",
        accent: "#FFC72C",
        image: `${IMG_BASE}/categories/events.png`,
        imageAlt: "Hands-on medical technology workshop session",
        imageSide: "right",
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
