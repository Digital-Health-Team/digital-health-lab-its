import { useState } from "react";
import { Box } from "@/Core/Components/Common/Box";
import { cn } from "@/Core/Utils/utils";
import CategorySection from "./fragments/CategorySection";
import { type ProjectCategory } from "@/Features/Projects/Types/project.type";

interface ProjectCatalogueProps {
    categories: ProjectCategory[];
}

const ALL_TAB = "All";

export default function ProjectCatalogue({ categories }: ProjectCatalogueProps) {
    const [activeTab, setActiveTab] = useState<string>(ALL_TAB);

    const tabs = [ALL_TAB, ...categories.map((c) => c.label)];

    const visibleCategories =
        activeTab === ALL_TAB
            ? categories
            : categories.filter((c) => c.label === activeTab);

    return (
        <Box>
            {/* Title comes from the page's section header */}

            {/* Pill tab bar */}
            <Box className="flex items-center justify-center mb-8">
                <Box className="flex gap-2 overflow-x-auto pb-1 scrollbar-none max-w-full">
                    {tabs.map((tab) => (
                        <button
                            key={tab}
                            type="button"
                            onClick={() => setActiveTab(tab)}
                            className={cn(
                                "shrink-0 px-4 py-1.5 rounded-full text-sm font-medium transition-colors cursor-pointer whitespace-nowrap",
                                activeTab === tab
                                    ? "bg-primary-700 text-white shadow-sm"
                                    : "bg-slate-200 text-slate-600 hover:bg-slate-300",
                            )}
                        >
                            {tab}
                        </button>
                    ))}
                </Box>
            </Box>

            {/* Category sections */}
            <Box className="space-y-10">
                {visibleCategories.map((category, index) => (
                    <CategorySection key={category.id} category={category} isEven={index % 2 !== 0} />
                ))}
            </Box>
        </Box>
    );
}
