import { type ComponentType, type SVGProps } from "react";

export interface Category {
    id: string;
    label: string;
    href: string;
    icon: ComponentType<SVGProps<SVGSVGElement>>;
    accent?: "primary" | "secondary" | "accent";
}

