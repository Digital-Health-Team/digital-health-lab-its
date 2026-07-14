import { ArticleEntry } from "../Types/articlesSection.type";

/**
 * Static mirror of config/lab-news.php (user-chosen data strategy: hardcoded
 * + CMS overrides, no live DB query). Lead = the featured row (config index
 * 0); index = the next four, newest first. Overridable via the
 * `articles_featured` / `articles_entry_*` CMS keys. Hrefs point at the real
 * per-article page served by NewsController.
 */
export const featuredArticle: ArticleEntry = {
    title: "IDIG Gelar Workshop Cetak 3D Prostetik untuk Pelajar SMA",
    category: "Workshop",
    date: "12 Juni 2025",
    excerpt:
        "Puluhan siswa SMA se-Surabaya mencoba langsung proses desain hingga perakitan akhir prostetik cetak 3D, didampingi tim peneliti laboratorium.",
    href: "/news/workshop-cetak-3d-prostetik-pelajar-sma",
    image: "/assets/images/projects/clinical_3d_printing.png",
    imageAlt: "Peserta workshop mengamati proses pencetakan 3D prostetik di laboratorium IDIG HTech",
};

export const articleEntries: ArticleEntry[] = [
    {
        title: "Tim Lab Uji Coba Ortosis Cetak 3D di RSUD Dr. Soetomo",
        category: "Kunjungan",
        date: "5 Juni 2025",
        excerpt:
            "Kunjungan kerja sama dengan unit rehabilitasi medik menguji kecocokan ortosis kaki hasil cetak 3D pada pasien uji coba.",
        href: "/news/kunjungan-rsud-dr-soetomo-uji-ortosis",
        image: "/assets/images/projects/prosthetic_limb_3d.png",
        imageAlt: "Ortosis kaki hasil cetak 3D yang diuji coba bersama tim rehabilitasi medik",
    },
    {
        title: "Tim IDIG Raih Juara Kompetisi Inovasi Teknologi Kesehatan Nasional",
        category: "Prestasi",
        date: "28 Mei 2025",
        excerpt:
            "Purwarupa alat pemantauan pasien berbasis IoT besutan mahasiswa laboratorium meraih juara satu pada ajang inovasi tingkat nasional.",
        href: "/news/juara-kompetisi-inovasi-teknologi-kesehatan-2025",
        image: "/assets/images/projects/patient_monitoring_iot.png",
        imageAlt: "Purwarupa alat pemantauan pasien berbasis IoT yang meraih juara satu kompetisi nasional",
    },
    {
        title: "Printer Resin Baru Perkuat Fabrikasi Presisi Laboratorium",
        category: "Kabar Lab",
        date: "20 Mei 2025",
        excerpt:
            "Unit printer resin generasi terbaru resmi beroperasi, memperluas kapasitas laboratorium mencetak komponen medis berpresisi tinggi.",
        href: "/news/printer-resin-baru-fabrikasi-presisi",
        image: "/assets/images/projects/stl_medical_devices.png",
        imageAlt: "Unit printer resin baru yang digunakan untuk fabrikasi komponen medis presisi tinggi",
    },
    {
        title: "Kolaborasi Riset dengan FK Universitas Airlangga Dimulai",
        category: "Kolaborasi",
        date: "9 Mei 2025",
        excerpt:
            "Nota kesepahaman riset bersama Fakultas Kedokteran Universitas Airlangga membuka jalan pengembangan alat bantu medis berbasis kebutuhan klinis.",
        href: "/news/kolaborasi-riset-fk-universitas-airlangga",
        image: "/assets/images/projects/medtech_research_digest_v4.png",
        imageAlt: "Penandatanganan nota kesepahaman riset kolaboratif dengan FK Universitas Airlangga",
    },
];
