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

            {/* Single-line horizontal scroll rail — cards carry their own width via span */}
            <Box className="-mx-4 pl-4 pr-0 sm:-mx-6 sm:px-6 overflow-x-auto scrollbar-none pb-1">
                <Box className="flex gap-4 sm:gap-5 min-w-max">
                    {category.cards.map((card) => (
                        <Box
                            key={card.id}
                            className={cn(
                                "shrink-0",
                                card.span === 2 ? "w-80 sm:w-96" : "w-40 sm:w-48",
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
