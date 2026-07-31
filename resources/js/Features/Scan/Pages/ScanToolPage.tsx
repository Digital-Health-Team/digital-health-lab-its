import { Head, usePage } from "@inertiajs/react";
import React from "react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { useTranslation } from "@/Core/Hooks/useTranslation";

interface ToolScanProps {
    [key: string]: unknown;
    tool: {
        unique_code: string;
        name: string;
        lab: string | null;
        added_by: string | null;
        added_on: string;
        image: string | null;
    };
}

export default function ScanToolPage(): React.JSX.Element {
    const { tool } = usePage<ToolScanProps>().props;
    const { t } = useTranslation();

    return (
        <>
            <Head title={`${t("Tool")}: ${tool.name}`} />

            <Box className="min-h-screen bg-slate-50 flex items-start justify-center py-10 px-4">
                <Box className="w-full max-w-sm bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">

                    {/* Header */}
                    <Box className="bg-indigo-600 px-6 py-4">
                        <Text className="text-[10px] font-bold uppercase tracking-widest text-indigo-200">
                            Digital Health Lab ITS
                        </Text>
                        <Heading level={2} className="text-white font-black text-xl leading-tight mt-0.5">
                            {tool.name}
                        </Heading>
                    </Box>

                    {/* Image */}
                    {tool.image && (
                        <Box className="aspect-[4/3] bg-slate-100 overflow-hidden">
                            <img
                                src={tool.image}
                                alt={tool.name}
                                className="w-full h-full object-cover"
                            />
                        </Box>
                    )}

                    {/* Info */}
                    <Box className="px-6 py-5 space-y-4">

                        {/* Unique Code */}
                        <Box className="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                            <Text className="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                                {t("Unique Code")}
                            </Text>
                            <Text className="font-mono font-bold text-slate-800 text-sm tracking-wider">
                                {tool.unique_code}
                            </Text>
                        </Box>

                        {/* Details */}
                        <Box className="space-y-3">
                            {tool.lab && (
                                <Box className="flex items-start gap-3">
                                    <Box className="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                        <svg className="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </Box>
                                    <Box>
                                        <Text className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                            {t("Location")}
                                        </Text>
                                        <Text className="text-sm font-semibold text-slate-700 mt-0.5">
                                            {tool.lab}
                                        </Text>
                                    </Box>
                                </Box>
                            )}

                            {tool.added_by && (
                                <Box className="flex items-start gap-3">
                                    <Box className="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                        <svg className="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </Box>
                                    <Box>
                                        <Text className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                            {t("Added By")}
                                        </Text>
                                        <Text className="text-sm font-semibold text-slate-700 mt-0.5">
                                            {tool.added_by}
                                        </Text>
                                    </Box>
                                </Box>
                            )}

                            <Box className="flex items-start gap-3">
                                <Box className="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center shrink-0">
                                    <svg className="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </Box>
                                <Box>
                                    <Text className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                        {t("Date Added")}
                                    </Text>
                                    <Text className="text-sm font-semibold text-slate-700 mt-0.5">
                                        {tool.added_on}
                                    </Text>
                                </Box>
                            </Box>
                        </Box>
                    </Box>

                    {/* Footer */}
                    <Box className="border-t border-slate-100 px-6 py-3">
                        <Text className="text-[10px] text-slate-400 text-center">
                            {t("Digital Health Lab ITS — scan the QR code for the latest info")}
                        </Text>
                    </Box>

                </Box>
            </Box>
        </>
    );
}
