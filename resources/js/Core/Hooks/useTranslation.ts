import { router, usePage } from "@inertiajs/react";

export type Locale = "en" | "id";

interface TranslationProps {
    locale: Locale;
    translations: Record<string, string>;
}

/**
 * Switch the active locale server-side.
 *
 * preserveState:false is REQUIRED, not cosmetic. Inertia defaults it to true for POST,
 * which keeps the page key and reuses the component. Every landing animation is
 * useGSAP(fn, { scope }) with no dependency array, so it runs once on mount — and
 * setupChapterIntroState() captures the .glyph-char NodeList and pins it to opacity:0.
 * Swapping text without a remount leaves those nodes stale and permanently invisible,
 * and leaves ScrollTrigger pin distances measured against the old copy.
 */
export function switchLocale(locale: Locale): void {
    router.post(
        "/locale",
        { locale },
        { preserveState: false, preserveScroll: false },
    );
}

/**
 * Reads the locale + translation map shared by HandleInertiaRequests, so React and
 * Blade resolve copy from the same lang/*.json files.
 *
 * Keys ARE the English source string, so a missing key degrades to English exactly
 * like Laravel's __(). That is also why the `en` payload is empty — nothing to look up.
 */
export function useTranslation() {
    const { locale, translations } = usePage().props as unknown as TranslationProps;

    // ponytail: no :placeholder interpolation — add it when a string actually needs one.
    const t = (key: string): string => translations?.[key] ?? key;

    return { t, lang: locale };
}
