export interface CatalogService {
    id: number;
    name: string;
    description: string | null;
    priceLabel: string;
    hasContact: boolean;
}

export interface OrderSummary {
    id: number;
    invoice: string;
    serviceName: string;
    status: string;
    customerStage?: string;
    customerStageLabel?: string;
    priceLabel: string | null;
    paymentStatus: string | null;
    progressPercentage: number;
    createdAt: string | null;
}

export interface ProgressUpdate {
    id: number;
    statusLabel: string;
    percentage: number;
    notes: string | null;
    createdAt: string | null;
    attachments: string[];
}

export interface PaymentTermin {
    id: number;
    terminName: string;
    amountLabel: string;
    status: string; // pending | awaiting_verification | paid | rejected
    paidAt: string | null;
    proofUrl: string | null;
}

export interface PaymentSummary {
    totalLabel: string;
    paidLabel: string;
    remainingLabel: string;
}

export interface ChatMessage {
    id: number;
    body: string;
    senderId: number;
    senderName: string | null;
    isMine: boolean;
    createdAt: string | null;
}

export interface ObjectDimensions {
    length?: string;
    width?: string;
    height?: string;
}

export interface OrderDetail {
    id: number;
    invoice: string;
    serviceName: string;
    serviceType?: string | null;
    briefDescription: string | null;
    referencePhotoUrl?: string | null;
    modelFileUrl?: string | null;
    materialPreference?: string | null;
    filamentWidth?: string | null;
    scanPurpose?: string | null;
    objectDimensions?: ObjectDimensions | null;
    status: string;
    customerStage?: string;
    customerStageLabel?: string;
    priceLabel: string | null;
    paymentStatus: string | null;
    createdAt: string | null;
    progress: ProgressUpdate[];
    payments: PaymentTermin[];
    paymentSummary: PaymentSummary;
    messages: ChatMessage[];
}
