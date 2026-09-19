import { useEffect, useState } from "react";
import { Head, router, usePage } from "@inertiajs/react";
import {
    CheckCircle2,
    User,
    Mail,
    Building2,
    IdCard,
    Clock,
    LogOut,
    ArrowRight,
    ArrowLeft,
    Package,
    ShoppingBag,
    ChevronRight,
} from "lucide-react";
import { cn } from "@/Core/Utils/utils";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import type { SharedFlash } from "@/Core/Types/global";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import {
    Card,
    CardHeader,
    CardTitle,
    CardBody,
    CardFooter,
    Badge,
} from "@/Core/Components/Shared";
import OrdersSection from "@/Features/Portfolio/Components/OrdersSection/OrdersSection";
import TrainingsSection from "@/Features/Portfolio/Components/TrainingsSection/TrainingsSection";
import { type UserOrder, type UserProject, type UserEnrollment } from "@/Features/Portfolio/Types/portfolio.type";

interface ProfileProps {
    profile: {
        name: string | null;
        email: string | null;
        role: string | null;
        avatar: string | null;
        full_name: string | null;
        nim: string | null;
        nik: string | null;
        university: string | null;
        faculty: string | null;
        department: string | null;
        phone: string | null;
        address: string | null;
        member_since: string | null;
        verified: boolean;
    };
    orders: UserOrder[];
    projects: UserProject[];
    enrollments: UserEnrollment[];
    flash?: SharedFlash;
    [key: string]: unknown;
}

type Mode = "overview" | "activities";

// Uploads live on /publish now — this page keeps only the read-only stats for them.
type ActivityTab = "orders" | "trainings";

const ACTIVITY_TABS: { id: ActivityTab; label: string }[] = [
    { id: "orders", label: "Service Orders" },
    { id: "trainings", label: "Trainings" },
];

const PROJECT_CATEGORY_LABELS: Record<UserProject["category"], string> = {
    "3d_model": "3D Model",
    iot_system: "IoT System",
    medical_device: "Medical Device",
    software: "Software",
};

const PROJECT_CATEGORY_DOTS: Record<UserProject["category"], string> = {
    "3d_model": "bg-[#00A8B5]",
    iot_system: "bg-amber-400",
    medical_device: "bg-[#00426D]",
    software: "bg-violet-400",
};

const ORDER_STATUS_LABELS: Record<UserOrder["status"], string> = {
    pending: "Pending",
    negotiating: "Negotiating",
    in_progress: "In Progress",
    completed: "Completed",
    cancelled: "Cancelled",
};

const ORDER_STATUS_DOTS: Record<UserOrder["status"], string> = {
    pending: "bg-amber-400",
    negotiating: "bg-violet-400",
    in_progress: "bg-[#00A8B5]",
    completed: "bg-emerald-500",
    cancelled: "bg-red-400",
};

/* ── Relative time ──────────────────────────────────────
   ponytail: copied from PublicationRowItem's timeAgo instead of
   extracting a shared util; promote to @/Core/Utils if a third
   caller needs it. */
function timeAgo(dateStr: string): string {
    const diffMs = Date.now() - new Date(dateStr).getTime();
    const days = Math.floor(diffMs / (1000 * 60 * 60 * 24));
    if (days <= 0) return "Today";
    if (days === 1) return "1 day ago";
    if (days < 30) return `${days} days ago`;
    const months = Math.floor(days / 30);
    return `${months} month${months > 1 ? "s" : ""} ago`;
}

/* ── Role display label ─────────────────────────────── */
/** English source strings, which are also the t() keys the caller resolves. */
function roleLabel(role: string | null): string {
    if (role === "mahasiswa") return "Student";
    if (role === "user_publik") return "Public";
    return role ?? "—";
}

/* ── Avatar initials fallback ───────────────────────── */
function getInitials(name: string): string {
    return name
        .trim()
        .split(/\s+/)
        .slice(0, 2)
        .map((w) => w[0]?.toUpperCase() ?? "")
        .join("");
}

