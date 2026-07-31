import { FormEvent, useRef } from "react";
import { useForm } from "@inertiajs/react";
import { Banknote, Upload, Receipt } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { Card, CardBody, CardHeader, CardTitle } from "@/Core/Components/Shared";
import Button from "@/Core/Components/Shared/Button/Button";
import OrderStatusBadge from "@/Features/Orders/Components/OrderStatusBadge";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { type OrderDetail, type PaymentTermin } from "@/Features/Orders/Types/order.type";

function SummaryCell({ label, value, accent }: { label: string; value: string; accent?: boolean }) {
    return (
        <Box className="rounded-xl border border-slate-100 bg-slate-50/60 px-3.5 py-3">
            <Text as="span" className="block text-xs uppercase tracking-wide text-slate-400">
                {label}
            </Text>
            <Text as="span" className={`block text-sm font-bold ${accent ? "text-[#00A8B5]" : "text-slate-800"}`}>
                {value}
            </Text>
        </Box>
    );
}

function TerminRow({ orderId, termin }: { orderId: number; termin: PaymentTermin }) {
    const { t } = useTranslation();
    const fileInput = useRef<HTMLInputElement>(null);

    const { setData, post, processing, errors, reset } = useForm<{ proof: File | null }>({
        proof: null,
    });

    function handleSubmit(e: FormEvent) {
        e.preventDefault();
        post(`/orders/${orderId}/payments/${termin.id}/proof`, {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                reset();
                if (fileInput.current) fileInput.current.value = "";
            },
        });
    }

    const canUpload = termin.status !== "paid";

    return (
        <Box className="rounded-xl border border-slate-100 p-3.5 space-y-3">
            <Box className="flex items-center justify-between gap-3">
                <Box className="min-w-0">
                    <Text as="span" className="block text-sm font-semibold text-slate-800">
                        {termin.terminName}
                    </Text>
                    <Text as="span" className="block text-sm text-slate-500">
                        {termin.amountLabel}
                    </Text>
                </Box>
                <Box className="flex items-center gap-2 shrink-0">
                    {termin.proofUrl && (
                        <a href={termin.proofUrl} target="_blank" rel="noopener noreferrer" title={t("View proof")}>
                            <img
                                src={termin.proofUrl}
                                alt={t("Payment proof")}
                                className="h-10 w-10 rounded-lg border border-slate-200 object-cover"
                            />
                        </a>
                    )}
                    <OrderStatusBadge status={termin.status} />
                </Box>
            </Box>

            {canUpload && (
                <form onSubmit={handleSubmit} className="space-y-2 border-t border-slate-100 pt-3">
                    <input
                        ref={fileInput}
                        type="file"
                        accept="image/*"
                        onChange={(e) => setData("proof", e.target.files?.[0] ?? null)}
                        className="block w-full text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-200"
                    />
                    {errors.proof && (
                        <Text as="span" className="block text-xs text-red-500">
                            {errors.proof}
                        </Text>
                    )}
                    <Button type="submit" loading={processing} disabled={processing} className="w-full">
                        <Upload className="h-4 w-4" />
                        {termin.proofUrl ? t("Replace Transfer Proof") : t("Upload Transfer Proof")}
                    </Button>
                </form>
            )}
        </Box>
    );
}

export default function PaymentTermins({ order }: { order: OrderDetail }) {
    const { t } = useTranslation();
    const { payments, paymentSummary } = order;

    return (
        <Card>
            <CardHeader className="pb-2">
                <CardTitle className="flex items-center gap-2 text-base">
                    <Banknote className="h-4 w-4 text-slate-400" />
                    {t("Payment Details")}
                </CardTitle>
            </CardHeader>
            <CardBody className="pt-0 space-y-4">
                <Box className="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <SummaryCell label={t("Total Agreed")} value={paymentSummary.totalLabel} />
                    <SummaryCell label={t("Paid")} value={paymentSummary.paidLabel} />
                    <SummaryCell label={t("Remaining")} value={paymentSummary.remainingLabel} accent />
                </Box>

                {payments.length === 0 ? (
                    <Box className="flex flex-col items-center gap-2 rounded-xl bg-slate-50 px-4 py-8 text-center">
                        <Receipt className="h-7 w-7 text-slate-300" />
                        <Text variant="small" className="text-slate-400">
                            {t("No payment termins set yet — your admin will configure these after the price is agreed.")}
                        </Text>
                    </Box>
                ) : (
                    <Box className="space-y-3">
                        {payments.map((termin) => (
                            <TerminRow key={termin.id} orderId={order.id} termin={termin} />
                        ))}
                    </Box>
                )}
            </CardBody>
        </Card>
    );
}
