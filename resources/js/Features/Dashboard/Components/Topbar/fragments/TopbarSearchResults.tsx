import { router } from "@inertiajs/react";
import { BookOpen, FolderOpen, ShoppingBag, Wrench, Loader2, GraduationCap } from "lucide-react";

export interface SearchResult {
    type: "training" | "publication" | "project" | "product" | "service";
    title: string;
    subtitle: string;
    href: string;
}

interface TopbarSearchResultsProps {
    query: string;
    results: SearchResult[];
    loading: boolean;
    onClose: () => void;
    onViewAll?: () => void;
}

const TYPE_META: Record<SearchResult["type"], { label: string; icon: React.ElementType; color: string }> = {
    training:    { label: "Training",     icon: GraduationCap, color: "text-teal-500" },
    publication: { label: "Publications", icon: BookOpen,       color: "text-blue-500" },
    project:     { label: "Projects",     icon: FolderOpen,     color: "text-emerald-500" },
    product:     { label: "Products",     icon: ShoppingBag,    color: "text-amber-500" },
    service:     { label: "Services",     icon: Wrench,         color: "text-violet-500" },
};

const TYPE_ORDER: SearchResult["type"][] = ["training", "publication", "project", "product", "service"];

export default function TopbarSearchResults({ query, results, loading, onClose, onViewAll }: TopbarSearchResultsProps) {
    const grouped = TYPE_ORDER.reduce<Record<string, SearchResult[]>>((acc, type) => {
        const items = results.filter((r) => r.type === type);
        if (items.length > 0) acc[type] = items;
        return acc;
    }, {});

    const isEmpty = results.length === 0 && !loading;

    const handleSelect = (href: string) => {
        onClose();
        router.visit(href);
    };

    return (
        <div className="absolute top-full left-0 right-0 mt-1 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 overflow-hidden max-h-[480px] overflow-y-auto">
            {loading && (
                <div className="flex items-center justify-center gap-2 py-8 text-slate-400 text-sm">
                    <Loader2 className="h-4 w-4 animate-spin" />
                    Searching...
                </div>
            )}

            {!loading && isEmpty && (
                <div className="py-8 text-center text-slate-400 text-sm">
                    No results for{" "}
                    <span className="font-medium text-slate-600">"{query}"</span>
                </div>
            )}

            {!loading && !isEmpty && Object.entries(grouped).map(([type, items]) => {
                const meta = TYPE_META[type as SearchResult["type"]];
                const Icon = meta.icon;

                return (
                    <div key={type}>
                        <div className="flex items-center gap-2 px-4 py-2 bg-slate-50 border-b border-slate-100">
                            <Icon className={`h-3.5 w-3.5 ${meta.color}`} />
                            <span className="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                {meta.label}
                            </span>
                        </div>
                        {items.map((result) => (
                            <button
                                key={result.href}
                                type="button"
                                onClick={() => handleSelect(result.href)}
                                className="w-full flex flex-col gap-0.5 px-4 py-3 text-left hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0"
                            >
                                <span className="text-sm font-medium text-slate-800 line-clamp-1">
                                    {result.title}
                                </span>
                                <span className="text-xs text-slate-400 line-clamp-1">
                                    {result.subtitle}
                                </span>
                            </button>
                        ))}
                    </div>
                );
            })}

            {!loading && !isEmpty && onViewAll && (
                <div className="border-t border-slate-100 sticky bottom-0 bg-white">
                    <button
                        type="button"
                        onClick={onViewAll}
                        className="w-full text-center text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 py-3 transition-colors"
                    >
                        See all results for "{query}" →
                    </button>
                </div>
            )}
        </div>
    );
}
