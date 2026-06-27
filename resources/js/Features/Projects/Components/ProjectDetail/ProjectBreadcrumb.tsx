import { Link } from "@inertiajs/react";
import { ChevronRight } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";

interface ProjectBreadcrumbProps {
    breadcrumb: string[];
}

export default function ProjectBreadcrumb({ breadcrumb }: ProjectBreadcrumbProps) {
    const hrefFor = (index: number) => {
        if (index === 0) return "/";
        if (index === 1) return "/projects";
        return "/projects";
    };

    return (
        <Box className="flex items-center gap-1 flex-wrap mb-4">
            {breadcrumb.map((crumb, i) => {
                const isLast = i === breadcrumb.length - 1;
                return (
                    <Box key={i} className="flex items-center gap-1">
                        {i > 0 && (
                            <ChevronRight className="h-3 w-3 text-slate-300 shrink-0" />
                        )}
                        {isLast ? (
                            <Text
                                as="span"
                                className="text-xs font-medium text-slate-700"
                            >
                                {crumb}
                            </Text>
                        ) : (
                            <Link
                                href={hrefFor(i)}
                                className="text-xs text-slate-400 hover:text-primary-700 transition-colors"
                            >
                                {crumb}
                            </Link>
                        )}
                    </Box>
                );
            })}
        </Box>
    );
}
