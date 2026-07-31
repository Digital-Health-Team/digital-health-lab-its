/**
 * Bilingual guided-tour step definitions for the React/Inertia dashboard.
 *
 * Two authenticated roles land here: `mahasiswa` (students) and `user_publik`
 * (public users). Both share the same dashboard shell, so the step targets are
 * identical; only the copy is tuned per role. Targets are `[data-tour="..."]`
 * selectors added to the sidebar nav items and topbar fragments.
 */

export type TourLang = "en" | "id";

interface StepCopy {
    title: string;
    description: string;
}

export interface TourStep {
    /** CSS selector, or null for a centered welcome step. */
    element: string | null;
    side?: "top" | "bottom" | "left" | "right";
    align?: "start" | "center" | "end";
    id: StepCopy;
    en: StepCopy;
}

const tourButton: TourStep = {
    element: '[data-tour="tour-button"]',
    side: "bottom",
    align: "end",
    id: {
        title: "Ulangi Tur Kapan Saja",
        description: "Klik tombol ini di navbar untuk memutar ulang tutorial ini.",
    },
    en: {
        title: "Replay Anytime",
        description: "Click this button in the navbar to replay this tutorial whenever you like.",
    },
};

const search: TourStep = {
    element: '[data-tour="user-search"]',
    side: "bottom",
    align: "center",
    id: {
        title: "Pencarian",
        description: "Cari publikasi, produk, dan layanan dengan cepat dari sini.",
    },
    en: {
        title: "Search",
        description: "Quickly find publications, products and services from here.",
    },
};

const notifications: TourStep = {
    element: '[data-tour="user-notifications"]',
    side: "bottom",
    align: "end",
    id: {
        title: "Notifikasi",
        description: "Pembaruan tentang pesanan dan aktivitas Anda muncul di sini.",
    },
    en: {
        title: "Notifications",
        description: "Updates about your orders and activity appear here.",
    },
};

const userMenu: TourStep = {
    element: '[data-tour="user-menu"]',
    side: "bottom",
    align: "end",
    id: {
        title: "Menu Akun",
        description: "Buka profil, pesanan, unggahan, ganti mode, atau keluar dari sini.",
    },
    en: {
        title: "Account Menu",
        description: "Open your profile, orders, uploads, switch mode or sign out from here.",
    },
};

const navHome = (copy: { id: StepCopy; en: StepCopy }): TourStep => ({
    element: '[data-tour="nav-home"]',
    side: "right",
    align: "center",
    ...copy,
});

export const userTours: Record<string, TourStep[]> = {
    mahasiswa: [
        {
            element: null,
            id: {
                title: "Selamat datang! 👋",
                description:
                    "Tur singkat ini mengenalkan dashboard mahasiswa. Anda bisa mengulanginya kapan saja lewat tombol tutorial di navbar.",
            },
            en: {
                title: "Welcome! 👋",
                description:
                    "This quick tour introduces your student dashboard. You can replay it anytime from the tutorial button in the navbar.",
            },
        },
        tourButton,
        search,
        navHome({
            id: { title: "Beranda", description: "Ringkasan aktivitas, pesanan, dan pintasan penting Anda." },
            en: { title: "Home", description: "An overview of your activity, orders and key shortcuts." },
        }),
        {
            element: '[data-tour="nav-training"]',
            side: "right",
            id: { title: "Pelatihan", description: "Jelajahi dan daftar workshop serta pelatihan yang tersedia." },
            en: { title: "Training", description: "Browse and register for available workshops and trainings." },
        },
        {
            element: '[data-tour="nav-projects"]',
            side: "right",
            id: { title: "Proyek", description: "Kirim dan kelola karya open-source Anda di sini." },
            en: { title: "Projects", description: "Submit and manage your open-source works here." },
        },
        {
            element: '[data-tour="nav-publications"]',
            side: "right",
            id: { title: "Publikasi", description: "Baca publikasi dan riset dari lab." },
            en: { title: "Publications", description: "Read publications and research from the lab." },
        },
        {
            element: '[data-tour="nav-services"]',
            side: "right",
            id: { title: "Layanan", description: "Pesan layanan 3D printing dan negosiasikan harga." },
            en: { title: "Services", description: "Book 3D-printing services and negotiate pricing." },
        },
        {
            element: '[data-tour="nav-products"]',
            side: "right",
            id: { title: "Produk", description: "Telusuri katalog produk buatan lab." },
            en: { title: "Products", description: "Browse the catalogue of lab-made products." },
        },
        {
            element: '[data-tour="nav-portfolio"]',
            side: "right",
            id: { title: "Portofolio Saya", description: "Semua karya, pesanan, dan pelatihan Anda dalam satu tempat." },
            en: { title: "My Portfolio", description: "All your works, orders and trainings in one place." },
        },
        {
            element: '[data-tour="nav-profile"]',
            side: "right",
            id: { title: "Profil", description: "Perbarui data diri dan pengaturan akun Anda." },
            en: { title: "Profile", description: "Update your personal details and account settings." },
        },
        notifications,
        userMenu,
    ],

    user_publik: [
        {
            element: null,
            id: {
                title: "Selamat datang! 👋",
                description:
                    "Tur singkat ini mengenalkan dashboard Anda. Anda bisa mengulanginya kapan saja lewat tombol tutorial di navbar.",
            },
            en: {
                title: "Welcome! 👋",
                description:
                    "This quick tour introduces your dashboard. You can replay it anytime from the tutorial button in the navbar.",
            },
        },
        tourButton,
        search,
        navHome({
            id: { title: "Beranda", description: "Ringkasan aktivitas dan pesanan Anda." },
            en: { title: "Home", description: "An overview of your activity and orders." },
        }),
        {
            element: '[data-tour="nav-services"]',
            side: "right",
            id: { title: "Layanan", description: "Pesan layanan 3D printing dan negosiasikan harga langsung." },
            en: { title: "Services", description: "Book 3D-printing services and negotiate pricing directly." },
        },
        {
            element: '[data-tour="nav-products"]',
            side: "right",
            id: { title: "Produk", description: "Telusuri dan pesan produk buatan lab." },
            en: { title: "Products", description: "Browse and order lab-made products." },
        },
        {
            element: '[data-tour="nav-publications"]',
            side: "right",
            id: { title: "Publikasi", description: "Baca publikasi dan riset dari lab." },
            en: { title: "Publications", description: "Read publications and research from the lab." },
        },
        {
            element: '[data-tour="nav-training"]',
            side: "right",
            id: { title: "Pelatihan", description: "Jelajahi dan daftar workshop yang tersedia untuk umum." },
            en: { title: "Training", description: "Browse and register for workshops open to the public." },
        },
        {
            element: '[data-tour="nav-portfolio"]',
            side: "right",
            id: { title: "Portofolio Saya", description: "Semua pesanan dan aktivitas Anda dalam satu tempat." },
            en: { title: "My Portfolio", description: "All your orders and activity in one place." },
        },
        {
            element: '[data-tour="nav-profile"]',
            side: "right",
            id: { title: "Profil", description: "Perbarui data diri dan pengaturan akun Anda." },
            en: { title: "Profile", description: "Update your personal details and account settings." },
        },
        notifications,
        userMenu,
    ],
};
