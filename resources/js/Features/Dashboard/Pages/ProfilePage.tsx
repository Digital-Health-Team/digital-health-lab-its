import { FormEvent, useRef, useState } from "react";
import { Head, useForm } from "@inertiajs/react";
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
    };
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

/* ═══════════════════════════════════════════════════════
   ProfilePage
═══════════════════════════════════════════════════════ */
export default function ProfilePage({ profile }: ProfileProps) {
    const isMahasiswa = profile.role === "mahasiswa";
    const photoInputRef = useRef<HTMLInputElement>(null);
    const [photoPreview, setPhotoPreview] = useState<string | null>(null);

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
    const displayInitials = getInitials(data.name || profile.full_name || "U");

    return (
        <>
            <Head title="Profil Saya" />
            <DashboardLayout>
                <div className="max-w-3xl mx-auto space-y-5">

                    {/* ── Page heading ─────────────────── */}
                    <div>
                        <h1 className="text-2xl font-bold text-slate-800 tracking-tight">
                            Profil Saya
                        </h1>
                        <p className="text-sm text-slate-500 mt-0.5">
                            Kelola informasi akun dan data profil kamu.
                        </p>
                    </div>

                    {/* ── Success banner ───────────────── */}
                    {recentlySuccessful && (
                        <div className="flex items-center gap-2.5 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
                            <CheckCircle2 className="h-4 w-4 shrink-0" />
                            Profil berhasil diperbarui!
                        </div>
                    )}

                    <form
                        onSubmit={handleSubmit}
                        className="space-y-5"
                    >
                        {/* ══ Card 1: Informasi Akun ═══════════════════ */}
                        <Card>
                            <CardHeader className="pb-3">
                                <CardTitle>Informasi Akun</CardTitle>
                                <p className="text-sm text-slate-500 mt-0.5">
                                    Foto profil, nama tampilan, dan email
                                </p>
                            </CardHeader>

                            <CardBody className="space-y-5 pt-2">
                                {/* Photo upload row */}
                                <div className="flex items-center gap-5">
                                    {/* Avatar preview */}
                                    <div className="relative shrink-0">
                                        <div className="w-20 h-20 rounded-full overflow-hidden ring-4 ring-white shadow-md bg-gradient-to-br from-[#00426D] to-[#00A8B5] flex items-center justify-center">
                                            {avatarSrc ? (
                                                <img
                                                    src={avatarSrc}
                                                    alt={data.name}
                                                    className="w-full h-full object-cover"
                                                />
                                            ) : (
                                                <span className="text-white text-2xl font-bold">
                                                    {displayInitials}
                                                </span>
                                            )}
                                        </div>
                                        {/* Small camera badge */}
                                        <button
                                            type="button"
                                            onClick={() =>
                                                photoInputRef.current?.click()
                                            }
                                            aria-label="Ganti foto"
                                            className="absolute -bottom-0.5 -right-0.5 w-7 h-7 rounded-full bg-[#00426D] text-white flex items-center justify-center shadow-md hover:bg-[#003558] transition-colors focus:outline-none focus:ring-2 focus:ring-[#00426D]/50"
                                        >
                                            <Camera className="h-3.5 w-3.5" />
                                        </button>
                                    </div>

                                    {/* Upload button + hint */}
                                    <div className="space-y-1.5">
                                        <button
                                            type="button"
                                            onClick={() =>
                                                photoInputRef.current?.click()
                                            }
                                            className="inline-flex items-center gap-2 h-10 px-4 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:border-[#00426D] hover:text-[#00426D] hover:bg-[#00426D]/5 active:scale-[0.99] transition-all duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#00426D]/30"
                                        >
                                            <Camera className="h-4 w-4" />
                                            {photoPreview
                                                ? "Ganti Foto"
                                                : "Unggah Foto"}
                                        </button>
                                        {photoPreview ? (
                                            <p className="text-xs text-emerald-600 font-medium pl-0.5">
                                                ✓ Foto baru dipilih
                                            </p>
                                        ) : (
                                            <p className="text-xs text-slate-400 pl-0.5">
                                                JPG, PNG · Maks 2MB
                                            </p>
                                        )}
                                        {errors.profile_photo && (
                                            <p className="text-red-500 text-xs pl-0.5">
                                                {errors.profile_photo}
                                            </p>
                                        )}
                                    </div>

                                    <input
                                        ref={photoInputRef}
                                        type="file"
                                        accept="image/*"
                                        className="hidden"
                                        onChange={handlePhotoChange}
                                    />
                                </div>

                                {/* Name + Email */}
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
                                <p className="text-sm font-medium text-[#00426D] uppercase tracking-wider text-xs mt-0.5">
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
                                                    setData(
                                                        "nim",
                                                        e.target.value,
                                                    )
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
                                            icon={
                                                <Building2 className="h-4 w-4" />
                                            }
                                        >
                                            <FormInput
                                                value={data.university}
                                                onChange={(e) =>
                                                    setData(
                                                        "university",
                                                        e.target.value,
                                                    )
                                                }
                                                placeholder="ITS"
                                            />
                                        </Field>

                                        <Field
                                            label="Fakultas"
                                            required
                                            error={errors.faculty}
                                            icon={
                                                <GraduationCap className="h-4 w-4" />
                                            }
                                        >
                                            <FormInput
                                                value={data.faculty}
                                                onChange={(e) =>
                                                    setData(
                                                        "faculty",
                                                        e.target.value,
                                                    )
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
                                                setData(
                                                    "department",
                                                    e.target.value,
                                                )
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
                                <a
                                    href="/dashboard"
                                    className="inline-flex items-center justify-center h-11 px-6 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors duration-150"
                                >
                                    Batal
                                </a>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="inline-flex items-center justify-center h-11 px-6 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-[#00426D] to-[#00A8B5] shadow-lg shadow-[#00426D]/20 hover:opacity-90 active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed transition-all duration-150"
                                >
                                    {processing
                                        ? "Menyimpan..."
                                        : "Simpan Perubahan"}
                                </button>
                            </CardFooter>
                        </Card>
                    </form>
                </div>
            </DashboardLayout>
        </>
    );
}
