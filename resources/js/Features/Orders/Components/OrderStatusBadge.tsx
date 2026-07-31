import Badge from "@/Core/Components/Shared/Badge/Badge";

type BadgeVariant = "verified" | "pending" | "rejected" | "neutral" | "tag";

const statusVariant: Record<string, BadgeVariant> = {
    // Customer-facing order stages
    warehouse_check: "pending",
    set_price: "pending",
    processing: "tag",
    post_processing: "tag",
    finish: "verified",
    cancelled: "rejected",
    // Production progress sub-stages (timeline entries)
    printing: "tag",
    finishing: "tag",
    completed: "verified",
    // Legacy admin statuses still present on old rows
    pending: "pending",
    negotiating: "pending",
    slicing: "tag",
    revising: "tag",
    in_progress: "tag",
    // Payment termin statuses
    awaiting_verification: "pending",
    paid: "verified",
    rejected: "rejected",
};

export default function OrderStatusBadge({ status, label }: { status: string; label?: string }) {
    const variant = statusVariant[status] ?? "neutral";
    return <Badge variant={variant}>{label ?? status.replace(/_/g, " ")}</Badge>;
}