/* ── Profile-card info row ──────────────────────────── */
function InfoRow({
    icon,
    label,
    value,
    trailing,
}: {
    icon: React.ReactNode;
    label: string;
    value: React.ReactNode;
    trailing?: React.ReactNode;
}) {
    return (
        <div className="flex items-start gap-3">
            <span className="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#00426D]/10 text-[#00426D]">
                {icon}
            </span>
            <div className="min-w-0 flex-1">
                <p className="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                    {label}
                </p>
                <div className="flex flex-wrap items-center gap-2">
                    <p className="text-sm font-semibold text-slate-700 truncate">
                        {value}
                    </p>
                    {trailing}
                </div>
            </div>
        </div>
    );
}

/* ── Centered link-style "See Details" button ───────── */
function SeeDetailsLink({ onClick }: { onClick: () => void }) {
    return (
        <button
            type="button"
            onClick={onClick}
            className="mx-auto flex items-center gap-1.5 text-sm font-semibold text-[#00426D] hover:text-[#00A8B5] transition-colors duration-150"
        >
            See Details
            <ArrowRight className="h-4 w-4" />
        </button>
    );
}

/* ── My Activities link row ─────────────────────────── */
function ActivityRow({
    icon,
    label,
    count,
    onClick,
}: {
    icon: React.ReactNode;
    label: string;
    count: number;
    onClick: () => void;
}) {
    return (
        <button
            type="button"
            onClick={onClick}
            className="flex w-full items-center gap-3 px-4 sm:px-6 py-3.5 text-left transition-colors duration-150 hover:bg-slate-50"
        >
            <span className="h-4 w-4 shrink-0 text-slate-600">{icon}</span>
            <span className="flex-1 text-sm font-semibold text-slate-700">{label}</span>
            <span className="text-sm font-semibold text-slate-400 tabular-nums">{count}</span>
            <ChevronRight className="h-4 w-4 shrink-0 text-slate-300" />
        </button>
    );
}

/* ── Stat tile ───────────────────────────────────────── */
function StatTile({
    value,
    label,
    tone = "default",
}: {
    value: number;
    label: string;
    tone?: "default" | "accent" | "warning";
}) {
    return (
        <div
            className={cn(
                "rounded-xl px-4 py-3.5 text-center",
                tone === "accent" && "bg-[#00426D]/5",
                tone === "warning" && "bg-amber-50",
                tone === "default" && "bg-slate-50",
            )}
        >
            <p
                className={cn(
                    "text-2xl font-bold tabular-nums",
                    tone === "warning" ? "text-amber-600" : "text-[#00426D]",
                )}
            >
                {value}
            </p>
            <p className="text-xs text-slate-500 mt-0.5">{label}</p>
        </div>
    );
}

