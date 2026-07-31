import { type Locale } from "@/Core/Hooks/useTranslation";

/**
 * BCP 47 tag for `Intl` date formatting. Lifted out of Events/Utils/eventDate.ts,
 * which was the only place deriving this from the active locale instead of
 * hardcoding one.
 */
export const localeTag = (locale: Locale): string => (locale === "id" ? "id-ID" : "en-GB");

/**
 * Rupiah, always formatted the Indonesian way.
 *
 * Deliberately NOT locale-aware: the lab bills in IDR, so the amount and its
 * grouping must not change shape when the reader switches to English. Only dates
 * follow the locale — see localeTag above.
 */
export function formatIDR(amount: number): string {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
}

/** Grouped integer without the currency symbol, for prices that render their own "Rp". */
export const formatIDRAmount = (amount: number): string =>
    new Intl.NumberFormat("id-ID").format(amount);
