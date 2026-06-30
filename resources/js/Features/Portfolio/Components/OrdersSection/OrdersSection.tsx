import { Link } from "@inertiajs/react";
import { ArrowRight, ChevronRight, Package } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { Heading } from "@/Core/Components/Common/Heading";
import { Card, CardBody } from "@/Core/Components/Shared";
import Button from "@/Core/Components/Shared/Button/Button";
import { type UserOrder } from "@/Features/Portfolio/Types/portfolio.type";

interface OrdersProps {
    orders: UserOrder[];
}

const STATUS_STYLES: Record<string, string> = {
    pending: "bg-amber-100 text-amber-700",
    negotiating: "bg-blue-100 text-blue-700",
    in_progress: "bg-purple-100 text-purple-700",
    completed: "bg-emerald-100 text-emerald-700",
    cancelled: "bg-slate-100 text-slate-500",
};

const STATUS_LABELS: Record<string, string> = {
    pending: "Pending",
    negotiating: "Negotiating",
    in_progress: "In Progress",
    completed: "Completed",
    cancelled: "Cancelled",
};

export default function OrdersSection({ orders }: OrdersProps) {
    if (orders.length === 0) {
        return (
            <Card>
                <CardBody>
                    <Box className="flex flex-col items-center gap-3 py-10 text-center">
                        <Package className="h-10 w-10 text-slate-300" />
                        <Heading level={3} className="text-base font-semibold text-slate-600">
                            No orders yet
                        </Heading>
                        <Text variant="small" className="text-slate-400 max-w-xs">
                            Browse our services and place your first order.
                        </Text>
                        <Link
                            href="/services"
                            className="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-[#00426D] hover:underline"
                        >
                            Browse services <ArrowRight className="h-3.5 w-3.5" />
                        </Link>
                    </Box>
                </CardBody>
            </Card>
        );
    }

    return (
        <Box className="space-y-4">
            <Box className="flex items-center justify-between">
                <Text variant="small" className="text-slate-400">
                    {orders.length} order{orders.length !== 1 ? "s" : ""}
                </Text>
                <Link href="/services">
                    <Button size="sm">Order Now</Button>
                </Link>
            </Box>

            <Box className="space-y-3">
                {orders.map((order) => (
                    <Link key={order.id} href={`/orders/${order.id}`} className="block">
                        <Card className="transition-shadow hover:shadow-lg">
                            <CardBody className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <Box className="space-y-1">
                                    <Box className="flex items-center gap-2">
                                        <Text as="span" className="font-mono text-xs text-slate-400">
                                            {order.invoice}
                                        </Text>
                                        <Box
                                            as="span"
                                            className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${STATUS_STYLES[order.status] ?? "bg-slate-100 text-slate-600"}`}
                                        >
                                            {STATUS_LABELS[order.status] ?? order.status}
                                        </Box>
                                    </Box>
                                    <Heading level={3} className="text-base font-bold text-slate-800">
                                        {order.serviceName ?? "Service"}
                                    </Heading>
                                    <Text variant="small" className="text-slate-400">
                                        {order.createdAt}
                                    </Text>
                                </Box>

                                <Box className="flex items-center gap-4">
                                    <Box className="text-right">
                                        <Text className="text-sm font-bold text-[#00426D]">
                                            {order.priceLabel ?? "Awaiting price"}
                                        </Text>
                                        <Text as="span" className="block text-xs text-slate-400">
                                            {order.progressPercentage}% · {order.paymentStatus ?? "—"}
                                        </Text>
                                    </Box>
                                    <ChevronRight className="h-5 w-5 text-slate-300" />
                                </Box>
                            </CardBody>
                        </Card>
                    </Link>
                ))}
            </Box>
        </Box>
    );
}
