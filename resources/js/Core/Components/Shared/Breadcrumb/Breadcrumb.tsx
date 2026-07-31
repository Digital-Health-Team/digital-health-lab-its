import { Link } from "@inertiajs/react";
import { ChevronRight } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { cn } from "@/Core/Utils/utils";

export interface Crumb {
    label: string;
    /** Omit on the last crumb — the current page is not a link. */
    href?: string;
}

interface BreadcrumbProps {
    items: Crumb[];
    className?: string;
}

export default function Breadcrumb({ items, className }: BreadcrumbProps) {
    return (
        <Box
            as="nav"
            aria-label="Breadcrumb"
            className={cn("mb-4 flex flex-wrap items-center gap-1", className)}
        >
            {items.map((item, i) => {
                const isLast = i === items.length - 1;

                return (
                    <Box key={`${item.label}-${i}`} className="flex items-center gap-1">
                        {i > 0 && (
                            <ChevronRight
                                className="h-3 w-3 shrink-0 text-slate-300"
                                aria-hidden="true"
                            />
                        )}
                        {isLast || !item.href ? (
                            <Text
                                as="span"
                                aria-current={isLast ? "page" : undefined}
                                className="text-xs font-medium text-slate-700"
                            >
                                {item.label}
                            </Text>
                        ) : (
                            <Link
                                href={item.href}
                                className="rounded text-xs text-slate-500 transition-colors hover:text-primary-700 focus-visible:ring-2 focus-visible:ring-secondary-500/50 focus-visible:outline-none"
                            >
                                {item.label}
                            </Link>
                        )}
                    </Box>
                );
            })}
        </Box>
    );
}
