import { Link } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { ChevronRight } from "lucide-react";
import ProjectCard from "@/Features/Projects/Components/ProjectCatalogue/fragments/ProjectCard";
import { type ProjectCard as ProjectCardType } from "@/Features/Projects/Types/project.type";

interface RelatedProjectsProps {
    related: ProjectCardType[];
}

export default function RelatedProjects({ related }: RelatedProjectsProps) {
    if (related.length === 0) return null;

    return (
        <Box className="space-y-4">
            {/* Section header */}
            <Box className="flex items-center justify-between">
                <Heading level={3} className="text-base font-bold text-slate-900">
                    More projects you might like
                </Heading>
                <Link
                    href="/research"
                    className="flex items-center gap-1 text-xs font-semibold text-secondary-600 hover:text-secondary-700 transition-colors"
                >
                    View all
                    <ChevronRight className="h-3.5 w-3.5" />
                </Link>
            </Box>

            {/* Grid */}
            <Box className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                {related.map((card) => (
                    <ProjectCard key={card.id} card={card} />
                ))}
            </Box>
        </Box>
    );
}
