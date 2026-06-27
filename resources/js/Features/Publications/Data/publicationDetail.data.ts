import { type PublicationDetail } from "@/Features/Publications/Types/publication.type";

export const publicationDetailData: PublicationDetail = {
    id: "pub-1",
    title: "Design and Fabrication of a Low-Cost 3D-Printed Prosthetic Arm",
    slug: "prosthetic-arm-low-cost",
    thumbnailUrl: "https://picsum.photos/seed/pub-prosthetic-arm/800/560",
    author: "Ahmad Farhan",
    category: "Journals",
    publishedAt: "2025-11-05",
    viewCount: 1240,
    href: "/publications/prosthetic-arm-low-cost",

    // Native iframe — a publicly available sample PDF for preview
    pdfUrl: "https://www.w3.org/WAI/WCAG21/Techniques/pdf/PDF2.pdf",
    fileSize: "2.4 MB",
    doi: "10.1234/its.medtech.2025.0042",

    abstract:
        "This paper presents the design and fabrication of a low-cost transradial prosthetic arm using fused deposition modelling (FDM) 3D printing. The device achieves a full range of grip patterns at under 15 % of the cost of commercially available myoelectric alternatives, with a total fabricated mass of 380 g. Mechanical characterisation confirms a tensile strength of 42 MPa using PETG filament for structural members and TPU for interface sockets.",

    description: [
        "Prosthetic limb access remains severely limited in low-and-middle-income settings, where cost is the primary barrier. Additive manufacturing offers a pathway to radically reduce per-unit costs while enabling local customisation. This work focuses on a body-powered transradial design with a voluntary-opening terminal device, fully printable on any consumer FDM machine.",
        "The arm was validated through standardised gripping force tests (SHAP protocol, 14 object classes) and a six-week user trial with five adult amputees. All participants achieved functional independence scores above 80 % for activities of daily living within two weeks of fitting, comparable to results reported for commercially produced devices at 12× the cost.",
        "Material selection was guided by a multi-criteria decision matrix weighing biocompatibility, printability, moisture resistance, and cost. PETG was chosen for structural components and TPU-95A for socket liners and flexible joints. The total bill-of-materials cost, including hardware, is under IDR 450,000 at Indonesian retail prices.",
    ],

    keywords: [
        "3D printing",
        "prosthetics",
        "PETG",
        "FDM",
        "assistive technology",
        "medical devices",
        "upper limb",
    ],

    related: [
        {
            id: "pub-3",
            title: "Human Gait Analysis Using Wearable IMU Sensors for Orthotic Fitting",
            slug: "gait-imu-orthotic",
            thumbnailUrl: "https://picsum.photos/seed/pub-gait-imu/480/320",
            author: "Nur Hidayat",
            category: "Journals",
            publishedAt: "2025-10-14",
            viewCount: 652,
            href: "/publications/gait-imu-orthotic",
        },
        {
            id: "pub-4",
            title: "Topology Optimisation of Ankle-Foot Orthosis with Variable Infill",
            slug: "topology-afo",
            thumbnailUrl: "https://picsum.photos/seed/pub-topology-afo/480/320",
            author: "Dewi Kartika",
            category: "Research",
            publishedAt: "2025-09-30",
            viewCount: 438,
            href: "/publications/topology-afo",
        },
        {
            id: "pub-7",
            title: "Biocompatibility Assessment of PETG Scaffolds for Tissue Engineering",
            slug: "petg-scaffold-biocompat",
            thumbnailUrl: "https://picsum.photos/seed/pub-petg-scaffold/480/320",
            author: "Hendra Wijaya",
            category: "Journals",
            publishedAt: "2025-07-18",
            viewCount: 510,
            href: "/publications/petg-scaffold-biocompat",
        },
        {
            id: "pub-2",
            title: "Parametric Analysis of FDM Layer Thickness on Mechanical Properties",
            slug: "fdm-parametric-analysis",
            thumbnailUrl: "https://picsum.photos/seed/pub-fdm-analysis/480/320",
            author: "Rizki Amalia",
            category: "Papers",
            publishedAt: "2025-10-28",
            viewCount: 874,
            href: "/publications/fdm-parametric-analysis",
        },
    ],
};
