import { ArticleEntry } from "../Types/articlesSection.type";

/**
 * Static mirror of config/lab-news.php (user-chosen data strategy: hardcoded
 * + CMS overrides, no live DB query). Lead = the featured row (config index
 * 0); index = the next four, newest first. Overridable via the
 * `articles_featured` / `articles_entry_*` CMS keys. Hrefs point at the real
 * per-article page served by NewsController.
 */
export const featuredArticle: ArticleEntry = {
    title: "IDIG Runs a 3D-Printed Prosthetics Workshop for High-School Students",
    category: "Workshop",
    date: "12 June 2025",
    excerpt:
        "Dozens of high-school students from across Surabaya worked through the full process, from design to final prosthetic assembly, guided by the laboratory research team.",
    href: "/news/workshop-cetak-3d-prostetik-pelajar-sma",
    image: "/assets/images/projects/clinical_3d_printing.png",
    imageAlt: "Workshop participants observing the 3D printing of a prosthetic at the IDIG HTech laboratory",
};

export const articleEntries: ArticleEntry[] = [
    {
        title: "Lab Team Trials 3D-Printed Orthoses at RSUD Dr. Soetomo",
        category: "Visit",
        date: "5 June 2025",
        excerpt:
            "A joint visit with the medical rehabilitation unit tested the fit of 3D-printed foot orthoses on trial patients.",
        href: "/news/kunjungan-rsud-dr-soetomo-uji-ortosis",
        image: "/assets/images/projects/prosthetic_limb_3d.png",
        imageAlt: "A 3D-printed foot orthosis being trialled with the medical rehabilitation team",
    },
    {
        title: "IDIG Team Wins the National Health Technology Innovation Competition",
        category: "Achievement",
        date: "28 May 2025",
        excerpt:
            "An IoT-based patient monitoring prototype built by laboratory students took first place at the national innovation awards.",
        href: "/news/juara-kompetisi-inovasi-teknologi-kesehatan-2025",
        image: "/assets/images/projects/patient_monitoring_iot.png",
        imageAlt: "The IoT-based patient monitoring prototype that won first place at the national competition",
    },
    {
        title: "New Resin Printer Strengthens the Laboratory's Precision Fabrication",
        category: "Lab News",
        date: "20 May 2025",
        excerpt:
            "The latest-generation resin printer is now in operation, expanding the laboratory's capacity to print high-precision medical components.",
        href: "/news/printer-resin-baru-fabrikasi-presisi",
        image: "/assets/images/projects/stl_medical_devices.png",
        imageAlt: "The new resin printer used for high-precision medical component fabrication",
    },
    {
        title: "Research Collaboration with Universitas Airlangga Medical Faculty Begins",
        category: "Collaboration",
        date: "9 May 2025",
        excerpt:
            "A joint research memorandum with the Universitas Airlangga Faculty of Medicine opens the way for clinically driven medical assistive devices.",
        href: "/news/kolaborasi-riset-fk-universitas-airlangga",
        image: "/assets/images/projects/medtech_research_digest_v4.png",
        imageAlt: "Signing the collaborative research memorandum with the Universitas Airlangga Faculty of Medicine",
    },
];
