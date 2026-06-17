import { FormEvent, useState } from "react";
import { Head, useForm, usePage } from "@inertiajs/react";
import { Wrench, MessageCircle, X } from "lucide-react";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { Heading } from "@/Core/Components/Common/Heading";
import { Card, CardBody } from "@/Core/Components/Shared";
import Button from "@/Core/Components/Shared/Button/Button";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { type CatalogService } from "@/Features/Orders/Types/order.type";

interface CatalogPageProps {
    services: CatalogService[];
}

export default function ServiceCatalogPage() {
    const { services } = usePage<CatalogPageProps>().props;
    const { t } = useTranslation();
    const [activeService, setActiveService] = useState<CatalogService | null>(null);

    const { data, setData, post, processing, errors, reset } = useForm({
        service_id: 0,
        brief_description: "",
    });

    function openOrder(service: CatalogService) {
        setActiveService(service);
        setData({ service_id: service.id, brief_description: "" });
    }

    function closeOrder() {
        setActiveService(null);
        reset();
    }

    function handleSubmit(e: FormEvent) {
        e.preventDefault();
        post("/orders", { onSuccess: () => closeOrder() });
    }

    return (
        <>
            <Head title={t("Services")} />
            <DashboardLayout>
                <Box className="space-y-1">
                    <Heading level={1} className="text-2xl font-bold text-slate-800 tracking-tight">
                        {t("Order a Service")}
                    </Heading>
                    <Text variant="small" className="text-slate-500">
                        {t("Pick a 3D-printing service, send a brief, then settle the price with our admin on WhatsApp.")}
                    </Text>
                </Box>

                {services.length === 0 ? (
                    <Card className="mt-6">
                        <CardBody>
                            <Text className="text-slate-500">{t("No services available yet.")}</Text>
                        </CardBody>
                    </Card>
                ) : (
                    <Box className="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        {services.map((service) => (
                            <Card key={service.id} className="flex flex-col">
                                <CardBody className="flex flex-col gap-3 grow">
                                    <Box className="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-[#00426D] to-[#00A8B5] text-white">
                                        <Wrench className="h-5 w-5" />
                                    </Box>
                                    <Heading level={3} className="text-lg font-bold text-slate-800">
                                        {service.name}
                                    </Heading>
                                    {service.description && (
                                        <Text variant="small" className="text-slate-500 line-clamp-3">
                                            {service.description}
                                        </Text>
                                    )}
                                    <Box className="mt-auto pt-2">
                                        <Text className="text-xs uppercase tracking-wide text-slate-400">
                                            {t("Starting from")}
                                        </Text>
                                        <Text className="text-base font-bold text-[#00426D]">
                                            {service.priceLabel}
                                        </Text>
                                    </Box>
                                    <Button className="mt-2 w-full" onClick={() => openOrder(service)}>
                                        {t("Order Now")}
                                    </Button>
                                </CardBody>
                            </Card>
                        ))}
                    </Box>
                )}
            </DashboardLayout>

            {/* Order brief modal */}
            {activeService && (
                <Box
                    className="fixed inset-0 z-50 flex items-center justify-center p-4"
                    role="dialog"
                    aria-modal="true"
                >
                    <Box
                        aria-hidden="true"
                        onClick={closeOrder}
                        className="absolute inset-0 bg-primary-950/80 backdrop-blur-sm"
                    />
                    <Card className="relative z-10 w-full max-w-lg">
                        <CardBody className="space-y-5">
                            <Box className="flex items-start justify-between gap-4">
                                <Box>
                                    <Heading level={2} className="text-xl font-bold text-slate-800">
                                        {t("Order")}: {activeService.name}
                                    </Heading>
                                    <Text variant="small" className="text-slate-500">
                                        {activeService.priceLabel}
                                    </Text>
                                </Box>
                                <Button variant="icon" onClick={closeOrder} aria-label={t("Close")}>
                                    <X className="h-5 w-5" />
                                </Button>
                            </Box>

                            <form onSubmit={handleSubmit} className="space-y-4">
                                <Box className="space-y-1.5">
                                    <Text
                                        as="span"
                                        className="block text-sm font-semibold text-slate-700"
                                    >
                                        {t("Brief Description")}
                                        <span className="text-red-500"> *</span>
                                    </Text>
                                    <textarea
                                        value={data.brief_description}
                                        onChange={(e) => setData("brief_description", e.target.value)}
                                        rows={5}
                                        placeholder={t("Describe what you want printed: model, size, material, quantity, deadline...")}
                                        className="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 bg-white focus:outline-none focus:border-[#00426D] focus:ring-1 focus:ring-[#00426D] transition-colors duration-150 resize-none"
                                    />
                                    {errors.brief_description && (
                                        <Text as="span" className="block text-xs text-red-500">
                                            {errors.brief_description}
                                        </Text>
                                    )}
                                </Box>

                                <Box className="flex items-center gap-2 rounded-xl bg-emerald-50 border border-emerald-100 px-3.5 py-2.5">
                                    <MessageCircle className="h-4 w-4 shrink-0 text-emerald-600" />
                                    <Text as="span" className="text-xs text-emerald-700">
                                        {t("After submitting, you'll get a WhatsApp link to discuss and confirm the price.")}
                                    </Text>
                                </Box>

                                <Box className="flex justify-end gap-3">
                                    <Button type="button" variant="outline" onClick={closeOrder}>
                                        {t("Cancel")}
                                    </Button>
                                    <Button type="submit" loading={processing} disabled={processing}>
                                        {t("Submit Order")}
                                    </Button>
                                </Box>
                            </form>
                        </CardBody>
                    </Card>
                </Box>
            )}
        </>
    );
}
