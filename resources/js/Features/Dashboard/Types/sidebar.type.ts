import { type LucideIcon } from "lucide-react";

export interface NavItem {
    id: string;
    label: string;
    href: string;
    icon: LucideIcon;
    match?: string;
}
