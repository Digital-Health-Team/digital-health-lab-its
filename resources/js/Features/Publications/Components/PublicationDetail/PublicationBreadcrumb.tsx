import { Link } from "@inertiajs/react";
import { ChevronRight } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";

interface PublicationBreadcrumbProps {
    title: string;
}

export default function PublicationBreadcrumb({ title }: PublicationBreadcrumbProps) {
    const crumbs = [
        { label: "Home", href: "/" },
        { label: "Publications", href: "/publications" },
        { label: title },
    ];

    return (
        <Box className="flex items-center gap-1 flex-wrap mb-4">
            {crumbs.map((crumb, i) => {
                const isLast = i === crumbs.length - 1;
                return (
                    <Box key={i} className="flex items-center gap-1">
                        {i > 0 && (
                            <ChevronRight className="h-3 w-3 text-slate-300 shrink-0" />
                        )}
                        {isLast ? (
                            <Text
                                as="span"
                                className="text-xs font-medium text-slate-700 line-clamp-1 max-w-[200px]"
                            >
                                {crumb.label}
                            </Text>
                        ) : (
                            <Link
                                href={crumb.href!}
                                className="text-xs text-slate-400 hover:text-primary-700 transition-colors"
                            >
                                {crumb.label}
                            </Link>
                        )}
                    </Box>
                );
            })}
        </Box>
    );
}
