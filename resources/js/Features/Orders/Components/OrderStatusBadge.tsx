import Badge from "@/Core/Components/Shared/Badge/Badge";

type BadgeVariant = "verified" | "pending" | "rejected" | "neutral" | "tag";

const statusVariant: Record<string, BadgeVariant> = {
    pending: "pending",
    negotiating: "pending",
    slicing: "tag",
    printing: "tag",
    revising: "tag",
    in_progress: "tag",
    finishing: "tag",
    completed: "verified",
    cancelled: "rejected",
    // Payment termin statuses
    awaiting_verification: "pending",
    paid: "verified",
    rejected: "rejected",
};

export default function OrderStatusBadge({ status }: { status: string }) {
    const variant = statusVariant[status] ?? "neutral";
    return <Badge variant={variant}>{status.replace(/_/g, " ")}</Badge>;
}
