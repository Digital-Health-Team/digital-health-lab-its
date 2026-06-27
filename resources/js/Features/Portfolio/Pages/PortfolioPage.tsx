import { useState, useEffect } from "react";
import { Head, usePage } from "@inertiajs/react";
import { Briefcase } from "lucide-react";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { Heading } from "@/Core/Components/Common/Heading";
import { cn } from "@/Core/Utils/utils";
import OrdersSection from "@/Features/Portfolio/Components/OrdersSection/OrdersSection";
import ProjectsSection from "@/Features/Portfolio/Components/ProjectsSection/ProjectsSection";
import TrainingsSection from "@/Features/Portfolio/Components/TrainingsSection/TrainingsSection";
import { type UserOrder, type UserProject, type UserEnrollment } from "@/Features/Portfolio/Types/portfolio.type";

interface PortfolioPageProps {
    orders: UserOrder[];
    projects: UserProject[];
    enrollments: UserEnrollment[];
    flash: { success?: string; error?: string };
    [key: string]: unknown;
}

type Tab = "orders" | "projects" | "trainings";

const TABS: { id: Tab; label: string; countKey: keyof Pick<PortfolioPageProps, "orders" | "projects" | "enrollments"> }[] = [
    { id: "orders", label: "Service Orders", countKey: "orders" },
    { id: "projects", label: "My Projects", countKey: "projects" },
    { id: "trainings", label: "Trainings", countKey: "enrollments" },
];

export default function PortfolioPage() {
    const { orders, projects, enrollments, flash } = usePage<PortfolioPageProps>().props;
    const [activeTab, setActiveTab] = useState<Tab>("orders");
    const [toast, setToast] = useState<string | null>(null);

    useEffect(() => {
        if (flash?.success) {
            setToast(flash.success);
            const t = setTimeout(() => setToast(null), 4000);
            return () => clearTimeout(t);
        }
    }, [flash?.success]);

    return (
        <>
            <Head title="My Portfolio" />
            <DashboardLayout>
                <Box className="max-w-5xl mx-auto space-y-6">
                    {/* Header */}
                    <Box className="flex items-center gap-3">
                        <Box className="flex h-10 w-10 items-center justify-center rounded-xl bg-[#00426D]/10">
                            <Briefcase className="h-5 w-5 text-[#00426D]" />
                        </Box>
                        <Box>
                            <Heading level={1} className="text-xl font-bold text-slate-800">
                                My Portfolio
                            </Heading>
                            <Text variant="small" className="text-slate-400">
                                All your activity in one place
                            </Text>
                        </Box>
                    </Box>

                    {/* Flash toast */}
                    {toast && (
                        <Box className="rounded-xl bg-emerald-50 border border-emerald-100 px-4 py-3 flex items-center justify-between">
                            <Text variant="small" className="text-emerald-700 font-medium">
                                {toast}
                            </Text>
                            <button
                                type="button"
                                onClick={() => setToast(null)}
                                className="text-emerald-500 hover:text-emerald-700 text-lg leading-none"
                            >
                                ×
                            </button>
                        </Box>
                    )}

                    {/* Tab bar */}
                    <Box className="flex gap-1 bg-slate-100 p-1 rounded-xl w-fit">
                        {TABS.map((tab) => {
                            const count =
                                tab.countKey === "orders"
                                    ? orders.length
                                    : tab.countKey === "projects"
                                      ? projects.length
                                      : enrollments.length;

                            return (
                                <button
                                    key={tab.id}
                                    type="button"
                                    onClick={() => setActiveTab(tab.id)}
                                    className={cn(
                                        "px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2",
                                        activeTab === tab.id
                                            ? "bg-white text-slate-800 shadow-sm"
                                            : "text-slate-500 hover:text-slate-700",
                                    )}
                                >
                                    {tab.label}
                                    <Box
                                        as="span"
                                        className={cn(
                                            "inline-flex items-center justify-center h-5 min-w-5 rounded-full text-xs font-semibold px-1.5",
                                            activeTab === tab.id
                                                ? "bg-[#00426D] text-white"
                                                : "bg-slate-200 text-slate-500",
                                        )}
                                    >
                                        {count}
                                    </Box>
                                </button>
                            );
                        })}
                    </Box>

                    {/* Tab content */}
                    {activeTab === "orders" && <OrdersSection orders={orders} />}
                    {activeTab === "projects" && <ProjectsSection projects={projects} />}
                    {activeTab === "trainings" && <TrainingsSection enrollments={enrollments} />}
                </Box>
            </DashboardLayout>
        </>
    );
}
