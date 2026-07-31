import { FormEvent, useRef, useState } from "react";
import { Head, Link, useForm, usePage } from "@inertiajs/react";
import {
    ArrowLeft,
    Camera,
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
} from "lucide-react";
import { cn } from "@/Core/Utils/utils";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import {
    Card,
    CardHeader,
    CardTitle,
    CardBody,
    CardFooter,
} from "@/Core/Components/Shared";

interface ProfileEditProps {
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
    };
    [key: string]: unknown;
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

/* ═══════════════════════════════════════════════════════
   ProfileEditPage
═══════════════════════════════════════════════════════ */
export default function ProfileEditPage() {
    const { profile } = usePage<ProfileEditProps>().props;
    const isMahasiswa = profile.role === "mahasiswa";
    const photoInputRef = useRef<HTMLInputElement>(null);
    const [photoPreview, setPhotoPreview] = useState<string | null>(null);

    const { data, setData, put, processing, errors } = useForm({
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

    const avatarSrc = photoPreview ?? profile.avatar ?? undefined;
    const displayName = data.name || profile.full_name || "—";
    const displayInitials = getInitials(data.name || profile.full_name || "U");
    const completion = computeCompletion(data, isMahasiswa);

    return (
        <>
            <Head title="Edit Profil" />
            <DashboardLayout>
                <div className="max-w-5xl mx-auto space-y-5">
                    <Link
                        href="/profile"
                        className="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-[#00426D] transition-colors duration-150"
                    >
                        <ArrowLeft className="h-4 w-4" />
                        Kembali ke Profil
                    </Link>

                    <div className="grid grid-cols-1 lg:grid-cols-[minmax(0,300px)_minmax(0,1fr)] gap-6 items-start">

                        {/* ═══ Left: photo upload ═══════════════════════ */}
                        <div className="lg:sticky lg:top-[5.5rem]">
                        <Card>
                            <CardBody className="flex flex-col items-center gap-4 text-center">
                                <div className="relative shrink-0">
                                    <div className="w-24 h-24 rounded-full overflow-hidden ring-4 ring-white shadow-lg bg-linear-to-br from-[#00426D] to-[#00A8B5] flex items-center justify-center">
                                        {avatarSrc ? (
                                            <img src={avatarSrc} alt={displayName} className="w-full h-full object-cover" />
                                        ) : (
                                            <span className="text-white text-2xl font-bold">{displayInitials}</span>
                                        )}
                                    </div>
                                    <button
                                        type="button"
                                        onClick={() => photoInputRef.current?.click()}
                                        aria-label="Ganti foto"
                                        className="absolute -bottom-0.5 -right-0.5 w-7 h-7 rounded-full bg-[#00426D] text-white flex items-center justify-center shadow-md hover:bg-[#003558] transition-colors focus:outline-none focus:ring-2 focus:ring-[#00426D]/50"
                                    >
                                        <Camera className="h-3.5 w-3.5" />
                                    </button>
                                </div>

                                <button
                                    type="button"
                                    onClick={() => photoInputRef.current?.click()}
                                    className="inline-flex items-center gap-2 h-9 px-4 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:border-[#00426D] hover:text-[#00426D] hover:bg-[#00426D]/5 active:scale-[0.99] transition-all duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#00426D]/30"
                                >
                                    <Camera className="h-3.5 w-3.5" />
                                    {photoPreview ? "Ganti Foto" : "Unggah Foto"}
                                </button>

                                {photoPreview ? (
                                    <p className="-mt-2 text-xs text-emerald-600 font-medium">✓ Foto baru dipilih</p>
                                ) : (
                                    <p className="-mt-2 text-xs text-slate-400">JPG, PNG · Maks 2MB</p>
                                )}
                                {errors.profile_photo && (
                                    <p className="-mt-2 text-red-500 text-xs">{errors.profile_photo}</p>
                                )}

                                <div className="w-full flex items-center gap-2.5">
                                    <div className="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div
                                            className="h-full bg-linear-to-r from-[#00426D] to-[#00A8B5] rounded-full transition-all duration-500"
                                            style={{ width: `${completion}%` }}
                                        />
                                    </div>
                                    <span className="text-xs text-slate-500 shrink-0 tabular-nums">Profil {completion}%</span>
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
                        </div>

                        {/* ═══ Right: account & profile data ════════════ */}
                        <form onSubmit={handleSubmit} className="min-w-0 space-y-5">

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
                                <Link
                                    href="/profile"
                                    className="inline-flex items-center justify-center h-11 px-6 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors duration-150"
                                >
                                    Batal
                                </Link>
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
                    </div>
                </div>
            </DashboardLayout>
        </>
    );
}
