import { type Locale } from "@/Core/Hooks/useTranslation";

const localeTag = (locale: Locale): string => (locale === "id" ? "id-ID" : "en-GB");

const MS_PER_DAY = 86_400_000;

/** Day numeral + short month, for the date block stamped on every card. */
export function dateStamp(iso: string | null | undefined, locale: Locale) {
    if (!iso) return null;

    const date = new Date(iso);

    return {
        day: date.toLocaleDateString(localeTag(locale), { day: "numeric" }),
        month: date.toLocaleDateString(localeTag(locale), { month: "short" }),
        year: date.toLocaleDateString(localeTag(locale), { year: "numeric" }),
    };
}

/**
 * "6–10 Okt 2025" when the range spans days, "6 Okt 2025" when it doesn't.
 * Falls back to the start date alone when there is no end date.
 */
export function formatDateRange(
    startIso: string | null | undefined,
    endIso: string | null | undefined,
    locale: Locale,
): string | null {
    if (!startIso) return null;

    const tag = localeTag(locale);
    const start = new Date(startIso);
    const full: Intl.DateTimeFormatOptions = { day: "numeric", month: "short", year: "numeric" };

    if (!endIso) return start.toLocaleDateString(tag, full);

    const end = new Date(endIso);

    if (start.toDateString() === end.toDateString()) {
        return start.toLocaleDateString(tag, full);
    }

    const sameMonth =
        start.getFullYear() === end.getFullYear() && start.getMonth() === end.getMonth();

    const startPart = start.toLocaleDateString(
        tag,
        sameMonth ? { day: "numeric" } : { day: "numeric", month: "short" },
    );

    return `${startPart} – ${end.toLocaleDateString(tag, full)}`;
}

/** Inclusive day span of an event; 1 for a single-day event. */
export function durationInDays(
    startIso: string | null | undefined,
    endIso: string | null | undefined,
): number | null {
    if (!startIso) return null;
    if (!endIso) return 1;

    const start = new Date(startIso).setHours(0, 0, 0, 0);
    const end = new Date(endIso).setHours(0, 0, 0, 0);

    return Math.round((end - start) / MS_PER_DAY) + 1;
}

/** 1-based day the event is currently on. Only meaningful while ongoing. */
export function currentDay(startIso: string | null | undefined): number | null {
    if (!startIso) return null;

    const start = new Date(startIso).setHours(0, 0, 0, 0);
    const today = new Date().setHours(0, 0, 0, 0);

    return Math.round((today - start) / MS_PER_DAY) + 1;
}
