import { useState } from "react";
import { ChevronDown, User, ShoppingBag, Settings2, LogOut, Bookmark, ArrowLeftRight } from "lucide-react";
import { router, usePage } from "@inertiajs/react";
import {
    DropdownMenu,
    DropdownMenuTrigger,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
} from "@/Core/Components/Shared";
import Avatar from "@/Core/Components/Shared/Avatar/Avatar";
import SwitchModeModal from "@/Features/Dashboard/Components/SwitchModeModal";
import { useTranslation } from "@/Core/Hooks/useTranslation";

export default function TopbarUserMenu() {
    const { auth } = usePage().props;
    const { t } = useTranslation();
    const [modalOpen, setModalOpen] = useState(false);

    if (!auth?.user) {
        return (
            <div className="flex items-center gap-2">
                <a
                    href="/login"
                    className="px-4 py-1.5 text-sm font-semibold text-slate-700 border border-slate-200 rounded-full hover:bg-slate-50 transition-colors duration-150"
                >
                    {t("Login")}
                </a>
                <a
                    href="/register"
                    className="px-4 py-1.5 text-sm font-semibold text-white bg-[#00426D] rounded-full hover:bg-[#003558] transition-colors duration-150"
                >
                    {t("Register")}
                </a>
            </div>
        );
    }

    const user = auth.user;
    const roles = (user.roles as string[] | undefined) ?? [];
    const activeRole = (user.active_role as string | undefined) ?? "";
    const canSwitch = roles.length > 1;

    return (
        <>
            <span data-tour="user-menu" className="inline-flex">
            <DropdownMenu>
                <DropdownMenuTrigger className="flex items-center gap-2 pl-1 pr-3 py-1 rounded-full hover:bg-slate-100 transition-colors duration-150">
                    <Avatar
                        src={user.avatar as string | undefined}
                        name={user.name}
                        size="sm"
                        statusDot
                    />
                    <span className="hidden sm:block text-sm font-medium text-slate-700 whitespace-nowrap">
                        {user.name.split(" ")[0]}
                    </span>
                    <ChevronDown className="h-3.5 w-3.5 text-slate-400" />
                </DropdownMenuTrigger>

                <DropdownMenuContent width="w-56">
                    <div className="px-3.5 py-3 border-b border-slate-100">
                        <p className="text-sm font-semibold text-slate-800">{user.name}</p>
                        <p className="text-xs text-slate-500 mt-0.5">{user.email}</p>
                        {activeRole && (
                            <span className="mt-1.5 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-600">
                                {activeRole.replace(/_/g, " ").replace(/\b\w/g, (c) => c.toUpperCase())}
                            </span>
                        )}
                    </div>

                    <DropdownMenuItem icon={<User className="h-4 w-4" />} onClick={() => router.visit("/profile")}>
                        {t("My Profile")}
                    </DropdownMenuItem>
                    <DropdownMenuItem icon={<ShoppingBag className="h-4 w-4" />} onClick={() => router.visit("/orders")}>
                        {t("My Orders")}
                    </DropdownMenuItem>
                    <DropdownMenuItem icon={<Bookmark className="h-4 w-4" />} onClick={() => router.visit("/uploads")}>
                        {t("My Uploads")}
                    </DropdownMenuItem>

                    {canSwitch ? (
                        <DropdownMenuItem
                            icon={<ArrowLeftRight className="h-4 w-4" />}
                            onClick={() => setModalOpen(true)}
                        >
                            {t("Switch Mode")}
                        </DropdownMenuItem>
                    ) : (
                        <DropdownMenuItem
                            icon={<Settings2 className="h-4 w-4" />}
                            onClick={() => router.visit("/settings")}
                        >
                            {t("Settings")}
                        </DropdownMenuItem>
                    )}

                    <DropdownMenuSeparator />

                    <DropdownMenuItem
                        destructive
                        icon={<LogOut className="h-4 w-4" />}
                        onClick={() => router.post("/logout")}
                    >
                        {t("Sign out")}
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>
            </span>

            {canSwitch && (
                <SwitchModeModal
                    open={modalOpen}
                    onClose={() => setModalOpen(false)}
                    roles={roles}
                    activeRole={activeRole}
                />
            )}
        </>
    );
}
