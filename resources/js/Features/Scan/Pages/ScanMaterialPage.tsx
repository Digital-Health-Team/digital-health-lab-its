import { Head, usePage } from "@inertiajs/react";
import React from "react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { formatIDRAmount } from "@/Core/Utils/locale";

interface StockEntry {
    lab: string | null;
    color: string | null;
    quantity: number;
}

interface Movement {
    id: number;
    type: string;
    quantity: number;
    notes: string | null;
    actor: string | null;
    created_at: string;
}

interface MaterialScanProps {
    [key: string]: unknown;
    material: {
        unique_code: string;
        name: string;
        unit: string;
        brand: string | null;
        stocks: StockEntry[];
    };
    movements: Movement[];
}

export default function ScanMaterialPage(): React.JSX.Element {
    const { material, movements } = usePage<MaterialScanProps>().props;
    const { t } = useTranslation();

    const totalStock = material.stocks.reduce((sum, s) => sum + s.quantity, 0);

    return (
        <>
            <Head title={`${t("Raw Material")}: ${material.name}`} />

            <Box className="min-h-screen bg-slate-50 flex items-start justify-center py-10 px-4">
                <Box className="w-full max-w-sm space-y-4">

                    {/* Main card */}
                    <Box className="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">

                        {/* Header */}
                        <Box className="bg-emerald-600 px-6 py-4">
                            <Text className="text-[10px] font-bold uppercase tracking-widest text-emerald-200">
                                {t("Raw Material")} — Digital Health Lab ITS
                            </Text>
                            <Heading level={2} className="text-white font-black text-xl leading-tight mt-0.5">
                                {material.name}
                            </Heading>
                            {material.brand && (
                                <Text className="text-emerald-200 text-xs mt-0.5">{material.brand}</Text>
                            )}
                        </Box>

                        <Box className="px-6 py-5 space-y-4">

                            {/* Unique Code */}
                            <Box className="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                                <Text className="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                                    {t("Unique Code")}
                                </Text>
                                <Text className="font-mono font-bold text-slate-800 text-sm tracking-wider">
                                    {material.unique_code}
                                </Text>
                            </Box>

                            {/* Total Stock */}
                            <Box className="flex items-center justify-between bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3">
                                <Text className="text-sm font-semibold text-emerald-700">{t("Total Stock")}</Text>
                                <Text className="text-lg font-black text-emerald-700">
                                    {formatIDRAmount(totalStock)} {material.unit}
                                </Text>
                            </Box>

                            {/* Stock per location */}
                            {material.stocks.length > 0 && (
                                <Box>
                                    <Text className="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">
                                        {t("Stock per Location")}
                                    </Text>
                                    <Box className="space-y-2">
                                        {material.stocks.map((s, i) => (
                                            <Box key={i} className="flex items-center justify-between text-sm">
                                                <Box className="flex items-center gap-1.5">
                                                    <Text className="text-slate-600">{s.lab ?? '—'}</Text>
                                                    {s.color && (
                                                        <Text className="text-[10px] text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded">
                                                            {s.color}
                                                        </Text>
                                                    )}
                                                </Box>
                                                <Text className="font-semibold text-slate-700">
                                                    {formatIDRAmount(s.quantity)} {material.unit}
                                                </Text>
                                            </Box>
                                        ))}
                                    </Box>
                                </Box>
                            )}
                        </Box>
                    </Box>

                    {/* Recent movements */}
                    {movements.length > 0 && (
                        <Box className="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">
                            <Box className="px-6 py-4 border-b border-slate-100">
                                <Heading level={3} className="text-base font-bold text-slate-800">
                                    {t("Movement History")}
                                </Heading>
                            </Box>
                            <Box className="divide-y divide-slate-100">
                                {movements.map((m) => (
                                    <Box key={m.id} className="px-6 py-3 flex items-start justify-between gap-3">
                                        <Box className="flex-1 min-w-0">
                                            <Box className="flex items-center gap-2">
                                                <Box className={`w-1.5 h-1.5 rounded-full shrink-0 ${m.type === 'in' ? 'bg-emerald-500' : 'bg-rose-500'}`} />
                                                <Text className="text-xs font-semibold text-slate-700 capitalize">
                                                    {m.type === 'in' ? t('Stock In') : t('Stock Out')}
                                                </Text>
                                            </Box>
                                            {m.notes && (
                                                <Text className="text-[11px] text-slate-500 mt-0.5 truncate">{m.notes}</Text>
                                            )}
                                            <Text className="text-[10px] text-slate-400 mt-0.5">
                                                {m.actor ?? '—'} · {m.created_at}
                                            </Text>
                                        </Box>
                                        <Text className={`text-sm font-bold shrink-0 ${m.type === 'in' ? 'text-emerald-600' : 'text-rose-600'}`}>
                                            {m.type === 'in' ? '+' : '-'}{m.quantity} {material.unit}
                                        </Text>
                                    </Box>
                                ))}
                            </Box>
                        </Box>
                    )}

                    <Text className="text-[10px] text-slate-400 text-center pb-4">
                        {t("Digital Health Lab ITS — scan the QR code for the latest info")}
                    </Text>
                </Box>
            </Box>
        </>
    );
}
