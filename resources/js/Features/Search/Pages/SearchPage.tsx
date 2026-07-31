import { useState } from "react";
import { router, usePage } from "@inertiajs/react";
import {
    Search,
    BookOpen,
    FolderOpen,
    ShoppingBag,
    Wrench,
    GraduationCap,
    ArrowRight,
    SearchX,
} from "lucide-react";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";

interface SearchResult {
    type: "training" | "publication" | "project" | "product" | "service";
    title: string;
    subtitle: string;
    href: string;
}

interface SearchPageProps {
    query: string;
    results: SearchResult[];
}

const TYPE_META: Record<
    SearchResult["type"],
    { label: string; icon: React.ElementType; color: string; bg: string }
> = {
    training:    { label: "Training",     icon: GraduationCap, color: "text-teal-600",   bg: "bg-teal-50" },
    publication: { label: "Publications", icon: BookOpen,       color: "text-blue-600",   bg: "bg-blue-50" },
    project:     { label: "Projects",     icon: FolderOpen,     color: "text-emerald-600", bg: "bg-emerald-50" },
    product:     { label: "Products",     icon: ShoppingBag,    color: "text-amber-600",   bg: "bg-amber-50" },
    service:     { label: "Services",     icon: Wrench,         color: "text-violet-600",  bg: "bg-violet-50" },
};

const TYPE_ORDER: SearchResult["type"][] = ["training", "publication", "project", "product", "service"];

export default function SearchPage() {
    const { query: initialQuery, results } = usePage<{ props: SearchPageProps }>().props as unknown as SearchPageProps;
    const [inputValue, setInputValue] = useState(initialQuery);

    const grouped = TYPE_ORDER.reduce<Record<string, SearchResult[]>>((acc, type) => {
        const items = results.filter((r) => r.type === type);
        if (items.length > 0) acc[type] = items;
        return acc;
    }, {});

    const hasResults = results.length > 0;

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        if (inputValue.trim().length < 2) return;
        router.get("/search", { q: inputValue.trim() }, { preserveScroll: false });
    };

    return (
        <DashboardLayout>
            <div className="max-w-4xl mx-auto space-y-8">
                {/* Search bar */}
                <form onSubmit={handleSearch} className="relative">
                    <div className="relative">
                        <Search className="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400 pointer-events-none" />
                        <input
                            type="text"
                            value={inputValue}
                            onChange={(e) => setInputValue(e.target.value)}
                            placeholder="Search training, publications, projects, services, products…"
                            className="w-full pl-12 pr-32 py-3.5 rounded-2xl border border-slate-200 bg-white shadow-sm text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition"
                            autoFocus
                        />
                        <button
                            type="submit"
                            className="absolute right-2 top-1/2 -translate-y-1/2 px-5 py-2 bg-[#082A55] text-white text-sm font-semibold rounded-xl hover:bg-[#0A3D7A] transition-colors"
                        >
                            Search
                        </button>
                    </div>
                </form>

                {/* Header */}
                {initialQuery && (
                    <div>
                        <h1 className="text-xl font-bold text-slate-800">
                            {hasResults
                                ? `${results.length} result${results.length !== 1 ? "s" : ""} for "${initialQuery}"`
                                : `No results for "${initialQuery}"`}
                        </h1>
                        <p className="text-sm text-slate-500 mt-1">
                            {hasResults
                                ? "Showing matches across training, publications, projects, products, and services."
                                : "Try a different keyword or browse the sections from the sidebar."}
                        </p>
                    </div>
                )}

                {/* Empty state */}
                {initialQuery && !hasResults && (
                    <div className="flex flex-col items-center justify-center py-20 text-center">
                        <div className="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                            <SearchX className="h-8 w-8 text-slate-400" />
                        </div>
                        <p className="text-slate-500 text-sm max-w-sm">
                            We couldn't find anything matching <span className="font-semibold text-slate-700">"{initialQuery}"</span>.
                            Try broader terms or check for typos.
                        </p>
                    </div>
                )}

                {/* No query yet */}
                {!initialQuery && (
                    <div className="flex flex-col items-center justify-center py-20 text-center">
                        <div className="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                            <Search className="h-8 w-8 text-slate-400" />
                        </div>
                        <p className="text-slate-500 text-sm">
                            Type at least 2 characters to start searching.
                        </p>
                    </div>
                )}

                {/* Results grouped by type */}
                {hasResults && Object.entries(grouped).map(([type, items]) => {
                    const meta = TYPE_META[type as SearchResult["type"]];
                    const Icon = meta.icon;

                    return (
                        <section key={type}>
                            {/* Section header */}
                            <div className="flex items-center gap-2.5 mb-3">
                                <div className={`w-7 h-7 rounded-lg ${meta.bg} flex items-center justify-center`}>
                                    <Icon className={`h-4 w-4 ${meta.color}`} />
                                </div>
                                <h2 className="text-sm font-bold text-slate-700 uppercase tracking-widest">
                                    {meta.label}
                                </h2>
                                <span className="text-xs text-slate-400 font-medium">({items.length})</span>
                            </div>

                            {/* Cards */}
                            <div className="grid gap-2">
                                {items.map((result) => (
                                    <button
                                        key={result.href}
                                        type="button"
                                        onClick={() => router.visit(result.href)}
                                        className="w-full flex items-center gap-4 px-5 py-4 bg-white border border-slate-200 rounded-2xl text-left hover:border-indigo-300 hover:shadow-sm transition-all group"
                                    >
                                        <div className={`shrink-0 w-9 h-9 rounded-xl ${meta.bg} flex items-center justify-center`}>
                                            <Icon className={`h-4.5 w-4.5 ${meta.color}`} />
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <p className="text-sm font-semibold text-slate-800 line-clamp-1 group-hover:text-indigo-700 transition-colors">
                                                {result.title}
                                            </p>
                                            {result.subtitle && (
                                                <p className="text-xs text-slate-400 line-clamp-1 mt-0.5">
                                                    {result.subtitle}
                                                </p>
                                            )}
                                        </div>
                                        <ArrowRight className="h-4 w-4 text-slate-300 group-hover:text-indigo-400 shrink-0 transition-colors" />
                                    </button>
                                ))}
                            </div>
                        </section>
                    );
                })}
            </div>
        </DashboardLayout>
    );
}
