import { FormEvent, useEffect, useRef, useState } from "react";
import { Head, router, useForm, usePage } from "@inertiajs/react";
import {
    Camera,
    CheckCircle2,
    User,
    Mail,
    ShieldCheck,
    CreditCard,
    Hash,
    Building2,
    GraduationCap,
    Layers,
    Phone,
    MapPin,
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
import ProjectsSection from "@/Features/Portfolio/Components/ProjectsSection/ProjectsSection";
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

type Mode = "overview" | "edit" | "activities";

type ActivityTab = "orders" | "projects" | "trainings";

const ACTIVITY_TABS: { id: ActivityTab; label: string }[] = [
    { id: "orders", label: "Service Orders" },
    { id: "projects", label: "My Projects" },
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

/* ── Reusable field wrapper ─────────────────────────── */
function Field({
    label,
    required,
    error,
    hint,
    icon,
    children,
}: {
    label: string;
    required?: boolean;
    error?: string;
    hint?: string;
    icon?: React.ReactNode;
    children: React.ReactNode;
}) {
    return (
        <div className="space-y-1.5">
            <label className="flex items-center gap-1.5 text-sm font-semibold text-slate-700">
                {icon && (
                    <span className="text-slate-400 w-4 h-4 shrink-0">{icon}</span>
                )}
                {label}
                {required && <span className="text-red-500">*</span>}
            </label>
            {children}
            {error && (
                <p className="text-red-500 text-xs pl-0.5">{error}</p>
            )}
            {hint && !error && (
                <p className="text-slate-400 text-xs pl-0.5">{hint}</p>
            )}
        </div>
    );
}

/* ── Styled text input ──────────────────────────────── */
function FormInput({
    className,
    ...props
}: React.InputHTMLAttributes<HTMLInputElement>) {
    return (
        <input
            className={cn(
                "h-11 w-full rounded-xl border border-slate-200 px-3.5 text-sm text-slate-700 placeholder-slate-400 bg-white",
                "focus:outline-none focus:border-[#00426D] focus:ring-1 focus:ring-[#00426D]",
                "disabled:bg-slate-50 disabled:text-slate-400 disabled:cursor-not-allowed",
                "transition-colors duration-150",
                className,
            )}
            {...props}
        />
    );
}

/* ── Role display label ─────────────────────────────── */
function roleLabel(role: string | null): string {
    if (role === "mahasiswa") return "Mahasiswa";
    if (role === "user_publik") return "Publik";
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

/* ── Profile-completion meter ───────────────────────── */
type FormFields = {
    name: string;
    email: string;
    nik: string;
    nim: string;
    university: string;
    faculty: string;
    phone: string;
    department: string;
    address: string;
};

function computeCompletion(fields: FormFields, isMahasiswa: boolean): number {
    const values: (string | null | undefined)[] = [
        fields.name,
        fields.email,
        fields.nik,
        ...(isMahasiswa ? [fields.nim, fields.university, fields.faculty] : []),
        fields.phone,
        fields.department,
        fields.address,
    ];
    const filled = values.filter((v) => v && v.trim() !== "").length;
    return Math.round((filled / values.length) * 100);
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
    const isMahasiswa = profile.role === "mahasiswa";
    const photoInputRef = useRef<HTMLInputElement>(null);
    const [photoPreview, setPhotoPreview] = useState<string | null>(null);
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

    const { data, setData, put, processing, errors, recentlySuccessful } =
        useForm({
            name: profile.name ?? "",
            email: profile.email ?? "",
            profile_photo: null as File | null,
            nik: profile.nik ?? "",
            nim: profile.nim ?? "",
            university: profile.university ?? "",
            faculty: profile.faculty ?? "",
            department: profile.department ?? "",
            phone: profile.phone ?? "",
            address: profile.address ?? "",
        });

    useEffect(() => {
        if (recentlySuccessful) setMode("overview");
    }, [recentlySuccessful]);

    function handlePhotoChange(e: React.ChangeEvent<HTMLInputElement>) {
        const file = e.target.files?.[0];
        if (!file) return;
        setData("profile_photo", file);
        setPhotoPreview(URL.createObjectURL(file));
    }

    function handleSubmit(e: FormEvent) {
        e.preventDefault();
        put("/profile", { forceFormData: true });
    }

    function openActivities(tab: ActivityTab) {
        setActivityTab(tab);
        setMode("activities");
    }

    const avatarSrc = photoPreview ?? profile.avatar ?? undefined;
    const displayName = data.name || profile.full_name || "—";
    const displayInitials = getInitials(data.name || profile.full_name || "U");
    const completion = computeCompletion(data, isMahasiswa);

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
            <Head title="Profil Saya" />
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
                                    {roleLabel(profile.role)}
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
                                    onClick={() => setMode("edit")}
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

                                <SeeDetailsLink onClick={() => openActivities(statTab === "projects" ? "projects" : "orders")} />
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
                                    onClick={() => openActivities("projects")}
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
                                    <p className="text-sm text-slate-400 py-4">Belum ada aktivitas.</p>
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

                    {mode === "edit" && (
                    <>
                    {recentlySuccessful && (
                        <div className="flex items-center gap-2.5 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
                            <CheckCircle2 className="h-4 w-4 shrink-0" />
                            Profil berhasil diperbarui!
                        </div>
                    )}

                    {/* Photo upload — only surfaced while editing */}
                    <Card>
                        <CardBody className="flex items-center gap-4 flex-wrap">
                            <div className="relative shrink-0">
                                <div className="w-16 h-16 rounded-full overflow-hidden ring-4 ring-white shadow-lg bg-linear-to-br from-[#00426D] to-[#00A8B5] flex items-center justify-center">
                                    {avatarSrc ? (
                                        <img src={avatarSrc} alt={displayName} className="w-full h-full object-cover" />
                                    ) : (
                                        <span className="text-white text-lg font-bold">{displayInitials}</span>
                                    )}
                                </div>
                                <button
                                    type="button"
                                    onClick={() => photoInputRef.current?.click()}
                                    aria-label="Ganti foto"
                                    className="absolute -bottom-0.5 -right-0.5 w-6 h-6 rounded-full bg-[#00426D] text-white flex items-center justify-center shadow-md hover:bg-[#003558] transition-colors focus:outline-none focus:ring-2 focus:ring-[#00426D]/50"
                                >
                                    <Camera className="h-3 w-3" />
                                </button>
                            </div>
                            <div className="flex-1 min-w-0">
                                <div className="flex items-center gap-3 flex-wrap">
                                    <button
                                        type="button"
                                        onClick={() => photoInputRef.current?.click()}
                                        className="inline-flex items-center gap-2 h-9 px-4 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:border-[#00426D] hover:text-[#00426D] hover:bg-[#00426D]/5 active:scale-[0.99] transition-all duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#00426D]/30"
                                    >
                                        <Camera className="h-3.5 w-3.5" />
                                        {photoPreview ? "Ganti Foto" : "Unggah Foto"}
                                    </button>
                                    {photoPreview ? (
                                        <p className="text-xs text-emerald-600 font-medium">✓ Foto baru dipilih</p>
                                    ) : (
                                        <p className="text-xs text-slate-400">JPG, PNG · Maks 2MB</p>
                                    )}
                                    {errors.profile_photo && (
                                        <p className="text-red-500 text-xs">{errors.profile_photo}</p>
                                    )}
                                </div>
                                <div className="mt-3 flex items-center gap-2.5">
                                    <div className="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div
                                            className="h-full bg-linear-to-r from-[#00426D] to-[#00A8B5] rounded-full transition-all duration-500"
                                            style={{ width: `${completion}%` }}
                                        />
                                    </div>
                                    <span className="text-xs text-slate-500 shrink-0 tabular-nums">Profil {completion}%</span>
                                </div>
                            </div>
                            <input
                                ref={photoInputRef}
                                type="file"
                                accept="image/*"
                                className="hidden"
                                onChange={handlePhotoChange}
                            />
                        </CardBody>
                    </Card>

                    <form onSubmit={handleSubmit} className="space-y-5">

                        {/* ══ Card 1: Informasi Akun ═══════════════════ */}
                        <Card>
                            <CardHeader className="pb-3">
                                <CardTitle>Informasi Akun</CardTitle>
                                <p className="text-sm text-slate-500 mt-0.5">
                                    Nama tampilan dan email yang terdaftar
                                </p>
                            </CardHeader>

                            <CardBody className="space-y-4 pt-2">
                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <Field
                                        label="Nama Lengkap"
                                        required
                                        error={errors.name}
                                        icon={<User className="h-4 w-4" />}
                                    >
                                        <FormInput
                                            value={data.name}
                                            onChange={(e) =>
                                                setData("name", e.target.value)
                                            }
                                            placeholder="Nama lengkap"
                                            autoComplete="name"
                                        />
                                    </Field>

                                    <Field
                                        label="Alamat Email"
                                        required
                                        error={errors.email}
                                        icon={<Mail className="h-4 w-4" />}
                                    >
                                        <FormInput
                                            type="email"
                                            value={data.email}
                                            onChange={(e) =>
                                                setData("email", e.target.value)
                                            }
                                            placeholder="nama@its.ac.id"
                                            autoComplete="email"
                                        />
                                    </Field>
                                </div>

                                {/* Role — read-only */}
                                <Field
                                    label="Peran"
                                    hint="Peran tidak dapat diubah"
                                    icon={<ShieldCheck className="h-4 w-4" />}
                                >
                                    <FormInput
                                        value={roleLabel(profile.role)}
                                        disabled
                                        readOnly
                                    />
                                </Field>
                            </CardBody>
                        </Card>

                        {/* ══ Card 2: Data Profil ══════════════════════ */}
                        <Card>
                            <CardHeader className="pb-3">
                                <CardTitle>Data Profil</CardTitle>
                                <p className="font-medium text-[#00426D] uppercase tracking-wider text-xs mt-0.5">
                                    {isMahasiswa
                                        ? "NIM, NIK, Universitas, dan Fakultas wajib diisi"
                                        : "NIK wajib diisi"}
                                </p>
                            </CardHeader>

                            <CardBody className="space-y-4 pt-2">
                                {/* NIK + NIM row */}
                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <Field
                                        label="NIK"
                                        required
                                        error={errors.nik}
                                        icon={<CreditCard className="h-4 w-4" />}
                                    >
                                        <FormInput
                                            value={data.nik}
                                            onChange={(e) =>
                                                setData("nik", e.target.value)
                                            }
                                            placeholder="3578XXXXXXXXXXXXXX"
                                            maxLength={20}
                                        />
                                    </Field>

                                    {isMahasiswa && (
                                        <Field
                                            label="NIM"
                                            required
                                            error={errors.nim}
                                            icon={<Hash className="h-4 w-4" />}
                                        >
                                            <FormInput
                                                value={data.nim}
                                                onChange={(e) =>
                                                    setData("nim", e.target.value)
                                                }
                                                placeholder="5031201013"
                                                maxLength={50}
                                            />
                                        </Field>
                                    )}
                                </div>

                                {/* University + Faculty — mahasiswa only */}
                                {isMahasiswa && (
                                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <Field
                                            label="Universitas"
                                            required
                                            error={errors.university}
                                            icon={<Building2 className="h-4 w-4" />}
                                        >
                                            <FormInput
                                                value={data.university}
                                                onChange={(e) =>
                                                    setData("university", e.target.value)
                                                }
                                                placeholder="ITS"
                                            />
                                        </Field>

                                        <Field
                                            label="Fakultas"
                                            required
                                            error={errors.faculty}
                                            icon={<GraduationCap className="h-4 w-4" />}
                                        >
                                            <FormInput
                                                value={data.faculty}
                                                onChange={(e) =>
                                                    setData("faculty", e.target.value)
                                                }
                                                placeholder="FTEIC"
                                            />
                                        </Field>
                                    </div>
                                )}

                                {/* Department + Phone */}
                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <Field
                                        label="Departemen"
                                        error={errors.department}
                                        icon={<Layers className="h-4 w-4" />}
                                    >
                                        <FormInput
                                            value={data.department}
                                            onChange={(e) =>
                                                setData("department", e.target.value)
                                            }
                                            placeholder="Teknologi Kedokteran"
                                        />
                                    </Field>

                                    <Field
                                        label="Nomor Telepon"
                                        error={errors.phone}
                                        icon={<Phone className="h-4 w-4" />}
                                    >
                                        <FormInput
                                            type="tel"
                                            value={data.phone}
                                            onChange={(e) =>
                                                setData("phone", e.target.value)
                                            }
                                            placeholder="081234567890"
                                        />
                                    </Field>
                                </div>

                                {/* Address */}
                                <Field
                                    label="Alamat Lengkap"
                                    error={errors.address}
                                    icon={<MapPin className="h-4 w-4" />}
                                >
                                    <textarea
                                        value={data.address}
                                        onChange={(e) =>
                                            setData("address", e.target.value)
                                        }
                                        rows={3}
                                        placeholder="Jl. Raya ITS, Sukolilo, Surabaya 60111"
                                        className="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 bg-white focus:outline-none focus:border-[#00426D] focus:ring-1 focus:ring-[#00426D] transition-colors duration-150 resize-none"
                                    />
                                </Field>
                            </CardBody>

                            <CardFooter className="flex items-center justify-end gap-3">
                                <button
                                    type="button"
                                    onClick={() => setMode("overview")}
                                    className="inline-flex items-center justify-center h-11 px-6 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors duration-150"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="inline-flex items-center justify-center h-11 px-6 rounded-xl text-sm font-bold text-white bg-linear-to-r from-[#00426D] to-[#00A8B5] shadow-lg shadow-[#00426D]/20 hover:opacity-90 active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed transition-all duration-150"
                                >
                                    {processing ? "Menyimpan..." : "Simpan Perubahan"}
                                </button>
                            </CardFooter>
                        </Card>
                    </form>
                    </>
                    )}

                    {mode === "activities" && (
                    <>
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
                            const count =
                                item.id === "orders"
                                    ? orders.length
                                    : item.id === "projects"
                                      ? projects.length
                                      : enrollments.length;

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
                    {activityTab === "projects" && <ProjectsSection projects={projects} />}
                    {activityTab === "trainings" && <TrainingsSection enrollments={enrollments} />}
                    </>
                    )}
                    </div>
                </div>
            </DashboardLayout>
        </>
    );
}
