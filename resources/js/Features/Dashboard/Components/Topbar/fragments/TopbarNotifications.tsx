import { Bell } from "lucide-react";
import { usePage } from "@inertiajs/react";

export default function TopbarNotifications() {
    const { auth } = usePage().props;

    if (!auth?.user) return null;

    const count = 0;

    return (
        <button
            type="button"
            aria-label={`Notifications${count > 0 ? `, ${count} unread` : ""}`}
            className="relative w-10 h-10 flex items-center justify-center rounded-full text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-colors duration-150"
        >
            <Bell className="h-5 w-5" />
            {count > 0 && (
                <span
                    aria-hidden="true"
                    className="absolute top-2 right-2 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white animate-badge-pulse"
                />
            )}
        </button>
    );
}
