import { Head, Link, usePage } from "@inertiajs/react";
import { ArrowLeft, MessageCircle, FileText, CheckCircle2, Ruler, Download } from "lucide-react";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { Heading } from "@/Core/Components/Common/Heading";
import { Card, CardBody, CardHeader, CardTitle } from "@/Core/Components/Shared";
import Button from "@/Core/Components/Shared/Button/Button";
import OrderStatusBadge from "@/Features/Orders/Components/OrderStatusBadge";
import OrderChat from "@/Features/Orders/Components/OrderChat";
import PaymentTermins from "@/Features/Orders/Components/PaymentTermins";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { type OrderDetail } from "@/Features/Orders/Types/order.type";

interface OrderDetailPageProps {
    order: OrderDetail;
    whatsappUrl: string | null;
    [key: string]: unknown;
}

function InfoRow({ label, value }: { label: string; value: React.ReactNode }) {
    return (
        <Box className="flex items-center justify-between gap-4 py-2 border-b border-slate-100 last:border-0">
            <Text as="span" className="text-sm text-slate-500">
                {label}
            </Text>
            <Text as="span" className="text-sm font-semibold text-slate-800 text-right">
                {value}
            </Text>
        </Box>
    );
}

export default function OrderDetailPage() {
    const { order, whatsappUrl } = usePage<OrderDetailPageProps>().props;
    const { t } = useTranslation();

    return (
        <>
            <Head title={`${t("Order Detail")} · ${order.invoice}`} />
            <DashboardLayout>
                <Box className="max-w-5xl mx-auto space-y-5">
                    <Link href="/profile" className="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-[#00426D]">
                        <ArrowLeft className="h-4 w-4" />
                        {t("Back to My Orders")}
                    </Link>

                    <Box className="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">

                        {/* LEFT — Summary, Progress, Brief */}
                        <Box className="lg:col-span-2 space-y-5">

                            {/* Summary */}
                            <Card>
                                <CardBody className="space-y-4">
                                    <Box className="flex items-start justify-between gap-4">
                                        <Box className="space-y-1">
                                            <Text as="span" className="font-mono text-xs text-slate-400">
                                                {order.invoice}
                                            </Text>
                                            <Heading level={1} className="text-xl font-bold text-slate-800">
                                                {order.serviceName}
                                            </Heading>
                                        </Box>
                                        <OrderStatusBadge
                                            status={order.customerStage ?? order.status}
                                            label={order.customerStageLabel}
                                        />
                                    </Box>

                                    <Box>
                                        <InfoRow
                                            label={t("Agreed Price")}
                                            value={order.priceLabel ?? t("Awaiting price")}
                                        />
                                        <InfoRow label={t("Payment")} value={order.paymentStatus ?? "—"} />
                                        <InfoRow label={t("Ordered On")} value={order.createdAt ?? "—"} />
                                    </Box>

                                    {whatsappUrl ? (
                                        <a href={whatsappUrl} target="_blank" rel="noopener noreferrer" className="block">
                                            <Button className="w-full bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/30">
                                                <MessageCircle className="h-4 w-4" />
                                                {t("Contact Admin via WhatsApp")}
                                            </Button>
                                        </a>
                                    ) : (
                                        <Box className="rounded-xl bg-amber-50 border border-amber-100 px-3.5 py-2.5">
                                            <Text as="span" className="text-xs text-amber-700">
                                                {t("No WhatsApp contact is configured for this service yet.")}
                                            </Text>
                                        </Box>
                                    )}
                                </CardBody>
                            </Card>

                            {/* Progress timeline */}
                            <Card>
                                <CardHeader className="pb-2">
                                    <CardTitle className="text-base">{t("Production Progress")}</CardTitle>
                                </CardHeader>
                                <CardBody className="pt-0">
                                    {order.progress.length === 0 ? (
                                        <Text variant="small" className="text-slate-400">
                                            {t("No progress updates posted yet.")}
                                        </Text>
                                    ) : (
                                        <Box className="relative space-y-6 border-l-2 border-slate-100 pl-6">
                                            {order.progress.map((update) => (
                                                <Box key={update.id} className="relative">
                                                    <Box className="absolute -left-[31px] top-1 flex h-4 w-4 items-center justify-center rounded-full bg-[#00A8B5] ring-4 ring-white">
                                                        <CheckCircle2 className="h-3 w-3 text-white" />
                                                    </Box>
                                                    <Box className="flex items-center justify-between gap-3">
                                                        <OrderStatusBadge status={update.statusLabel} />
                                                        <Text as="span" className="text-xs text-slate-400">
                                                            {update.createdAt}
                                                        </Text>
                                                    </Box>
                                                    <Box className="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                                                        <Box
                                                            className="h-full rounded-full bg-gradient-to-r from-[#00426D] to-[#00A8B5]"
                                                            style={{ width: `${update.percentage}%` }}
                                                        />
                                                    </Box>
                                                    {update.notes && (
                                                        <Text variant="small" className="mt-2 text-slate-600">
                                                            {update.notes}
                                                        </Text>
                                                    )}
                                                    {update.attachments.length > 0 && (
                                                        <Box className="mt-2 flex flex-wrap gap-2">
                                                            {update.attachments.map((url, i) => (
                                                                <a key={i} href={url} target="_blank" rel="noopener noreferrer">
                                                                    <img
                                                                        src={url}
                                                                        alt={`${update.statusLabel} ${i + 1}`}
                                                                        className="h-16 w-16 rounded-lg border border-slate-200 object-cover"
                                                                    />
                                                                </a>
                                                            ))}
                                                        </Box>
                                                    )}
                                                </Box>
                                            ))}
                                        </Box>
                                    )}
                                </CardBody>
                            </Card>

                            {/* Brief + type-specific details */}
                            <Card>
                                <CardHeader className="pb-2">
                                    <CardTitle className="flex items-center gap-2 text-base">
                                        <FileText className="h-4 w-4 text-slate-400" />
                                        {t("Brief Description")}
                                    </CardTitle>
                                </CardHeader>
                                <CardBody className="pt-0 space-y-4">
                                    <Text variant="small" className="whitespace-pre-line text-slate-600">
                                        {order.briefDescription || "—"}
                                    </Text>

                                    {(order.materialPreference || order.filamentWidth || order.scanPurpose || order.objectDimensions || order.referencePhotoUrl || order.modelFileUrl) && (
                                        <Box className="border-t border-slate-100 pt-4 space-y-3">
                                            <Box className="flex items-center gap-1.5">
                                                <Ruler className="h-3.5 w-3.5 text-slate-400" />
                                                <Text as="span" className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                                    {t("Request Details")}
                                                </Text>
                                            </Box>

                                            {order.materialPreference && (
                                                <InfoRow label={t("Material")} value={order.materialPreference} />
                                            )}
                                            {order.filamentWidth && (
                                                <InfoRow label={t("Filament Width")} value={order.filamentWidth} />
                                            )}
                                            {order.scanPurpose && (
                                                <InfoRow label={t("Scan Purpose")} value={order.scanPurpose} />
                                            )}
                                            {order.objectDimensions && (
                                                <InfoRow
                                                    label={t("Object Size")}
                                                    value={[
                                                        order.objectDimensions.length && `L: ${order.objectDimensions.length} cm`,
                                                        order.objectDimensions.width && `W: ${order.objectDimensions.width} cm`,
                                                        order.objectDimensions.height && `H: ${order.objectDimensions.height} cm`,
                                                    ].filter(Boolean).join("  ·  ")}
                                                />
                                            )}

                                            {order.referencePhotoUrl && (
                                                <Box className="space-y-1.5">
                                                    <Text as="span" className="text-sm text-slate-500">{t("Reference Photo")}</Text>
                                                    <a href={order.referencePhotoUrl} target="_blank" rel="noopener noreferrer">
                                                        <img
                                                            src={order.referencePhotoUrl}
                                                            alt="Reference"
                                                            className="h-24 w-24 rounded-lg object-cover border border-slate-200"
                                                        />
                                                    </a>
                                                </Box>
                                            )}

                                            {order.modelFileUrl && (
                                                <Box>
                                                    <a
                                                        href={order.modelFileUrl}
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        className="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-100 text-sm font-medium text-slate-700 hover:bg-slate-200 transition-colors"
                                                    >
                                                        <Download className="h-4 w-4" />
                                                        {t("Download 3D Model File")}
                                                    </a>
                                                </Box>
                                            )}
                                        </Box>
                                    )}
                                </CardBody>
                            </Card>

                        </Box>

                        {/* RIGHT — Chat, Payment */}
                        <Box className="space-y-5">
                            <OrderChat order={order} />
                            <PaymentTermins order={order} />
                        </Box>

                    </Box>
                </Box>
            </DashboardLayout>
        </>
    );
}
