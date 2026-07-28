import { ContactInfo, QuickLink, SocialLink } from "../Types/landingFooter.type";

export const QUICK_LINKS: QuickLink[] = [
    { label: "Beranda", href: "/" },
    { label: "Tentang Kami", href: "/#about" },
    { label: "Riset", href: "/projects" },
    { label: "Produk & Layanan", href: "/services" },
    { label: "Publikasi", href: "/publications" },
    { label: "Struktur Organisasi", href: "/#org" },
    { label: "Hubungi Kami", href: "/#contact" },
];

export const CONTACT_INFO: ContactInfo = {
    address: [
        "Dept. of Medical Engineering Technology,",
        "ITS Campus, Surabaya, East Java 60111",
    ],
    phone: "+62 31 5994251",
    email: "idig@its.ac.id",
};

export const SOCIAL_LINKS: SocialLink[] = [
    { name: "YouTube", href: "#" },
    { name: "Instagram", href: "https://instagram.com/idig.htech" },
    { name: "Facebook", href: "#" },
    { name: "LinkedIn", href: "#" },
];
