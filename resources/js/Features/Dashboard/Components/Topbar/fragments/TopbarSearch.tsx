import { useState, useEffect, useRef, useCallback } from "react";
import { router } from "@inertiajs/react";
import { SearchInput } from "@/Core/Components/Shared";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { search as searchRoute } from "@/routes";
import TopbarSearchResults, { type SearchResult } from "./TopbarSearchResults";

export default function TopbarSearch() {
    const [query, setQuery] = useState("");
    const [results, setResults] = useState<SearchResult[]>([]);
    const [loading, setLoading] = useState(false);
    const [open, setOpen] = useState(false);
    const containerRef = useRef<HTMLDivElement>(null);
    const debounceRef = useRef<ReturnType<typeof setTimeout> | null>(null);
    const { t } = useTranslation();

    const fetchResults = useCallback(async (q: string) => {
        if (q.length < 2) {
            setResults([]);
            setOpen(false);
            return;
        }

        setLoading(true);
        setOpen(true);

        try {
            const url = searchRoute.url({ query: { q } });
            const res = await fetch(url, {
                headers: { Accept: "application/json" },
            });
            const data = await res.json();
            setResults(data.results ?? []);
        } catch {
            setResults([]);
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => {
        if (debounceRef.current) clearTimeout(debounceRef.current);
        debounceRef.current = setTimeout(() => fetchResults(query), 300);
        return () => { if (debounceRef.current) clearTimeout(debounceRef.current); };
    }, [query, fetchResults]);

    // Close on click-outside
    useEffect(() => {
        const handler = (e: MouseEvent) => {
            if (containerRef.current && !containerRef.current.contains(e.target as Node)) {
                setOpen(false);
            }
        };
        document.addEventListener("mousedown", handler);
        return () => document.removeEventListener("mousedown", handler);
    }, []);

    const handleViewAll = useCallback(() => {
        setOpen(false);
        router.visit(`/search?q=${encodeURIComponent(query)}`);
    }, [query]);

    const handleKeyDown = (e: React.KeyboardEvent<HTMLInputElement>) => {
        if (e.key === "Escape") {
            setOpen(false);
        }
        if (e.key === "Enter" && query.length >= 2) {
            e.preventDefault();
            handleViewAll();
        }
    };

    const handleClear = () => {
        setQuery("");
        setResults([]);
        setOpen(false);
    };

    return (
        <div ref={containerRef} data-tour="user-search" className="relative flex-1 max-w-2xl mx-auto px-4">
            <SearchInput
                value={query}
                onChange={(e) => setQuery(e.target.value)}
                onClear={handleClear}
                placeholder={t("Search publications, products, services...")}
                aria-label={t("Search")}
                onKeyDown={handleKeyDown}
                onFocus={() => query.length >= 2 && setOpen(true)}
            />

            {open && (
                <TopbarSearchResults
                    query={query}
                    results={results}
                    loading={loading}
                    onClose={() => setOpen(false)}
                    onViewAll={handleViewAll}
                />
            )}
        </div>
    );
}
