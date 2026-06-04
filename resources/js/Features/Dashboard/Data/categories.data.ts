import {
    Box,
    HandMetal,
    Bandage,
    GraduationCap,
    FileText,
    BookOpen,
    FolderOpen,
    Wrench,
    Award,
    CalendarHeart,
} from "lucide-react";
import { type Category } from "../Types/category.type";

export const categories: Category[] = [
    { id: "3d-designs", label: "3D Designs", href: "/shop?cat=3d-designs", icon: Box, accent: "primary" },
    { id: "prosthetics", label: "Prosthetics", href: "/shop?cat=prosthetics", icon: HandMetal, accent: "secondary" },
    { id: "aid-bands", label: "Aid Bands", href: "/shop?cat=aid-bands", icon: Bandage },
    { id: "educational", label: "Educational Mannequin", href: "/shop?cat=educational", icon: GraduationCap },
    { id: "papers", label: "Papers", href: "/publications?type=paper", icon: FileText },
    { id: "journals", label: "Journals", href: "/publications?type=journal", icon: BookOpen },
    { id: "projects", label: "Projects", href: "/projects", icon: FolderOpen },
    { id: "services", label: "Services", href: "/services", icon: Wrench },
    { id: "training", label: "Training", href: "/training", icon: Award },
    { id: "events", label: "Event", href: "/events", icon: CalendarHeart },
];
