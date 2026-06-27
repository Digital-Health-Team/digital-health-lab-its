import { useState } from "react";
import { BookOpen } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { SearchInput } from "@/Core/Components/Shared/Input/SearchInput";
import { cn } from "@/Core/Utils/utils";
import PublicationRowCard from "./fragments/PublicationRowCard";
import { type PublicationListItem } from "@/Features/Publications/Types/publication.type";

interface PublicationCatalogueProps {
    publications: PublicationListItem[];
}

const ALL_TAB = "All";

type SortKey = "newest" | "views";

export default function PublicationCatalogue({ publications }: PublicationCatalogueProps) {
    const [activeTab, setActiveTab] = useState<string>(ALL_TAB);
    const [query, setQuery] = useState<string>("");
    const [sort, setSort] = useState<SortKey>("newest");

    const categories = [ALL_TAB, ...Array.from(new Set(publications.map((p) => p.category)))];

    const filtered = publications
        .filter((p) => {
            const matchesTab = activeTab === ALL_TAB || p.category === activeTab;
            const matchesSearch =
                query.trim() === "" ||
                p.title.toLowerCase().includes(query.toLowerCase()) ||
                p.author.toLowerCase().includes(query.toLowerCase());
            return matchesTab && matchesSearch;
        })
        .sort((a, b) => {
            if (sort === "newest") {
                return new Date(b.publishedAt).getTime() - new Date(a.publishedAt).getTime();
            }
            return b.viewCount - a.viewCount;
        });

    return (
        <Box as="section">
            <Box className="rounded-2xl border border-slate-200 overflow-hidden">

                {/* ── Zone 1: Institutional title strip ── */}
                <Box className="bg-primary-900 px-6 py-5 flex items-baseline justify-between gap-4">
                    <Heading
                        level={4}
                        className="font-display text-xl font-bold text-white shrink-0"
                    >
                        All Publications
                    </Heading>
                    <Text className="text-sm text-white/50 shrink-0 tabular-nums">
                        <Text as="span" className="text-white font-semibold">
                            {filtered.length}
                        </Text>
                        {" "}of {publications.length}
                    </Text>
                </Box>

                {/* ── Zone 2: Filter controls (search + sort) ── */}
                <Box className="bg-white px-6 py-3 border-b border-slate-100 flex items-center gap-3">
                    <Box className="flex-1 min-w-0">
                        <SearchInput
                            placeholder="Search by title or author…"
                            value={query}
                            onChange={(e) => setQuery(e.target.value)}
                            onClear={() => setQuery("")}
                        />
                    </Box>

                    <Box className="flex items-center gap-0.5 p-0.5 bg-slate-100 rounded-lg shrink-0">
                        {(["newest", "views"] as SortKey[]).map((key) => (
                            <button
                                key={key}
                                type="button"
                                onClick={() => setSort(key)}
                                className={cn(
                                    "px-3 py-1.5 rounded-md text-xs font-medium transition-all whitespace-nowrap",
                                    sort === key
                                        ? "bg-white text-slate-800 shadow-sm"
                                        : "text-slate-500 hover:text-slate-700",
                                )}
                            >
                                {key === "newest" ? "Newest" : "Most viewed"}
                            </button>
                        ))}
                    </Box>
                </Box>

                {/* ── Zone 3: Category tabs (tightest band) ── */}
                <Box className="bg-slate-50 px-6 py-2.5 border-b border-slate-100">
                    <Box className="flex gap-1.5 overflow-x-auto scrollbar-none">
                        {categories.map((tab) => (
                            <button
                                key={tab}
                                type="button"
                                onClick={() => setActiveTab(tab)}
                                className={cn(
                                    "shrink-0 px-3.5 py-1 rounded-full text-xs font-semibold transition-colors cursor-pointer whitespace-nowrap",
                                    activeTab === tab
                                        ? "bg-primary-800 text-white shadow-sm"
                                        : "bg-white border border-slate-200 text-slate-600 hover:border-primary-700 hover:text-primary-700",
                                )}
                            >
                                {tab}
                            </button>
                        ))}
                    </Box>
                </Box>

                {/* ── Row list ── */}
                {filtered.length > 0 ? (
                    <Box className="bg-white divide-y divide-slate-100 px-6">
                        {filtered.map((pub, i) => (
                            <PublicationRowCard key={pub.id} publication={pub} index={i} />
                        ))}
                    </Box>
                ) : (
                    <Box className="bg-white flex flex-col items-center justify-center py-20 text-center px-6">
                        <BookOpen className="h-10 w-10 text-slate-300 mb-3" />
                        <Text className="text-slate-500 font-medium">No publications found</Text>
                        <Text className="text-xs text-slate-400 mt-1">
                            Try a different search term or category.
                        </Text>
                    </Box>
                )}
            </Box>
        </Box>
    );
}
