import { Link } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import type { ArticleEntry } from "../../../Types/articlesSection.type";

interface ArticleIndexRowProps {
    entry: ArticleEntry;
}

/**
 * One ruled entry of the journal index. The wrapper Box takes the GSAP
 * reveal; the Link and its children keep the CSS hover transitions.
 */
export default function ArticleIndexRow({ entry }: ArticleIndexRowProps) {
    return (
        <Box className="articles-row">
            <Box
                aria-hidden="true"
                className="articles-row-rule h-px bg-primary-900/10 origin-left"
            />
            <Link
                href={entry.href}
                className="group grid grid-cols-[1fr_auto] items-start gap-4 py-5 focus-visible:outline-2 focus-visible:outline-secondary-500 focus-visible:outline-offset-4 rounded-sm"
            >
                <Box>
                    <Text className="font-display font-bold text-lg md:text-xl leading-snug text-primary-950 line-clamp-2 text-pretty transition-colors duration-300 group-hover:text-primary-700">
                        {entry.title}
                    </Text>
                    <Text className="mt-1.5 text-sm font-body text-[#062e5c]/70 line-clamp-1">
                        {entry.author} · {entry.category} · {entry.year}
                    </Text>
                </Box>
                <Text
                    as="span"
                    aria-hidden="true"
                    className="text-lg leading-none text-primary-700 pt-1 transition-transform duration-300 ease-[cubic-bezier(0.25,1,0.5,1)] group-hover:translate-x-0.5 group-hover:-translate-y-0.5"
                >
                    ↗
                </Text>
            </Link>
        </Box>
    );
}
