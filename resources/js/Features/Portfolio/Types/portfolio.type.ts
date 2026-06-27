export interface UserOrder {
    id: number;
    serviceName: string | null;
    serviceType: string | null;
    status: "pending" | "negotiating" | "in_progress" | "completed" | "cancelled";
    agreedPrice: number | null;
    createdAt: string;
}

export interface UserProject {
    id: number;
    title: string;
    caption: string | null;
    category: "3d_model" | "iot_system" | "medical_device" | "software";
    listingType: string | null;
    status: "pending" | "approved" | "rejected";
    license: string;
    version: string | null;
    format: string | null;
    description: string[];
    highlights: string[];
    includes: string[];
    coverUrl: string | null;
    createdAt: string;
}

export interface UserEnrollment {
    id: number;
    trainingId: number;
    trainingTitle: string | null;
    trainingSlug: string | null;
    trainingDate: string | null;
    trainingLocation: string | null;
    status: "pending" | "confirmed" | "cancelled";
    paymentStatus: "unpaid" | "awaiting_verification" | "paid" | "rejected";
    createdAt: string;
}
