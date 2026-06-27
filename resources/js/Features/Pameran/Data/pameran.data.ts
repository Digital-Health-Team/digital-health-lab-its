export const pameranData = {
    meta: {
        title: "Hitung Mundur InnovaTech 2026 — IDIG Laboratory",
        description:
            "Hitung mundur menuju Pameran InnovaTech 2026 — inovasi teknologi medis mahasiswa ITS, 2 Juli 2026.",
    },
    hero: {
        preTitle: "Institut Teknologi Sepuluh Nopember",
        title: "InnovaTech 2026",
        subtitle: "Pameran Inovasi Teknologi Medis",
        description:
            "Temukan karya inovatif mahasiswa dalam bidang teknologi medis dan rekayasa biomedis — solusi nyata untuk tantangan kesehatan masa depan.",
        ctaText: "Jelajahi Karya",
        ctaHref: "#pameran-intro",
        date: "IDIG Medical Technology Laboratory · ITS · 2026",
    },

    /**
     * Event date/time for the countdown timer.
     * Target: 2 July 2026, 09:00 WIB (Asia/Jakarta, UTC+7).
     * The ISO string carries the +07:00 offset so the countdown is
     * timezone-correct for all viewers regardless of their local clock.
     */
    event: {
        dateISO: "2026-07-02T09:00:00+07:00",
        dateLabel: "2 Juli 2026",
        timeLabel: "09.00 WIB",
    },

    /** Copy strings for the countdown page and its live state. */
    countdown: {
        units: {
            days: "Hari",
            hours: "Jam",
            minutes: "Menit",
            seconds: "Detik",
        },
        live: {
            badge: "Pameran Telah Dibuka",
            ctaText: "Jelajahi Karya",
        },
        backLabel: "Kembali ke Beranda",
    },

    /**
     * Cinematic spotlight reel — photos shown one at a time during the preloader.
     * PLACEHOLDER: swap every entry for real InnovaTech 2026 photos + captions before launch.
     * Local images (first 4) are used for the spotlight; keep the full list for future use.
     * Mix: local hero photos (always available offline) + Unsplash IDs validated in this repo.
     */
    reelImages: [
        // ── Local (spotlight uses these first — always offline-safe) ──
        {
            src: "/assets/images/hero.png",
            alt: "IDIG laboratory space",
            title: "Laboratorium Teknologi Medis",
        },
        {
            src: "/assets/images/hero_2.jpg",
            alt: "Medical technology prototype",
            title: "Prototipe Perangkat Biomedis",
        },
        {
            src: "/assets/images/hero_3.jpg",
            alt: "ITS research laboratory",
            title: "Penelitian Rekayasa Instrumentasi",
        },
        {
            src: "/assets/images/hero_4.jpg",
            alt: "Engineering innovation",
            title: "Inovasi Teknologi Terapan",
        },
        // ── Unsplash — reuse photo IDs already valid in this repo (lab/medical/3D-print/research) ──
        {
            src: "https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?auto=format&fit=crop&q=80&w=600&h=400",
            alt: "3D printing precision fabrication",
            title: "Fabrikasi Presisi 3D Printing",
        },
        {
            src: "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&q=80&w=600&h=400",
            alt: "Engineer at workstation",
            title: "Rekayasa Perangkat Medis",
        },
        {
            src: "https://images.unsplash.com/photo-1481627834876-b7833e8f5570?auto=format&fit=crop&q=80&w=600&h=400",
            alt: "Research documentation and publication",
            title: "Dokumentasi Riset Ilmiah",
        },
        {
            src: "https://images.unsplash.com/photo-1576086213369-97a306d36557?auto=format&fit=crop&q=80&w=600&h=400",
            alt: "Medical laboratory work",
            title: "Laboratorium Analisis Klinis",
        },
        {
            src: "https://images.unsplash.com/photo-1559757148-5c350d0d3c56?auto=format&fit=crop&q=80&w=600&h=400",
            alt: "Biomedical research",
            title: "Sistem Diagnostik Biomedis",
        },
        {
            src: "https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&q=80&w=600&h=400",
            alt: "Laboratory equipment and instruments",
            title: "Instrumentasi Laboratorium",
        },
        {
            src: "https://images.unsplash.com/photo-1530497610245-94d3c16cda28?auto=format&fit=crop&q=80&w=600&h=400",
            alt: "Innovation workshop",
            title: "Workshop Inovasi Terpadu",
        },
        {
            src: "https://images.unsplash.com/photo-1584982751601-97dcc096659c?auto=format&fit=crop&q=80&w=600&h=400",
            alt: "Medical device development",
            title: "Pengembangan Alat Kesehatan",
        },
        {
            src: "https://images.unsplash.com/photo-1518152006812-edab29b069ac?auto=format&fit=crop&q=80&w=600&h=400",
            alt: "Engineering design process",
            title: "Proses Desain Rekayasa",
        },
    ],
} as const;
