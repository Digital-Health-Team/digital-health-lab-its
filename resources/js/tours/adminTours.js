import { driver } from "driver.js";

/**
 * Role-based guided tours for the Livewire admin dashboard.
 *
 * Each step carries bilingual copy ({ id, en }); the active locale is resolved
 * at runtime from `window.__TOUR_CONTEXT__.locale`. Sidebar items are targeted
 * by href-suffix selectors so no per-<x-menu-item> markup change is needed;
 * navbar utilities are targeted via `data-tour` wrappers in the layout.
 *
 * Missing-for-role elements are filtered out before the tour runs, so a shared
 * step can be listed safely even if a given role doesn't have that menu item.
 */

const DASHBOARD_PATH = {
    super_admin: "/super-admin/dashboard",
    admin_lab: "/admin/dashboard",
    admin_gudang: "/gudang/dashboard",
};

// Reusable step fragments -------------------------------------------------

const welcomeStep = (roleLabelId, roleLabelEn) => ({
    element: null,
    id: {
        title: "Selamat datang! 👋",
        description: `Tur singkat ini mengenalkan fitur utama dashboard ${roleLabelId}. Anda bisa mengulanginya kapan saja lewat tombol tutorial di navbar.`,
    },
    en: {
        title: "Welcome! 👋",
        description: `This quick tour introduces the main features of the ${roleLabelEn} dashboard. You can replay it anytime from the tutorial button in the navbar.`,
    },
});

