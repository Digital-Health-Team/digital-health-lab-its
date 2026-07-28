import { Link } from "@inertiajs/react";
import { useTranslation } from "@/Core/Hooks/useTranslation";

interface ViewProfileLinkProps {
    href: string;
    align?: "left" | "right";
    /** Defaults to the translated "View Profile". */
    label?: string;
    className?: string;
}

// Explicit "View Profile" affordance, styled like NewsShow's hand-rolled
// back-link (no "link" variant exists on the shared Button component).
// Used both under leader blocks and inside the member hover/tap card.
export default function ViewProfileLink({
    href,
    align = "left",
    label,
    className = "",
}: ViewProfileLinkProps) {
    const { t } = useTranslation();
    const text = label ?? t("View Profile");

    return (
        <Link
            href={href}
            className={`group inline-flex items-center gap-1.5 text-xs font-body font-semibold text-primary-700 underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-secondary-500 focus-visible:outline-offset-2 rounded-sm ${align === "right" ? "flex-row-reverse" : ""} ${className}`}
        >
            {text}
            <span
                aria-hidden="true"
                className={`transition-transform duration-300 ${align === "right" ? "group-hover:-translate-x-0.5 rotate-180" : "group-hover:translate-x-0.5"}`}
            >
                &rarr;
            </span>
        </Link>
    );
}
