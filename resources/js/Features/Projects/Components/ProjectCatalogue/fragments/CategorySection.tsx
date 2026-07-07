import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { cn } from "@/Core/Utils/utils";
import ProjectCard from "./ProjectCard";
import { type ProjectCategory } from "@/Features/Projects/Types/project.type";

interface CategorySectionProps {
    category: ProjectCategory;
    isEven?: boolean;
}

export default function CategorySection({ category, isEven = false }: CategorySectionProps) {
    return (
        <Box
            as="section"
            className={cn(isEven ? "bg-slate-100 -mx-4 px-4 sm:-mx-6 sm:px-6 py-5" : "")}
        >
            <Heading
                level={3}
                className="font-display text-base font-semibold text-slate-800 mb-4"
            >
                {category.label}
            </Heading>

            {/* Horizontal scroll rail below lg (space-constrained); fluid bento grid at lg+ (space abundant) — same convention as CategoryQuickAccess */}
            <Box className="-mx-4 pl-4 pr-0 sm:-mx-6 sm:px-6 lg:mx-0 lg:px-0 overflow-x-auto lg:overflow-visible scrollbar-none pb-1 lg:pb-0">
                <Box className="flex gap-4 sm:gap-5 min-w-max lg:grid lg:grid-cols-4 lg:min-w-0 lg:w-full">
                    {category.cards.map((card) => (
                        <Box
                            key={card.id}
                            className={cn(
                                "shrink-0 lg:shrink lg:w-auto",
                                card.span === 2 ? "w-80 sm:w-96 lg:col-span-2" : "w-40 sm:w-48 lg:col-span-1",
                            )}
                        >
                            <ProjectCard card={card} />
                        </Box>
                    ))}
                </Box>
            </Box>
        </Box>
    );
}
