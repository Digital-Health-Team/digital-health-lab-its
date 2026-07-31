import { type TrainingDetail } from "../Types/trainingDetail.type";

export const trainingDetailData: Record<string, TrainingDetail> = {
    "intro-3d-printing-prosthetics": {
        id: 1,
        slug: "intro-3d-printing-prosthetics",
        title: "Intro to 3D Printing for Prosthetics: A Beginner's Guide",
        subtitle:
            "Learn the fundamentals of additive manufacturing as applied to prosthetic devices. Hands-on sessions in the ITS Medtech Lab.",
        previewImageUrl: "/assets/images/training/intro_3d_printing.png",
        rating: 4.9,
        ratingCount: 80,
        students: "27.7k",
        level: "Beginner",
        duration: "5h 57m",
        language: "Indonesian",
        price: 350000,
        isPaid: true,
        participantsCount: 0,
        isFull: false,
        maxParticipants: null,
        instructor: {
            name: "Brent Eviston",
            avatarUrl: "https://picsum.photos/seed/instructor-brent-ev/128/128",
            verified: true,
            title: "Senior Biomedical Engineer, ITS Medtech Lab",
            students: "27.7k",
        },
        whatYouWillLearn: [
            "Understand how FDM and SLA printers work for medical applications",
            "Select appropriate biocompatible filaments for prosthetics",
            "Navigate the slicer workflow from STL to G-code",
            "Post-process and fit printed prosthetic components",
            "Comply with basic medical device safety standards",
            "Troubleshoot common print failures in a clinical context",
        ],
        description:
            "This beginner-friendly training is designed for biomedical engineering students and healthcare professionals who want to leverage 3D printing technology in prosthetic design. Through a combination of theory and hands-on lab sessions at the ITS Medtech Lab, you will gain practical skills to produce functional prosthetic parts from scratch.\n\nNo prior 3D printing experience is required — only a curiosity for how technology can restore quality of life.",
        curriculum: [
            {
                module: "Module 1 — Foundations",
                lessons: [
                    "What is additive manufacturing?",
                    "FDM vs SLA vs SLS: choosing the right technology",
                    "Materials overview: PLA, PETG, TPU, and biocompatible resins",
                ],
            },
            {
                module: "Module 2 — Design for Prosthetics",
                lessons: [
                    "Introduction to open-source prosthetic libraries",
                    "Sizing and anatomy considerations",
                    "Designing attachment interfaces in CAD",
                ],
            },
            {
                module: "Module 3 — Slicing & Printing",
                lessons: [
                    "Slicer configuration for medical parts",
                    "Support strategies and infill patterns",
                    "Live printing session in the ITS Lab",
                ],
            },
            {
                module: "Module 4 — Post-Processing & Fitting",
                lessons: [
                    "Sanding, curing, and surface treatment",
                    "Fitting and patient comfort assessment",
                    "Iterative design and rapid prototyping workflow",
                ],
            },
        ],
        includes: [
            "5h 57m on-demand video",
            "Lab session access (ITS Medtech Lab)",
            "Downloadable STL resource pack",
            "Certificate of completion",
            "Lifetime access to course materials",
        ],
    },

    "surgical-guides-design": {
        id: 2,
        slug: "surgical-guides-design",
        title: "Designing Surgical Guides with Procreate & CAD: 20 Fun Projects for Beginners",
        subtitle:
            "Bridge the gap between artistic illustration and precision engineering. Create patient-specific surgical guides using Procreate sketching and professional CAD tools.",
        previewImageUrl: "/assets/images/training/surgical_guides_cad.png",
        rating: 4.9,
        ratingCount: 149,
        students: "25.3k",
        level: "Beginner",
        duration: "6h 24m",
        language: "Indonesian",
        price: 425000,
        isPaid: true,
        participantsCount: 0,
        isFull: false,
        maxParticipants: null,
        instructor: {
            name: "Lisa Bardot",
            avatarUrl: "https://picsum.photos/seed/instructor-lisa-b/128/128",
            verified: true,
            title: "Medical Illustrator & CAD Specialist",
            students: "25.3k",
        },
        whatYouWillLearn: [
            "Sketch anatomical references with Procreate",
            "Convert 2D sketches into 3D parametric models",
            "Design drilling and cutting guides for common procedures",
            "Export and validate models for 3D printing",
            "Understand sterility and material requirements for surgical tools",
            "Complete 20 guided capstone projects from ideation to print",
        ],
        description:
            "This course takes a creative-first approach to medical device design. You will start with Procreate to sketch anatomical structures, then transition into a professional CAD environment to build precise surgical guides. Twenty mini-projects walk you through real-world clinical scenarios — from osteotomy guides to dental stents.\n\nIdeal for design students, junior biomedical engineers, or anyone combining artistic and technical skills in a healthcare setting.",
        curriculum: [
            {
                module: "Module 1 — Sketching for Engineering",
                lessons: [
                    "Setting up Procreate for technical illustration",
                    "Anatomy tracing from medical imaging",
                    "Exporting sketches as references for CAD",
                ],
            },
            {
                module: "Module 2 — CAD Fundamentals",
                lessons: [
                    "Parametric modeling principles",
                    "Surfacing techniques for organic shapes",
                    "Tolerances and fit for surgical instruments",
                ],
            },
            {
                module: "Module 3 — Projects 1–10",
                lessons: [
                    "Dental impression tray",
                    "Tibial cutting block",
                    "Spinal alignment jig",
                    "Cranial drilling guide",
                    "And 6 more…",
                ],
            },
            {
                module: "Module 4 — Projects 11–20 & Validation",
                lessons: [
                    "Advanced guide geometries",
                    "Tolerance analysis and test printing",
                    "Documentation for regulatory submission",
                ],
            },
        ],
        includes: [
            "6h 24m on-demand video",
            "20 project asset packs (Procreate + CAD files)",
            "CAD software trial license (30 days)",
            "Certificate of completion",
            "Private student community access",
        ],
    },

    "fdm-vs-resin": {
        id: 3,
        slug: "fdm-vs-resin",
        title: "FDM vs Resin: Choosing the Right Material for Medical Applications",
        subtitle:
            "An in-depth comparison of filament and resin 3D printing technologies with a focus on biocompatibility, accuracy, and regulatory requirements in healthcare.",
        previewImageUrl: "/assets/images/training/fdm_vs_resin.png",
        rating: 4.9,
        ratingCount: 6,
        students: "1k",
        level: "Beginner",
        duration: "13h 5m",
        language: "Indonesian",
        price: 550000,
        isPaid: true,
        participantsCount: 0,
        isFull: false,
        maxParticipants: null,
        instructor: {
            name: "Daniel Scott",
            avatarUrl: "https://picsum.photos/seed/instructor-dan-s/128/128",
            verified: true,
            title: "Materials Science Researcher, ITS",
            students: "1k",
        },
        whatYouWillLearn: [
            "Understand the mechanical differences between FDM and MSLA/DLP printing",
            "Evaluate biocompatibility certifications (ISO 10993, USP Class VI)",
            "Choose the right material for skin-contact vs implantable vs surgical-use cases",
            "Run comparative print tests and interpret results",
            "Build a material selection framework for your lab or clinic",
            "Navigate the regulatory landscape for printed medical devices in Indonesia",
        ],
        description:
            "With more than 13 hours of content across 17 modules, this is the most comprehensive material-science training in the ITS Medtech curriculum. You will leave with a systematic decision framework for selecting between FDM and resin technologies — backed by real test data from the ITS materials lab.\n\nRecommended for senior engineering students, R&D teams, and procurement officers responsible for selecting printing solutions in a medical context.",
        curriculum: [
            {
                module: "Module 1 — Technology Primer",
                lessons: [
                    "How FDM/FFF printers deposit material",
                    "How MSLA and DLP resin printers cure layers",
                    "Accuracy, resolution, and surface finish comparison",
                ],
            },
            {
                module: "Module 2 — Biocompatibility Deep-Dive",
                lessons: [
                    "ISO 10993 testing categories",
                    "USP Class VI materials list",
                    "Cytotoxicity and sensitisation testing explained",
                ],
            },
            {
                module: "Module 3 — Material-by-Use-Case",
                lessons: [
                    "Skin-contact devices (orthoses, wearables)",
                    "Surgical instruments and guides",
                    "Short-term implantable prototypes",
                    "Lab jigs and fixtures",
                ],
            },
            {
                module: "Module 4 — Regulatory Landscape",
                lessons: [
                    "Indonesian BPOM classification for printed devices",
                    "CE marking pathways for EU export",
                    "Documentation best practices",
                ],
            },
        ],
        includes: [
            "13h 5m on-demand video",
            "Material comparison spreadsheet (Excel + Google Sheets)",
            "Lab test data PDFs",
            "Regulatory checklist templates",
            "Certificate of completion",
            "6 months Q&A access with instructor",
        ],
    },
};
