import { useState } from "react";
import { SearchInput } from "@/Core/Components/Shared";

// TODO(v2): wire to GlobalSearchAction
export default function TopbarSearch() {
    const [query, setQuery] = useState("");

    return (
        <div className="flex-1 max-w-2xl mx-auto px-4">
            <SearchInput
                value={query}
                onChange={(e) => setQuery(e.target.value)}
                onClear={() => setQuery("")}
                placeholder="Search publications, products, services..."
                aria-label="Search"
                onKeyDown={(e) => e.key === "Enter" && e.preventDefault()}
            />
        </div>
    );
}
