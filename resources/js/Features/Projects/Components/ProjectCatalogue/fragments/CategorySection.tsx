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
            className={cn(isEven ? "bg-slate-100 -mx-6 px-6 py-5" : "")}
        >
            <Heading
                level={3}
                className="font-display text-base font-semibold text-slate-800 mb-4"
            >
                {category.label}
            </Heading>

            {/* Bento grid: 2-col mobile → 4-col desktop; cards carry their own span */}
            <Box className="grid grid-cols-2 md:grid-cols-4 gap-x-5 gap-y-6 items-start">
                {category.cards.map((card) => (
                    <Box
                        key={card.id}
                        className={cn(
                            card.span === 2
                                ? "col-span-2"
                                : "col-span-1",
                        )}
                    >
                        <ProjectCard card={card} />
                    </Box>
                ))}
            </Box>
        </Box>
    );
}
