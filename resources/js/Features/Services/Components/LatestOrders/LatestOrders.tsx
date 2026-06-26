import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Card } from "@/Core/Components/Shared/Card/Card";
import Badge from "@/Core/Components/Shared/Badge/Badge";
import Button from "@/Core/Components/Shared/Button/Button";
import { type ServiceOrder, type ServiceOrderStatus } from "@/Features/Services/Types/service.type";

interface LatestOrdersProps {
    orders: ServiceOrder[];
}

const statusVariantMap: Record<
    ServiceOrderStatus,
    "verified" | "pending" | "rejected" | "neutral"
> = {
    completed: "verified",
    in_progress: "pending",
    pending: "neutral",
    cancelled: "rejected",
};

const statusLabelMap: Record<ServiceOrderStatus, string> = {
    completed: "Completed",
    in_progress: "In Progress",
    pending: "Pending",
    cancelled: "Cancelled",
};

const TABLE_COLUMNS = ["ORDER", "SERVICE", "DATE", "PRICE", "STATUS"] as const;

export default function LatestOrders({ orders }: LatestOrdersProps) {
    return (
        <Card className="overflow-hidden">
            {/* Panel header */}
            <Box className="flex items-center justify-between px-6 pt-6 pb-4 border-b border-slate-100">
                <Box>
                    <Heading
                        level={3}
                        className="font-display text-lg font-bold text-slate-800 mb-0.5"
                    >
                        Latest Service Orders
                    </Heading>
                    <Text className="text-xs text-slate-500">
                        Your most recent service orders and their status
                    </Text>
                </Box>
                <Button variant="ghost" size="sm" type="button">
                    View All Orders →
                </Button>
            </Box>

            {/* Scrollable table */}
            <Box className="overflow-x-auto">
                <Box as="table" className="w-full text-sm">
                    <Box as="thead">
                        <Box as="tr" className="border-b border-slate-100">
                            {TABLE_COLUMNS.map((col) => (
                                <Box
                                    key={col}
                                    as="th"
                                    className="px-6 py-3 text-left font-normal"
                                >
                                    <Text
                                        as="span"
                                        className="text-[11px] font-semibold text-slate-400 tracking-wide uppercase"
                                    >
                                        {col}
                                    </Text>
                                </Box>
                            ))}
                        </Box>
                    </Box>

                    <Box as="tbody">
                        {orders.map((order, i) => (
                            <Box
                                key={order.id}
                                as="tr"
                                className={
                                    i !== orders.length - 1
                                        ? "border-b border-slate-50 hover:bg-slate-50/60 transition-colors"
                                        : "hover:bg-slate-50/60 transition-colors"
                                }
                            >
                                {/* ORDER — avatar + title + code */}
                                <Box as="td" className="px-6 py-4">
                                    <Box className="flex items-center gap-3">
                                        <Box
                                            className={`w-8 h-8 rounded-full flex items-center justify-center shrink-0 ${order.avatarColor}`}
                                        >
                                            <Text
                                                as="span"
                                                className="text-white text-xs font-bold leading-none"
                                            >
                                                {order.title.charAt(0)}
                                            </Text>
                                        </Box>
                                        <Box>
                                            <Text
                                                as="span"
                                                className="text-sm font-semibold text-slate-800 block"
                                            >
                                                {order.title}
                                            </Text>
                                            <Text
                                                as="span"
                                                className="text-xs text-slate-400"
                                            >
                                                {order.orderCode}
                                            </Text>
                                        </Box>
                                    </Box>
                                </Box>

                                {/* SERVICE */}
                                <Box as="td" className="px-6 py-4">
                                    <Text className="text-sm text-slate-600">
                                        {order.service}
                                    </Text>
                                </Box>

                                {/* DATE */}
                                <Box as="td" className="px-6 py-4">
                                    <Text className="text-sm text-slate-600">
                                        {order.date}
                                    </Text>
                                </Box>

                                {/* PRICE */}
                                <Box as="td" className="px-6 py-4">
                                    <Text className="text-sm font-semibold text-slate-800">
                                        {order.price}
                                    </Text>
                                </Box>

                                {/* STATUS */}
                                <Box as="td" className="px-6 py-4">
                                    <Badge variant={statusVariantMap[order.status]}>
                                        {statusLabelMap[order.status]}
                                    </Badge>
                                </Box>
                            </Box>
                        ))}
                    </Box>
                </Box>
            </Box>
        </Card>
    );
}
