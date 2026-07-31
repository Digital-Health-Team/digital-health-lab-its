import { Head, Link, usePage } from "@inertiajs/react";
import { Package, ChevronRight } from "lucide-react";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { Heading } from "@/Core/Components/Common/Heading";
import { Card, CardBody } from "@/Core/Components/Shared";
import Button from "@/Core/Components/Shared/Button/Button";
import OrderStatusBadge from "@/Features/Orders/Components/OrderStatusBadge";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { type OrderSummary } from "@/Features/Orders/Types/order.type";

interface OrderHistoryPageProps {
    orders: OrderSummary[];
    [key: string]: unknown;
}

export default function OrderHistoryPage() {
    const { orders } = usePage<OrderHistoryPageProps>().props;
    const { t } = useTranslation();

    return (
        <>
            <Head title={t("My Orders")} />
            <DashboardLayout>
                <Box className="flex items-center justify-between gap-4">
                    <Box className="space-y-1">
                        <Heading level={1} className="text-2xl font-bold text-slate-800 tracking-tight">
                            {t("My Orders")}
                        </Heading>
                        <Text variant="small" className="text-slate-500">
                            {t("Your order history and production progress.")}
                        </Text>
                    </Box>
                    <Link href="/services">
                        <Button>{t("Order Now")}</Button>
                    </Link>
                </Box>

                {orders.length === 0 ? (
                    <Card className="mt-6">
                        <CardBody className="flex flex-col items-center gap-3 py-12 text-center">
                            <Box className="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                <Package className="h-7 w-7" />
                            </Box>
                            <Text className="text-slate-500">{t("You have no orders yet.")}</Text>
                            <Link href="/services">
                                <Button variant="outline">{t("Browse Services")}</Button>
                            </Link>
                        </CardBody>
                    </Card>
                ) : (
                    <Box className="mt-6 space-y-3">
                        {orders.map((order) => (
                            <Link key={order.id} href={`/orders/${order.id}`} className="block">
                                <Card className="transition-shadow hover:shadow-lg">
                                    <CardBody className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <Box className="space-y-1">
                                            <Box className="flex items-center gap-2">
                                                <Text as="span" className="font-mono text-xs text-slate-400">
                                                    {order.invoice}
                                                </Text>
                                                <OrderStatusBadge
                                                    status={order.customerStage ?? order.status}
                                                    label={order.customerStageLabel}
                                                />
                                            </Box>
                                            <Heading level={3} className="text-base font-bold text-slate-800">
                                                {order.serviceName}
                                            </Heading>
                                            <Text variant="small" className="text-slate-400">
                                                {order.createdAt}
                                            </Text>
                                        </Box>

                                        <Box className="flex items-center gap-4">
                                            <Box className="text-right">
                                                <Text className="text-sm font-bold text-[#00426D]">
                                                    {order.priceLabel ?? t("Awaiting price")}
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
                )}
            </DashboardLayout>
        </>
    );
}
