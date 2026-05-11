const IMG_BASE = "/assets/images/services";

export const SERVICES = [
    {
        title: "Produk & Layanan",
        body: "Jelajahi desain 3D, purwarupa medis, dan fabrikasi khusus sesuai kebutuhan Anda.",
        image: `${IMG_BASE}/Products%20%26%20Services%20-%20Hand%20PNG%20-%20Landing%20Page.png`,
        alt: "3D printed prosthetic hand prototype",
        gradient:
            "bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900",
        align: "left",
        tilt: -1.5,
    },
    {
        title: "Riset & Inovasi",
        body: "Temukan informasi dari koleksi jurnal, publikasi, dan penelitian terbaru kami.",
        image: `${IMG_BASE}/Research%20Card%20Journal%20PNG%20-%20Landing%20Page.png`,
        alt: "Open biomedical engineering journal pages",
        gradient: "bg-gradient-to-br from-teal-600 via-teal-800 to-slate-900",
        align: "right",
        tilt: 1.5,
    },
    {
        title: "Agenda & Acara",
        body: "Ikuti perkembangan terbaru mengenai acara, webinar, dan berita dari komunitas kami.",
        image: `${IMG_BASE}/Events%20-%20Booth%20PNG%20-%20Landing%20Page.png`,
        alt: "IDIG conference booth with branded displays",
        gradient: "bg-gradient-to-br from-rose-700 via-rose-900 to-fuchsia-950",
        align: "left",
        tilt: -1.5,
    },
];
