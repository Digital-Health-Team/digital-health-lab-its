import { useEffect, useState } from "react";
import { Head, Link, usePage } from "@inertiajs/react";
import { FolderOpen, Plus } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { Heading } from "@/Core/Components/Common/Heading";
import { Card, CardBody } from "@/Core/Components/Shared";
import Button from "@/Core/Components/Shared/Button/Button";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import PublishCard from "@/Features/Publish/Components/PublishCard";
import { type PublishItem } from "@/Features/Publish/Types/publish.type";

interface PublishPageProps {
    items: PublishItem[];
    flash?: { success?: string };
    [key: string]: unknown;
}

export default function PublishPage() {
    const { items, flash } = usePage<PublishPageProps>().props;
    const { t } = useTranslation();
    const [toast, setToast] = useState<string | null>(null);

    useEffect(() => {
        if (!flash?.success) return;
        setToast(flash.success);
        const timer = setTimeout(() => setToast(null), 4000);
        return () => clearTimeout(timer);
    }, [flash?.success]);

    return (
        <>
            <Head title={t("Your Publications")} />
            <DashboardLayout>
                {toast && (
                    <Box className="fixed bottom-6 right-6 z-50 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-medium text-white shadow-lg">
                        {toast}
                    </Box>
                )}

                <Box className="mb-8 flex flex-wrap items-start justify-between gap-4">
                    <Box className="max-w-xl">
                        <Text
                            as="span"
                            className="block text-xs font-bold uppercase tracking-[0.22em] text-secondary-500"
                        >
                            {t("Publish")}
                        </Text>
                        {/* md: overrides are required — Heading's default variant sets a responsive size per level. */}
                        <Heading
                            level={1}
                            className="mt-1 font-display text-3xl md:text-4xl font-extrabold text-primary-700"
                        >
                            {t("Your Publications")}
                        </Heading>
                        <Text className="mt-2 text-slate-500">
                            {t("Manage everything you've published to the platform, or start something new.")}
                        </Text>
                    </Box>

                    <Link
                        href="/publish/create"
                        className="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-primary-700 to-primary-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-primary-700/25 transition-shadow hover:shadow-xl hover:shadow-primary-700/35 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-600 focus-visible:ring-offset-2"
                    >
                        <Plus className="h-4 w-4" />
                        {t("Create")}
                    </Link>
                </Box>

                {items.length === 0 ? (
                    <Card>
                        <CardBody>
                            <Box className="flex flex-col items-center gap-3 py-14 text-center">
                                <FolderOpen className="h-10 w-10 text-slate-300" />
                                <Heading level={2} className="text-base md:text-base font-semibold text-slate-600">
                                    {t("Nothing published yet")}
                                </Heading>
                                <Text variant="small" className="max-w-xs text-slate-400">
                                    {t("Submit your work and wait for admin approval to have it listed publicly.")}
                                </Text>
                                <Link href="/publish/create" className="mt-2">
                                    <Button variant="primary" size="sm" className="gap-1.5">
                                        <Plus className="h-3.5 w-3.5" />
                                        {t("Create")}
                                    </Button>
                                </Link>
                            </Box>
                        </CardBody>
                    </Card>
                ) : (
                    <Box className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 items-start">
                        {items.map((item) => (
                            <PublishCard key={`${item.kind}-${item.id}`} item={item} />
                        ))}
                    </Box>
                )}
            </DashboardLayout>
        </>
    );
}
