import { useState, type ReactNode } from "react";
import {
    ArrowLeftRight,
    Check,
    X,
    Shield,
    FlaskConical,
    Package,
    GraduationCap,
    Globe,
    User,
} from "lucide-react";
import { cn } from "@/Core/Utils/utils";
import Modal from "@/Core/Components/Shared/Modal/Modal";
import { useTranslation } from "@/Core/Hooks/useTranslation";

interface SwitchModeModalProps {
    open: boolean;
    onClose: () => void;
    roles: string[];
    activeRole: string;
}

const ROLE_ICONS: Record<string, ReactNode> = {
    super_admin:  <Shield className="h-5 w-5" />,
    admin_lab:    <FlaskConical className="h-5 w-5" />,
    admin_gudang: <Package className="h-5 w-5" />,
    mahasiswa:    <GraduationCap className="h-5 w-5" />,
    user_publik:  <Globe className="h-5 w-5" />,
};

function formatRole(role: string | null): string {
    if (!role) return "";
    return role.replace(/_/g, " ").replace(/\b\w/g, (c) => c.toUpperCase());
}

export default function SwitchModeModal({ open, onClose, roles, activeRole }: SwitchModeModalProps) {
    const { t } = useTranslation();
    const [selectedRole, setSelectedRole] = useState<string | null>(null);
    const [pending, setPending] = useState(false);

    const handleClose = () => {
        if (pending) return;
        setSelectedRole(null);
        onClose();
    };

    const handleSwitch = () => {
        if (!selectedRole || pending) return;
        setPending(true);

        // Use a native form POST so the browser follows the server redirect as a full
        // page navigation. Inertia's router.post would intercept the redirect and try
        // to render the Blade/Livewire dashboard inside the React shell (wrong).
        const csrf = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? "";
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "/switch-role";

        const tokenInput = document.createElement("input");
        tokenInput.type = "hidden";
        tokenInput.name = "_token";
        tokenInput.value = csrf;

        const roleInput = document.createElement("input");
        roleInput.type = "hidden";
        roleInput.name = "role";
        roleInput.value = selectedRole;

        form.appendChild(tokenInput);
        form.appendChild(roleInput);
        document.body.appendChild(form);
        form.submit();
    };

    return (
        <Modal open={open} onClose={handleClose}>
            {/* Header */}
            <div className="flex items-center gap-3 px-5 pt-5 pb-4 border-b border-slate-100">
                <div className="w-9 h-9 rounded-xl bg-[#082A55] flex items-center justify-center shrink-0">
                    <ArrowLeftRight className="h-4 w-4 text-white" />
                </div>
                <div className="min-w-0">
                    <h2 className="text-sm font-bold text-slate-800">{t("Switch Mode")}</h2>
                    <p className="text-xs text-slate-500 mt-0.5">{t("Choose the mode you want to switch to.")}</p>
                </div>
                <button
                    type="button"
                    onClick={handleClose}
                    disabled={pending}
                    className="ml-auto p-1.5 rounded-lg hover:bg-slate-100 text-slate-400 transition-colors shrink-0 disabled:opacity-50"
                    aria-label="Close"
                >
                    <X className="h-4 w-4" />
                </button>
            </div>

            {/* Role card grid */}
            <div className="grid grid-cols-2 gap-3 p-5">
                {roles.map((role) => {
                    const isActive   = role === activeRole;
                    const isSelected = role === selectedRole;
                    return (
                        <button
                            key={role}
                            type="button"
                            disabled={isActive || pending}
                            onClick={() => setSelectedRole(isSelected ? null : role)}
                            className={cn(
                                "flex flex-col items-center gap-2 p-4 rounded-2xl border-2 transition-all text-center",
                                isActive
                                    ? "bg-[#082A55] border-[#082A55] text-white cursor-default"
                                    : isSelected
                                    ? "bg-indigo-50 border-indigo-500 text-indigo-700 cursor-pointer"
                                    : "bg-slate-50 border-slate-200 text-slate-600 hover:border-indigo-300 hover:bg-indigo-50/40 cursor-pointer",
                            )}
                        >
                            <span
                                className={cn(
                                    "w-10 h-10 rounded-full flex items-center justify-center",
                                    isActive
                                        ? "bg-white/20"
                                        : isSelected
                                        ? "bg-indigo-100"
                                        : "bg-slate-200/80",
                                )}
                            >
                                {ROLE_ICONS[role] ?? <User className="h-5 w-5" />}
                            </span>
                            <span className="text-xs font-semibold leading-tight">{formatRole(role)}</span>
                            {isActive && (
                                <span className="text-[10px] font-bold uppercase tracking-widest opacity-60">
                                    {t("Current")}
                                </span>
                            )}
                            {isSelected && !isActive && (
                                <Check className="h-3.5 w-3.5 text-indigo-500" />
                            )}
                        </button>
                    );
                })}
            </div>

            {/* Footer */}
            <div className="px-5 py-4 border-t border-slate-100 flex items-center justify-end gap-2">
                <button
                    type="button"
                    onClick={handleClose}
                    disabled={pending}
                    className="px-4 py-2 text-sm font-semibold text-slate-700 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors disabled:opacity-50"
                >
                    {t("Cancel")}
                </button>
                <button
                    type="button"
                    disabled={!selectedRole || pending}
                    onClick={handleSwitch}
                    className="px-4 py-2 text-sm font-semibold text-white bg-[#082A55] rounded-xl hover:bg-[#0A3D7A] transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
                >
                    {pending
                        ? t("Switching...")
                        : selectedRole
                        ? `${t("Switch to")} ${formatRole(selectedRole)}`
                        : t("Select a mode")}
                </button>
            </div>
        </Modal>
    );
}
