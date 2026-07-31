import { switchLocale, useTranslation } from "@/Core/Hooks/useTranslation";

interface LanguageToggleProps {
    /** "glass" suits the dark landing navbar; "plain" suits light surfaces. */
    variant?: "glass" | "plain";
    className?: string;
}

/**
 * Two-state EN/ID toggle. Lives in Core because both Landing and Dashboard need it
 * and feature modules must not cross-import (see CLAUDE.md).
 *
 * Posting through switchLocale() — rather than calling router.post here — keeps the
 * mandatory preserveState:false in one place.
 */
export default function LanguageToggle({ variant = "glass", className = "" }: LanguageToggleProps) {
    const { lang } = useTranslation();

    const shell =
        variant === "glass"
            ? "bg-white/8 border-white/15"
            : "bg-slate-100 border-slate-200";

    const active =
        variant === "glass"
            ? "bg-white/90 text-primary-950"
            : "bg-white text-slate-900 shadow-sm";

    const idle =
        variant === "glass"
            ? "text-white/60 hover:text-white"
            : "text-slate-500 hover:text-slate-900";

    return (
        <div
            className={`inline-flex items-center rounded-full border p-0.5 ${shell} ${className}`}
            role="group"
            aria-label="Language"
        >
            {(["id", "en"] as const).map((code) => {
                const isActive = lang === code;
                return (
                    <button
                        key={code}
                        type="button"
                        onClick={() => !isActive && switchLocale(code)}
                        aria-pressed={isActive}
                        className={`px-2.5 py-1 rounded-full text-[0.7rem] font-body font-bold uppercase tracking-wider transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-secondary-400/60 ${
                            isActive ? active : idle
                        }`}
                    >
                        {code}
                    </button>
                );
            })}
        </div>
    );
}
