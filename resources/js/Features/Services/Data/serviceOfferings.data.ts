import { Crosshair, Printer, ScanLine } from "lucide-react";
import { type ServiceOffering } from "../Types/service.type";

export const serviceOfferingsData: ServiceOffering[] = [
    {
        id: "design",
        title: "3D Design & Modeling",
        description:
            "Turn your 2D ideas into detailed 3D models. Upload your design and we can help turn your creation into reality.",
        icon: Crosshair,
        iconPath: "/assets/images/services/design_icon.svg",
        ctaLabel: "Create Designs",
        variant: "primary",
        href: "/services/design",
        imageGradient:
            "radial-gradient(ellipse at 25% 60%, rgba(0,130,150,0.75) 0%, rgba(10,61,122,0.92) 50%, #0d1b3e 100%)",
    },
    {
        id: "printing",
        title: "3D Printing & Prototyping",
        description:
            "High quality 3D printing & prototyping to turn your designs to real life. Fast turnaround with precision results.",
        icon: Printer,
        iconPath: "/assets/images/services/printing_icon.svg",
        ctaLabel: "Get Prints",
        variant: "outline",
        href: "/services/printing",
        imageGradient:
            "radial-gradient(ellipse at 50% 40%, rgba(0,110,130,0.8) 0%, rgba(10,61,122,0.9) 55%, #0a1e3e 100%)",
    },
    {
        id: "scanning",
        title: "3D Scanning",
        description:
            "Digitize physical objects with high-resolution 3D scanning. Ideal for reverse engineering, archival, and analysis.",
        icon: ScanLine,
        iconPath: "/assets/images/services/scanning_icon.svg",
        ctaLabel: "Start Scanning",
        variant: "primary",
        href: "/services/scanning",
        imageGradient:
            "radial-gradient(ellipse at 82% 45%, rgba(0,168,181,0.65) 0%, rgba(0,110,130,0.7) 30%, rgba(10,61,122,0.9) 62%, #0a1535 100%)",
    },
];
