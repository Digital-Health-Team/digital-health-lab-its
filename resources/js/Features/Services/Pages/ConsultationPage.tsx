import { Head, Link, usePage } from "@inertiajs/react";
import { ArrowLeft } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import OrderChat from "@/Features/Orders/Components/OrderChat";
import { type ChatMessage } from "@/Features/Orders/Types/order.type";

interface ConsultationPageProps {
    // Named 'order' so OrderChat's partial reload (only: ["order"]) resolves.
    order: { id: number; messages: ChatMessage[] };
    [key: string]: unknown;
}

export default function ConsultationPage() {
    const { order } = usePage<ConsultationPageProps>().props;
    const { t } = useTranslation();

    return (
        <>
            <Head title={t("Consultation")} />
            <DashboardLayout>
                <Box className="max-w-3xl mx-auto space-y-5">
                    <Link
                        href="/services"
                        className="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-[#00426D]"
                    >
                        <ArrowLeft className="h-4 w-4" />
                        {t("Back to Services")}
                    </Link>

                    <Box>
                        <Heading
                            level={3}
                            className="font-display text-2xl font-bold text-slate-800 mb-1"
                        >
                            {t("Consultation")}
                        </Heading>
                        <Text className="text-sm text-slate-500">
                            {t(
                                "Ask the lab team anything about your idea, materials, or budget. An admin will reply here.",
                            )}
                        </Text>
                    </Box>

                    <OrderChat order={order} />
                </Box>
            </DashboardLayout>
        </>
    );
}
