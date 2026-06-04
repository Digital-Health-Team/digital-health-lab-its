import { Globe, ChevronDown } from "lucide-react";
import { useUiStore } from "@/Core/Store/ui.store";
import {
    DropdownMenu,
    DropdownMenuTrigger,
    DropdownMenuContent,
    DropdownMenuItem,
} from "@/Core/Components/Shared";

const langs = [
    { code: "en" as const, label: "English (EN)" },
    { code: "id" as const, label: "Bahasa Indonesia (ID)" },
];

export default function TopbarLanguageSwitcher() {
    const { language, setLanguage } = useUiStore();

    return (
        <DropdownMenu>
            <DropdownMenuTrigger className="flex items-center gap-1.5 px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-600 hover:text-slate-900 transition-colors duration-150">
                <Globe className="h-4 w-4" />
                <span className="text-sm font-medium">{language.toUpperCase()}</span>
                <ChevronDown className="h-3.5 w-3.5" />
            </DropdownMenuTrigger>
            <DropdownMenuContent width="w-44">
                {langs.map((l) => (
                    <DropdownMenuItem
                        key={l.code}
                        onClick={() => setLanguage(l.code)}
                        className={language === l.code ? "text-primary-700 font-semibold" : ""}
                    >
                        {l.label}
                    </DropdownMenuItem>
                ))}
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