/* ═══════════════════════════════════════════════════════
   ProfilePage
═══════════════════════════════════════════════════════ */
export default function ProfilePage() {
    const { profile, orders, projects, enrollments, flash } =
        usePage<ProfileProps>().props;
    const { t } = useTranslation();
    const [mode, setMode] = useState<Mode>("overview");
    const [statTab, setStatTab] = useState<"projects" | "orders">("projects");
    const [activityTab, setActivityTab] = useState<ActivityTab>("orders");
    const [toast, setToast] = useState<string | null>(null);

    useEffect(() => {
        if (flash?.success) {
            setToast(flash.success);
            const t = setTimeout(() => setToast(null), 4000);
            return () => clearTimeout(t);
        }
    }, [flash?.success]);

    function openActivities(tab: ActivityTab) {
        setActivityTab(tab);
        setMode("activities");
    }

    const avatarSrc = profile.avatar ?? undefined;
    const displayName = profile.full_name ?? "—";
    const displayInitials = getInitials(profile.full_name ?? "U");

    const publishedCount = projects.filter((p) => p.status === "approved").length;
    const underReviewCount = projects.filter((p) => p.status === "pending").length;
    const projectsByCategory = (Object.keys(PROJECT_CATEGORY_LABELS) as UserProject["category"][])
        .map((category) => ({
            category,
            count: projects.filter((p) => p.category === category).length,
        }))
        .filter((row) => row.count > 0);

    const completedOrders = orders.filter((o) => o.status === "completed").length;
    const inProgressOrders = orders.filter((o) => o.status === "in_progress").length;
    const ordersByStatus = (Object.keys(ORDER_STATUS_LABELS) as UserOrder["status"][])
        .map((status) => ({
            status,
            count: orders.filter((o) => o.status === status).length,
        }))
        .filter((row) => row.count > 0);

    const recentActivity = [
        ...projects.map((p) => ({
            id: `project-${p.id}`,
            icon: <Package className="h-4 w-4" />,
            text: `Submitted "${p.title}"`,
            createdAt: p.createdAt,
        })),
        ...orders.map((o) => ({
            id: `order-${o.id}`,
            icon: <ShoppingBag className="h-4 w-4" />,
            text: `Ordered ${o.serviceName ?? "a service"}`,
            createdAt: o.createdAt,
        })),
        ...enrollments.map((e) => ({
            id: `enrollment-${e.id}`,
            icon: <CheckCircle2 className="h-4 w-4" />,
            text: `Registered for "${e.trainingTitle ?? "a training"}"`,
            createdAt: e.createdAt,
        })),
    ]
        .sort((a, b) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime())
        .slice(0, 4);

    return (
        <>
            <Head title={t("My Profile")} />
            <DashboardLayout>
                <div className="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-[minmax(0,380px)_minmax(0,1fr)] gap-6 items-start">

                    {/* ═══ Left rail: profile detail ═══════════════════ */}
                    <div className="lg:sticky lg:top-[5.5rem]">
                    <Card>
                        {/* Gradient cover band */}
                        <div className="h-28 bg-linear-to-r from-[#00426D] to-[#00A8B5] rounded-t-2xl relative overflow-hidden">
                            <div
                                className="absolute inset-0 opacity-10"
                                style={{
                                    backgroundImage:
                                        "radial-gradient(circle, white 1px, transparent 1px)",
                                    backgroundSize: "18px 18px",
                                }}
                            />
                        </div>

                        <div className="px-6 pb-6">
                            {/* Avatar */}
                            <div className="-mt-10 relative z-10">
                                <div className="w-20 h-20 rounded-full overflow-hidden ring-4 ring-white shadow-lg bg-linear-to-br from-[#00426D] to-[#00A8B5] flex items-center justify-center">
                                    {avatarSrc ? (
                                        <img
                                            src={avatarSrc}
                                            alt={displayName}
                                            className="w-full h-full object-cover"
                                        />
                                    ) : (
                                        <span className="text-white text-2xl font-bold">
                                            {displayInitials}
                                        </span>
                                    )}
                                </div>
                            </div>

                            {/* Identity text */}
                            <div className="mt-3">
                                <h1 className="text-xl font-bold text-slate-800 font-display truncate">
                                    {displayName}
                                </h1>
                                <span
                                    className={cn(
                                        "inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-xs font-semibold",
                                        profile.role === "mahasiswa"
                                            ? "bg-[#00426D]/10 text-[#00426D]"
                                            : "bg-slate-100 text-slate-600",
                                    )}
                                >
                                    {t(roleLabel(profile.role))}
                                </span>
                            </div>

                            {/* Info rows */}
                            <div className="mt-5 space-y-4">
                                {profile.email && (
                                    <InfoRow
                                        icon={<Mail className="h-4 w-4" />}
                                        label="Email"
                                        value={profile.email}
                                    />
                                )}
                                {profile.department && (
                                    <InfoRow
                                        icon={<Building2 className="h-4 w-4" />}
                                        label="Department"
                                        value={profile.department}
                                    />
                                )}
                                {profile.nim && (
                                    <InfoRow
                                        icon={<IdCard className="h-4 w-4" />}
                                        label="ITS Student ID"
                                        value={profile.nim}
                                        trailing={
                                            profile.verified && (
                                                <Badge variant="verified">ITS Verified</Badge>
                                            )
                                        }
                                    />
                                )}
                                {profile.member_since && (
                                    <InfoRow
                                        icon={<Clock className="h-4 w-4" />}
                                        label="Member Since"
                                        value={profile.member_since}
                                    />
                                )}
                            </div>

                            {/* Actions */}
                            <div className="mt-6 border-t border-slate-100 pt-4 space-y-1">
                                <button
                                    type="button"
                                    onClick={() => router.visit("/profile/edit")}
                                    className="inline-flex w-full items-center justify-center gap-2 h-11 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 hover:border-[#00426D] hover:text-[#00426D] hover:bg-[#00426D]/5 active:scale-[0.99] transition-all duration-150"
                                >
                                    <User className="h-4 w-4" />
                                    Edit Profile
                                </button>
                                <button
                                    type="button"
                                    onClick={() => router.post("/logout")}
                                    className="inline-flex w-full items-center justify-center gap-2 h-11 rounded-xl border border-red-200 bg-white text-sm font-semibold text-red-600 hover:bg-red-50 hover:border-red-300 active:scale-[0.99] transition-all duration-150"
                                >
                                    <LogOut className="h-4 w-4" />
                                    Log out
                                </button>
                            </div>
                        </div>
                    </Card>
                    </div>

                    {/* ═══ Right column ════════════════════════════════ */}
                    <div className="min-w-0 space-y-5">

                    {/* ── Flash toast ──────────────────────────────── */}
                    {toast && (
                        <div className="rounded-xl bg-emerald-50 border border-emerald-100 px-4 py-3 flex items-center justify-between">
                            <p className="text-sm text-emerald-700 font-medium">
                                {toast}
                            </p>
                            <button
                                type="button"
                                onClick={() => setToast(null)}
                                className="text-emerald-500 hover:text-emerald-700 text-lg leading-none"
                            >
                                ×
                            </button>
                        </div>
                    )}

                    {mode === "overview" && (
                    <>
                        {/* Uploads / Orders stats card */}
                        <Card>
                            <CardHeader className="pb-0">
                                <div className="flex gap-6 border-b border-slate-100">
                                    {(
                                        [
                                            { id: "projects", label: "Uploads" },
                                            { id: "orders", label: "Orders" },
                                        ] as const
                                    ).map((item) => (
                                        <button
                                            key={item.id}
                                            type="button"
                                            onClick={() => setStatTab(item.id)}
                                            className={cn(
                                                "pb-3 text-sm font-semibold border-b-2 -mb-px transition-colors duration-150",
                                                statTab === item.id
                                                    ? "border-[#00426D] text-slate-800"
                                                    : "border-transparent text-slate-400 hover:text-slate-600",
                                            )}
                                        >
                                            {item.label}
                                        </button>
                                    ))}
                                </div>
                            </CardHeader>

                            <CardBody className="space-y-5">
                                {statTab === "projects" ? (
                                    <>
                                        <div className="grid grid-cols-3 gap-3">
                                            <StatTile value={projects.length} label="Total Uploads" tone="accent" />
                                            <StatTile value={publishedCount} label="Published" />
                                            <StatTile value={underReviewCount} label="Under Review" tone="warning" />
                                        </div>
                                        {projectsByCategory.length > 0 && (
                                            <div className="space-y-2">
                                                {projectsByCategory.map((row) => (
                                                    <div key={row.category} className="flex items-center justify-between text-sm">
                                                        <span className="flex items-center gap-2 text-slate-600">
                                                            <span className={cn("h-2 w-2 rounded-full", PROJECT_CATEGORY_DOTS[row.category])} />
                                                            {PROJECT_CATEGORY_LABELS[row.category]}
                                                        </span>
                                                        <span className="font-semibold text-slate-700">{row.count}</span>
                                                    </div>
                                                ))}
                                            </div>
                                        )}
                                    </>
                                ) : (
                                    <>
                                        <div className="grid grid-cols-3 gap-3">
                                            <StatTile value={orders.length} label="Total Orders" tone="accent" />
                                            <StatTile value={completedOrders} label="Completed" />
                                            <StatTile value={inProgressOrders} label="In Progress" tone="warning" />
                                        </div>
                                        {ordersByStatus.length > 0 && (
                                            <div className="space-y-2">
                                                {ordersByStatus.map((row) => (
                                                    <div key={row.status} className="flex items-center justify-between text-sm">
                                                        <span className="flex items-center gap-2 text-slate-600">
                                                            <span className={cn("h-2 w-2 rounded-full", ORDER_STATUS_DOTS[row.status])} />
                                                            {ORDER_STATUS_LABELS[row.status]}
                                                        </span>
                                                        <span className="font-semibold text-slate-700">{row.count}</span>
                                                    </div>
                                                ))}
                                            </div>
                                        )}
                                    </>
                                )}

                                <SeeDetailsLink onClick={() => statTab === "projects" ? router.visit("/publish") : openActivities("orders")} />
                            </CardBody>
                        </Card>

                        {/* My Activities card */}
                        <Card>
                            <CardHeader className="pb-3">
                                <CardTitle>My Activities</CardTitle>
                            </CardHeader>
                            <CardBody className="px-0 pt-0 pb-2 divide-y divide-slate-100">
                                <ActivityRow
                                    icon={<Package className="h-4 w-4" />}
                                    label="Uploads"
                                    count={projects.length}
                                    onClick={() => router.visit("/publish")}
                                />
                                <ActivityRow
                                    icon={<ShoppingBag className="h-4 w-4" />}
                                    label="Orders"
                                    count={orders.length}
                                    onClick={() => openActivities("orders")}
                                />
                            </CardBody>
                        </Card>

                        {/* Recent Activity card */}
                        <Card>
                            <CardHeader className="pb-3">
                                <CardTitle>Recent Activity</CardTitle>
                            </CardHeader>
                            <CardBody className="pt-0">
                                {recentActivity.length === 0 ? (
                                    <p className="text-sm text-slate-400 py-4">{t("No activity yet.")}</p>
                                ) : (
                                    <div className="divide-y divide-slate-100">
                                        {recentActivity.map((item) => (
                                            <div key={item.id} className="flex items-center gap-3 py-3 first:pt-0 last:pb-0">
                                                <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#00426D]/10 text-[#00426D]">
                                                    {item.icon}
                                                </span>
                                                <div className="min-w-0 flex-1">
                                                    <p className="text-sm font-medium text-slate-700 truncate">{item.text}</p>
                                                    <p className="text-xs text-slate-400">{timeAgo(item.createdAt)}</p>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                )}
                            </CardBody>
                        </Card>
                    </>
                    )}

                    {mode === "activities" && (
                    <>
                    <button
                        type="button"
                        onClick={() => setMode("overview")}
                        className="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-[#00426D] transition-colors duration-150"
                    >
                        <ArrowLeft className="h-4 w-4" />
                        Back to Profile
                    </button>

                    {/* ── Activity sub-tabs ─────────────────────────── */}
                    <div className="flex gap-1 bg-slate-100 p-1 rounded-xl w-fit">
                        {ACTIVITY_TABS.map((item) => {
                            const count = item.id === "orders" ? orders.length : enrollments.length;

                            return (
                                <button
                                    key={item.id}
                                    type="button"
                                    onClick={() => setActivityTab(item.id)}
                                    className={cn(
                                        "px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2",
                                        activityTab === item.id
                                            ? "bg-white text-slate-800 shadow-sm"
                                            : "text-slate-500 hover:text-slate-700",
                                    )}
                                >
                                    {item.label}
                                    <span
                                        className={cn(
                                            "inline-flex items-center justify-center h-5 min-w-5 rounded-full text-xs font-semibold px-1.5",
                                            activityTab === item.id
                                                ? "bg-[#00426D] text-white"
                                                : "bg-slate-200 text-slate-500",
                                        )}
                                    >
                                        {count}
                                    </span>
                                </button>
                            );
                        })}
                    </div>

                    {activityTab === "orders" && <OrdersSection orders={orders} />}
                    {activityTab === "trainings" && <TrainingsSection enrollments={enrollments} />}
                    </>
                    )}
                    </div>
                </div>
            </DashboardLayout>
        </>
    );
}
