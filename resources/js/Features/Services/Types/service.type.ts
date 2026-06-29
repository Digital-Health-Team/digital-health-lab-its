import type { LucideIcon } from "lucide-react";

export interface ServicesHeroStat {
    value: string;
    label: string;
}

export interface ServicesHero {
    eyebrow: string;
    title: string;
    subtitle: string;
    stats: ServicesHeroStat[];
}

export interface ServiceOffering {
    id: string;
    title: string;
    description: string;
    icon: LucideIcon;
    iconPath: string;
    ctaLabel: string;
    variant: "primary" | "outline";
    imageGradient: string;
    href: string;
}

export type ServiceOrderStatus = "in_progress" | "completed" | "pending" | "cancelled";

export interface ServiceOrder {
    id: string;
    orderCode: string;
    title: string;
    service: string;
    date: string;
    price: string;
    status: ServiceOrderStatus;
    avatarColor: string;
}
