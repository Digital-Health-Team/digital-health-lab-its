export const translations = {
    // ── Sidebar brand ────────────────────────────────────
    "Medical Technology": { en: "Medical Technology", id: "Teknologi Kedokteran" },

    // ── Sidebar collapse toggle ───────────────────────────
    "Collapse":         { en: "Collapse",         id: "Tutup" },
    "Expand sidebar":   { en: "Expand sidebar",   id: "Perluas sidebar" },
    "Collapse sidebar": { en: "Collapse sidebar", id: "Tutup sidebar" },

    // ── Sidebar nav items ─────────────────────────────────
    "Home":        { en: "Home",        id: "Beranda" },
    "Publications": { en: "Publications", id: "Publikasi" },
    "Projects":    { en: "Projects",    id: "Proyek" },
    "Services":    { en: "Services",    id: "Layanan" },
    "Management":  { en: "Management",  id: "Manajemen" },
    "Shop":        { en: "Shop",        id: "Toko" },
    "Profile":     { en: "Profile",     id: "Profil" },

    // ── Topbar search ─────────────────────────────────────
    "Search publications, products, services...": {
        en: "Search publications, products, services...",
        id: "Cari publikasi, produk, layanan...",
    },
    "Search": { en: "Search", id: "Cari" },

    // ── User menu — authenticated ─────────────────────────
    "My Profile": { en: "My Profile", id: "Profil Saya" },
    "My Orders":  { en: "My Orders",  id: "Pesanan Saya" },
    "My Uploads": { en: "My Uploads", id: "Unggahan Saya" },
    "Settings":   { en: "Settings",   id: "Pengaturan" },
    "Sign out":   { en: "Sign out",   id: "Keluar" },

    // ── User menu — guest ─────────────────────────────────
    "Login":    { en: "Login",    id: "Masuk" },
    "Register": { en: "Register", id: "Daftar" },

    // ── NewProductsCard ───────────────────────────────────
    "New Products": { en: "New Products", id: "Produk Baru" },
    "PRODUCTS":     { en: "PRODUCTS",     id: "PRODUK" },
    "SERVICES":     { en: "SERVICES",     id: "LAYANAN" },

    // ── Dashboard sections ────────────────────────────────
    "Explore Our Projects": { en: "Explore Our Projects", id: "Jelajahi Proyek Kami" },

    "Trending Articles": { en: "Trending Articles", id: "Artikel Trending" },
    "Articles with recent increases in activity": {
        en: "Articles with recent increases in activity",
        id: "Artikel dengan peningkatan aktivitas terkini",
    },
    "See more trending articles": {
        en: "See more trending articles",
        id: "Lihat lebih banyak artikel",
    },

    "PubMed Updates": { en: "PubMed Updates", id: "Pembaruan PubMed" },
    "Feature updates and other PubMed highlights": {
        en: "Feature updates and other PubMed highlights",
        id: "Pembaruan fitur dan sorotan PubMed lainnya",
    },

    "See more!": { en: "See more!", id: "Lihat lebih banyak!" },

    // ── Empty state — Events ──────────────────────────────
    "No Ongoing Events": { en: "No Ongoing Events", id: "Tidak Ada Event Berlangsung" },
    "Check back later for upcoming Innovatech events from the IDIG laboratory.": {
        en: "Check back later for upcoming Innovatech events from the IDIG laboratory.",
        id: "Pantau terus untuk event Innovatech mendatang dari laboratorium IDIG.",
    },

    // ── OngoingEventCard ──────────────────────────────────
    "Ongoing Event":       { en: "Ongoing Event",       id: "Event Berlangsung" },
    "Theme":               { en: "Theme",               id: "Tema" },
    "participating teams": { en: "participating teams", id: "tim peserta" },
} as const;

export type TranslationKey = keyof typeof translations;
