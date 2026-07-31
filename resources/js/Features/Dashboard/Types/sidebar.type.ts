import { type LucideIcon } from "lucide-react";

export interface NavItem {
    id: string;
    label: string;
    href: string;
    icon: LucideIcon;
    /** Prefix(es) that mark this item active; defaults to `href`. Pass an array when one item owns several URLs. */
    match?: string | string[];
    authRequired?: boolean;
}
