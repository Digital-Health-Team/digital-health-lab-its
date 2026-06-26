import { type ServiceOrder } from "../Types/service.type";

export const serviceOrdersData: ServiceOrder[] = [
    {
        id: "1",
        orderCode: "#SVC-2026-001",
        title: "Prosthetic Hand Model",
        service: "3D Printing",
        date: "Jun 24, 2026",
        price: "Rp 450.000",
        status: "in_progress",
        avatarColor: "bg-secondary-500",
    },
    {
        id: "2",
        orderCode: "#SVC-2026-002",
        title: "Cranial Implant Scan",
        service: "3D Scanning",
        date: "Jun 21, 2026",
        price: "Rp 320.000",
        status: "completed",
        avatarColor: "bg-emerald-500",
    },
    {
        id: "3",
        orderCode: "#SVC-2026-003",
        title: "Orthopedic Bracket Design",
        service: "3D Design & Modeling",
        date: "Jun 18, 2026",
        price: "Rp 180.000",
        status: "completed",
        avatarColor: "bg-primary-700",
    },
];
