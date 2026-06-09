import { useUiStore } from "@/Core/Store/ui.store";
import { translations, type TranslationKey } from "@/Core/Locales/translations";

export function useTranslation() {
    const language = useUiStore((s) => s.language);
    const t = (key: TranslationKey): string => translations[key][language];
    return { t, lang: language };
}
