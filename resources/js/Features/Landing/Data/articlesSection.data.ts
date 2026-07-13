import { ArticleEntry, ArticleFeature } from "../Types/articlesSection.type";

const IMG_BASE = "/assets/images/publications";

/**
 * Static mirror of database/seeders/PublicationSeeder.php (user-chosen data
 * strategy: hardcoded + CMS overrides, no live DB query). Lead = the featured
 * row; index = the rest, newest first. Overridable via the
 * `articles_featured` / `articles_entry_*` CMS keys.
 */
export const featuredArticle: ArticleFeature = {
    title: "Design and Fabrication of a Low-Cost 3D-Printed Prosthetic Arm",
    author: "Andi Pratama, Budi Santoso",
    category: "Journals",
    year: "2025",
    href: "/publications/low-cost-3d-printed-prosthetic-arm",
    image: `${IMG_BASE}/pub_cover_prosthetic_arm.png`,
    imageAlt: "Sampul publikasi: purwarupa lengan prostetik cetak 3D berbiaya rendah",
};

export const articleEntries: ArticleEntry[] = [
    {
        title: "Parametric Analysis of FDM Print Parameters on Mechanical Properties of Orthotic Devices",
        author: "Dewi Rahayu, Rizky Fauzan",
        category: "Papers",
        year: "2025",
        href: "/publications/fdm-parametric-analysis-orthotic-mechanical-properties",
    },
    {
        title: "IoT-Enabled Remote Rehabilitation Monitoring for Elderly Patients",
        author: "Rini Anggraini, Dimas Setiawan",
        category: "Research",
        year: "2025",
        href: "/publications/iot-remote-rehabilitation-monitoring-elderly",
    },
    {
        title: "IMU-Based Gait Analysis System for Rehabilitation Monitoring",
        author: "Siti Nurhaliza, Fajar Wicaksono, Ahmad Yani",
        category: "Research",
        year: "2025",
        href: "/publications/imu-gait-analysis-rehabilitation-monitoring",
    },
    {
        title: "Topology Optimization of Ankle–Foot Orthosis Using Generative Design",
        author: "Hendri Kusuma, Ayu Lestari",
        category: "Journals",
        year: "2024",
        href: "/publications/topology-optimization-ankle-foot-orthosis",
    },
];
