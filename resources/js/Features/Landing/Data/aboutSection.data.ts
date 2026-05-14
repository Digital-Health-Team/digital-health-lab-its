import { Capability, AboutHeadlineWord } from "../Types/aboutSection.type";

export const capabilities: Capability[] = [
    {
        tag: "3D Innovation",
        title: "Cetak Tiga Dimensi Presisi Tinggi",
        description:
            "Perancangan dan fabrikasi implan, prostetik, serta model anatomi menggunakan teknologi additive manufacturing dengan material biokompatibel.",
        accent: "#00A8B5",
        image: "https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=400&h=400&fit=crop&crop=center",
        imageAlt: "3D printer fabricating a precision object",
    },
    {
        tag: "Custom Order",
        title: "Layanan Desain & Produksi Kustom",
        description:
            "Layanan berbasis pesanan untuk rumah sakit, klinik, dan institusi pendidikan. Dari konsep digital hingga produk fisik siap pakai.",
        accent: "#FFC72C",
        image: "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=400&h=400&fit=crop&crop=center",
        imageAlt: "Engineer working on a custom product design",
    },
    {
        tag: "Digital Repository",
        title: "Repositori Publikasi Terpusat",
        description:
            "Sentralisasi jurnal, laporan riset, dan dokumentasi teknis dalam satu platform terbuka yang mendukung akses dan kolaborasi lintas disiplin.",
        accent: "#22D3EE",
        image: "https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=400&fit=crop&crop=center",
        imageAlt: "Research library with open books and journals",
    },
];

export const headlineWords: AboutHeadlineWord[] = [
    { word: "Menjembatani", accent: false, lineBreakAfter: false },
    { word: "Inovasi", accent: false, lineBreakAfter: true },
    { word: "Kesehatan", accent: false, lineBreakAfter: false },
    { word: "dan", accent: false, lineBreakAfter: false },
    { word: "Rekayasa.", accent: true, lineBreakAfter: false },
];
