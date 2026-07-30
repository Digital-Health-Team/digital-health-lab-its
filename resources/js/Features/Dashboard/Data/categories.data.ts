import { type Category } from "../Types/category.type";

/**
 * The seven core competencies, mirroring `capabilities` in
 * Landing/Data/aboutSection.data.ts — same labels, same imagery, so the dashboard
 * catalogue and the public landing page name the lab's work identically.
 *
 * Labels are English t() keys and are already translated in lang/id.json as part
 * of the about section, so aligning here needed no new translation entries.
 */
export const categories: Category[] = [
    // ponytail: ?cat= names a real productCatalogue.data.ts category id, but
    // ProductsPage doesn't read it yet — wire the filter there, not here.
    {
        id: "medical-simulation",
        label: "Medical Simulation",
        href: "/products?cat=anatomical-models",
        icon: "/assets/images/categories/educational.png",
    },
    {
        id: "surgical-technology",
        label: "Surgical Technology",
        href: "/products?cat=custom-prints",
        icon: "/assets/images/categories/prosthetics.png",
    },
    {
        id: "rehabilitation-technology",
        label: "Rehabilitation Technology",
        href: "/products?cat=prosthetics",
        icon: "/assets/images/categories/aid_bands.png",
    },
    {
        id: "medical-instrumentation",
        label: "Medical Instrumentation",
        href: "/products?cat=lab-equipment",
        icon: "/assets/images/projects/wearable_sensor_housing.png",
    },
    {
        id: "innovation-services",
        label: "Innovation Services",
        href: "/services",
        icon: "/assets/images/categories/3d_designs.png",
    },
    {
        id: "research-collaboration",
        label: "Research & Collaboration",
        href: "/research",
        icon: "/assets/images/categories/projects.png",
    },
    {
        id: "education-training",
        label: "Education & Training",
        href: "/events",
        icon: "/assets/images/categories/events.png",
    },
];