const tourButtonStep = {
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

const searchStep = {
    element: '[data-tour="admin-search"]',
    side: "bottom",
    align: "start",
    id: {
        title: "Pencarian Global",
        description: "Cari pengguna, pesanan, produk, dan data lain dari satu tempat.",
    },
    en: {
        title: "Global Search",
        description: "Find users, orders, products and more from a single place.",
    },
};

const notificationsStep = {
    element: '[data-tour="admin-notifications"]',
    side: "bottom",
    align: "end",
    id: {
        title: "Notifikasi",
        description: "Pemberitahuan terbaru tentang pesanan, laporan, dan aktivitas lab muncul di sini.",
    },
    en: {
        title: "Notifications",
        description: "The latest alerts about orders, reports and lab activity appear here.",
    },
};

const profileStep = {
    element: '[data-tour="admin-profile"]',
    side: "bottom",
    align: "end",
    id: {
        title: "Profil & Pengaturan",
        description: "Buka pengaturan akun, ganti mode, atau keluar dari sini.",
    },
    en: {
        title: "Profile & Settings",
        description: "Open account settings, switch mode, or sign out from here.",
    },
};

// Sidebar link step factory (targets by href suffix).
// The dashboard route also appears on a mobile-only (`lg:hidden`) app-name link
// in the navbar, which DaisyUI's drawer renders *before* the sidebar — so we
// exclude it to make sure the visible sidebar link is spotlighted on desktop.
const nav = (path, id, en, side = "right") => ({
    element: `a[href$="${path}"]:not([class~="lg:hidden"])`,
    side,
    align: "center",
    id,
    en,
});

// Per-role tours ----------------------------------------------------------

const TOURS = {
    super_admin: [
        welcomeStep("Super Admin", "Super Admin"),
        tourButtonStep,
        searchStep,
        nav("/super-admin/dashboard",
            { title: "Dashboard", description: "Ringkasan sistem: statistik, aktivitas terbaru, dan pintasan penting." },
            { title: "Dashboard", description: "System overview: key stats, recent activity and important shortcuts." }),
        nav("/admin/order-center",
            { title: "Pusat Pesanan", description: "Kelola seluruh pesanan layanan 3D printing dari pelanggan." },
            { title: "Order Center", description: "Manage every 3D-printing service order from customers." }),
        nav("/admin/services",
            { title: "Layanan Lab", description: "Atur katalog layanan yang ditawarkan lab." },
            { title: "Lab Services", description: "Configure the catalogue of services the lab offers." }),
        nav("/admin/inventory",
            { title: "Inventaris", description: "Kelola bahan baku, stok, dan data master gudang." },
            { title: "Inventory", description: "Manage raw materials, stock and warehouse master data." }),
        nav("/admin/tools",
            { title: "Alat", description: "Katalog alat dan peralatan fisik di seluruh lab." },
            { title: "Tools", description: "Catalogue of physical tools and equipment across all labs." }),
        nav("/admin/products",
            { title: "Produk", description: "Kelola produk jadi yang dipublikasikan ke katalog." },
            { title: "Products", description: "Manage finished products published to the catalogue." }),
        nav("/admin/events",
            { title: "Acara & Pameran", description: "Buat dan kelola acara serta pameran inovasi." },
            { title: "Events & Exhibitions", description: "Create and manage innovation events and exhibitions." }),
        nav("/admin/open-source-projects",
            { title: "Proyek Open Source", description: "Validasi dan kelola karya mahasiswa yang dipublikasikan." },
            { title: "Open Source Projects", description: "Validate and manage published student works." }),
        nav("/admin/publications",
            { title: "Publikasi", description: "Kelola publikasi dan riset yang tampil di situs." },
            { title: "Publications", description: "Manage publications and research shown on the site." }),
        nav("/admin/trainings",
            { title: "Pelatihan", description: "Atur workshop dan pelatihan beserta pendaftarannya." },
            { title: "Training Workshops", description: "Manage workshops, trainings and their registrations." }),
        nav("/admin/reports",
            { title: "Laporan Masalah", description: "Pantau dan tanggapi laporan kendala dari tim." },
            { title: "Issue Reports", description: "Track and respond to issue reports from the team." }),
        nav("/admin/users",
            { title: "Manajemen Pengguna", description: "Kelola akun, peran, dan hak akses pengguna." },
            { title: "User Management", description: "Manage accounts, roles and user permissions." }),
        notificationsStep,
        profileStep,
    ],

    admin_lab: [
        welcomeStep("Admin Lab", "Lab Admin"),
        tourButtonStep,
        searchStep,
        nav("/admin/dashboard",
            { title: "Dashboard", description: "Ringkasan operasional lab dan aktivitas terbaru." },
            { title: "Dashboard", description: "Your lab's operational overview and recent activity." }),
        nav("/admin/order-center",
            { title: "Pusat Pesanan", description: "Tinjau pesanan, perbarui progres, dan chat dengan pelanggan." },
            { title: "Order Center", description: "Review orders, update progress and chat with customers." }),
        nav("/admin/reports",
            { title: "Laporan Masalah", description: "Buat dan pantau laporan kendala operasional." },
            { title: "Issue Reports", description: "Create and track operational issue reports." }),
        nav("/admin/services",
            { title: "Layanan Lab", description: "Atur katalog layanan yang ditawarkan lab." },
            { title: "Lab Services", description: "Configure the catalogue of services the lab offers." }),
        nav("/admin/products",
            { title: "Produk", description: "Kelola produk jadi yang dipublikasikan ke katalog." },
            { title: "Products", description: "Manage finished products published to the catalogue." }),
        nav("/admin/events",
            { title: "Acara & Pameran", description: "Buat dan kelola acara serta pameran inovasi." },
            { title: "Events & Exhibitions", description: "Create and manage innovation events and exhibitions." }),
        nav("/admin/open-source-projects",
            { title: "Proyek Open Source", description: "Validasi dan kelola karya mahasiswa yang dipublikasikan." },
            { title: "Open Source Projects", description: "Validate and manage published student works." }),
        nav("/admin/publications",
            { title: "Publikasi", description: "Kelola publikasi dan riset yang tampil di situs." },
            { title: "Publications", description: "Manage publications and research shown on the site." }),
        nav("/admin/trainings",
            { title: "Pelatihan", description: "Atur workshop dan pelatihan beserta pendaftarannya." },
            { title: "Training Workshops", description: "Manage workshops, trainings and their registrations." }),
        notificationsStep,
        profileStep,
    ],

    admin_gudang: [
        welcomeStep("Admin Gudang", "Warehouse Admin"),
        tourButtonStep,
        searchStep,
        nav("/gudang/dashboard",
            { title: "Dashboard", description: "Ringkasan gudang: stok menipis dan pesanan yang menunggu bahan." },
            { title: "Dashboard", description: "Warehouse overview: low stock and orders awaiting materials." }),
        nav("/gudang/orders",
            { title: "Pesanan Masuk", description: "Verifikasi ketersediaan bahan sebelum produksi dimulai." },
            { title: "Incoming Orders", description: "Verify material availability before production starts." }),
        nav("/admin/reports",
            { title: "Laporan Masalah", description: "Tangani dan selesaikan laporan kendala gudang." },
            { title: "Issue Reports", description: "Handle and resolve warehouse issue reports." }),
        nav("/admin/inventory",
            { title: "Inventaris", description: "Kelola bahan baku, stok per lokasi, dan restock." },
            { title: "Inventory", description: "Manage raw materials, per-location stock and restocking." }),
        nav("/admin/tools",
            { title: "Alat", description: "Katalog alat dan peralatan fisik di seluruh lab." },
            { title: "Tools", description: "Catalogue of physical tools and equipment across all labs." }),
        notificationsStep,
        profileStep,
    ],
};

function resolveLocale(lang) {
    return lang === "en" ? "en" : "id";
}

function isVisible(selector) {
    const el = document.querySelector(selector);
    return el && el.getClientRects().length > 0;
}

function buildSteps(role, locale) {
    const raw = TOURS[role] || [];
    return raw
        .filter((s) => !s.element || isVisible(s.element))
        .map((s) => {
            const copy = s[locale] || s.id;
            const popover = {
                title: copy.title,
                description: copy.description,
                side: s.side || "right",
                align: s.align || "start",
            };
            return s.element ? { element: s.element, popover } : { popover };
        });
}

export function startAdminTour(role, lang) {
    const locale = resolveLocale(lang);
    const steps = buildSteps(role, locale);
    if (!steps.length) return;

    driver({
        showProgress: true,
        allowClose: true,
        overlayColor: "rgba(2, 6, 23, 0.6)",
        popoverClass: "idig-tour",
        stagePadding: 6,
        stageRadius: 10,
        nextBtnText: locale === "en" ? "Next" : "Lanjut",
        prevBtnText: locale === "en" ? "Back" : "Kembali",
        doneBtnText: locale === "en" ? "Done" : "Selesai",
        progressText: locale === "en" ? "{{current}} of {{total}}" : "{{current}} dari {{total}}",
        steps,
    }).drive();
}

export function maybeAutoStart(role, lang) {
    const path = DASHBOARD_PATH[role];
    if (!path) return;
    if (!window.location.pathname.startsWith(path)) return;

    const key = `idig-tour-seen:${role}`;
    if (localStorage.getItem(key)) return;
    localStorage.setItem(key, "1");

    // Give Livewire/Alpine a beat to settle the morphed DOM before spotlighting.
    setTimeout(() => startAdminTour(role, lang), 600);
}
