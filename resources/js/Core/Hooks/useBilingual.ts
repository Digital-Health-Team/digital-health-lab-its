import { useTranslation } from "@/Core/Hooks/useTranslation";

/**
 * For the few sections that show BOTH languages at once by design — a large title
 * with the other language as its subtitle. Localising swaps which one leads rather
 * than dropping one, so these strings deliberately do NOT go through `t()`.
 *
 * Replaces the three copies of this logic that used to live inline in
 * OrganizationSection (`primary`/`secondary`/`bilingual`) and ContactForm (`pair`).
 */
export function useBilingual() {
    const { lang } = useTranslation();

    const primary = (id: string, en: string) => (lang === "en" ? en : id);
    const secondary = (id: string, en: string) => (lang === "en" ? id : en);

    return {
        primary,
        secondary,
        /** Both languages on one line, active locale first. */
        joined: (id: string, en: string) => `${primary(id, en)} · ${secondary(id, en)}`,
        /** Two-line treatment: `lead` above, `sub` beneath. */
        pair: (id: string, en: string) => ({ lead: primary(id, en), sub: secondary(id, en) }),
    };
}
