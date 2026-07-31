import { CollaborationPartner } from "../Types/collaborationSection.type";

const IMG_BASE = "/assets/images/projects";

const SHADOW_SIDE = "0 14px 44px rgba(3,16,38,0.45)";
const SHADOW_CENTER = "0 24px 70px rgba(3,16,38,0.55)";

/**
 * Placeholder partner registry — swap names, copy, and print images with the
 * real collaboration record here or via the `collaboration_chapter_*` CMS keys.
 * Print positions are per-chapter layouts; keep them distinct between chapters.
 */
export const partners: CollaborationPartner[] = [
    {
        name: "RSUD Dr. Soetomo",
        nameLines: ["RSUD", "Dr. Soetomo"],
        type: "Clinical Partnership",
        period: "2023—Present",
        description:
            "Clinical validation of implant and prosthetic prototypes — from anatomical model testing through to device evaluation inside the hospital itself.",
        align: "left",
        prints: [
            {
                image: `${IMG_BASE}/clinical_3d_printing.png`,
                width: "38%",
                aspectRatio: "3/4",
                top: "2%",
                left: "2%",
                rot: -7,
                z: 1,
                shadow: SHADOW_SIDE,
            },
            {
                image: `${IMG_BASE}/prosthetic_limb_3d.png`,
                width: "56%",
                aspectRatio: "4/3",
                top: "12%",
                left: "24%",
                rot: -2,
                z: 3,
                shadow: SHADOW_CENTER,
                center: true,
            },
            {
                image: `${IMG_BASE}/craniosynostosis_model_kit.png`,
                width: "34%",
                aspectRatio: "1/1",
                top: "50%",
                left: "63%",
                rot: 5,
                z: 2,
                shadow: SHADOW_SIDE,
            },
        ],
    },
    {
        name: "IDIG RCMED — Universitas Airlangga",
        nameLines: ["IDIG RCMED", "Universitas Airlangga"],
        type: "Research Collaboration",
        period: "2024—Present",
        description:
            "Cross-campus research into CT-image-based early detection and biomedical signal analysis, alongside our sister laboratory at Universitas Airlangga.",
        align: "right",
        prints: [
            {
                image: `${IMG_BASE}/craniosynostosis_ct_detection.png`,
                width: "62%",
                aspectRatio: "4/3",
                top: "12%",
                left: "4%",
                rot: 2.5,
                z: 2,
                shadow: SHADOW_CENTER,
                center: true,
            },
            {
                image: `${IMG_BASE}/biomedical_signal_ml.png`,
                width: "40%",
                aspectRatio: "3/4",
                top: "20%",
                left: "58%",
                rot: -6,
                z: 1,
                shadow: SHADOW_SIDE,
            },
        ],
    },
    {
        name: "ITS Innovation Hub",
        nameLines: ["ITS", "Innovation Hub"],
        type: "Incubation & Commercialisation",
        period: "2025",
        description:
            "Incubating and commercialising the laboratory's work — showcasing medical prototypes at the annual exhibition and connecting them with industry partners.",
        align: "left",
        prints: [
            {
                image: `${IMG_BASE}/its_innovation_hub_annual_2025.png`,
                width: "40%",
                aspectRatio: "4/5",
                top: "4%",
                left: "2%",
                rot: 6,
                z: 1,
                shadow: SHADOW_SIDE,
            },
            {
                image: `${IMG_BASE}/stl_medical_devices.png`,
                width: "30%",
                aspectRatio: "1/1",
                top: "2%",
                left: "68%",
                rot: -8,
                z: 2,
                shadow: SHADOW_SIDE,
            },
            {
                image: `${IMG_BASE}/medtech_annual_showcase.png`,
                width: "54%",
                aspectRatio: "4/3",
                top: "34%",
                left: "30%",
                rot: -2,
                z: 3,
                shadow: SHADOW_CENTER,
                center: true,
            },
        ],
    },
    {
        name: "Dinas Kesehatan Provinsi Jawa Timur",
        nameLines: ["Dinas Kesehatan", "Provinsi Jawa Timur"],
        type: "Training Programme",
        period: "2024—Present",
        description:
            "Training programmes on medical teaching aids and laboratory safety for healthcare workers across East Java.",
        align: "right",
        prints: [
            {
                image: `${IMG_BASE}/spine_teaching_aid.png`,
                width: "60%",
                aspectRatio: "3/2",
                top: "14%",
                left: "28%",
                rot: -3,
                z: 2,
                shadow: SHADOW_CENTER,
                center: true,
            },
            {
                image: `${IMG_BASE}/lab_safety_guide.png`,
                width: "36%",
                aspectRatio: "3/4",
                top: "32%",
                left: "4%",
                rot: 7,
                z: 3,
                shadow: SHADOW_SIDE,
            },
        ],
    },
];
