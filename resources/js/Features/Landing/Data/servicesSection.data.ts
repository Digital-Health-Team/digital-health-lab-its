import { Service } from "../Types/servicesSection.type";

const IMG_BASE = "/assets/images/services";

export const services: Service[] = [
    {
        title: "Products & Services",
        body: "Explore 3D design, medical prototypes, and bespoke fabrication built around your needs.",
        image: `${IMG_BASE}/Products%20%26%20Services%20-%20Hand%20PNG%20-%20Landing%20Page.png`,
        alt: "3D printed prosthetic hand prototype",
        gradient: "bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900",
        align: "left",
        tilt: -1.5,
        href: "/services",
    },
    {
        title: "Research & Innovation",
        body: "Browse our collection of journals, publications, and the latest research.",
        image: `${IMG_BASE}/Research%20Card%20Journal%20PNG%20-%20Landing%20Page.png`,
        alt: "Open biomedical engineering journal pages",
        gradient: "bg-gradient-to-br from-teal-600 via-teal-800 to-slate-900",
        align: "right",
        tilt: 1.5,
        href: "/publications",
    },
    {
        title: "Agenda & Events",
        body: "Keep up with events, webinars, and news from our community.",
        image: `${IMG_BASE}/Events%20-%20Booth%20PNG%20-%20Landing%20Page.png`,
        alt: "IDIG conference booth with branded displays",
        gradient: "bg-gradient-to-br from-rose-700 via-rose-900 to-fuchsia-950",
        align: "left",
        tilt: -1.5,
        href: "/news",
    },
];
