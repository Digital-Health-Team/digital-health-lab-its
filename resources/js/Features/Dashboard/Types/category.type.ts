import { type LucideIcon } from "lucide-react";

export interface Category {
    id: string;
    label: string;
    href: string;
    icon: LucideIcon;
    accent?: "primary" | "secondary" | "accent";
}
