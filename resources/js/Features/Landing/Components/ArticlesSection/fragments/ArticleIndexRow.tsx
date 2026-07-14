import { Link } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Image } from "@/Core/Components/Common/Image";
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
                className="group grid grid-cols-[auto_1fr_auto] items-center gap-4 sm:gap-5 py-5 -mx-3 px-3 sm:-mx-4 sm:px-4 rounded-lg transition-colors duration-300 hover:bg-primary-900/[0.03] focus-visible:outline-2 focus-visible:outline-secondary-500 focus-visible:outline-offset-4"
            >
                <Box className="relative shrink-0 w-20 h-20 sm:w-24 sm:h-24 rounded-lg overflow-hidden transition-transform duration-500 ease-[cubic-bezier(0.25,1,0.5,1)] group-hover:scale-[1.06]">
                    <Image
                        src={entry.image}
                        alt={entry.imageAlt}
                        className="absolute inset-0 w-full h-full"
                        objectFit="cover"
                    />
                </Box>
                <Box className="min-w-0">
                    <Text
                        as="span"
                        className="text-[0.68rem] font-body font-semibold tracking-[0.15em] uppercase text-primary-700/70"
                    >
                        {entry.date} · {entry.category}
                    </Text>
                    <Text className="mt-1.5 font-display font-bold text-lg md:text-xl leading-snug text-primary-950 line-clamp-2 text-pretty transition-colors duration-300 group-hover:text-primary-700">
                        {entry.title}
                    </Text>
                    <Text className="mt-1.5 text-sm font-body text-primary-900/70 line-clamp-1">
                        {entry.excerpt}
                    </Text>
                </Box>
                <Text
                    as="span"
                    aria-hidden="true"
                    className="text-lg leading-none text-primary-700 transition-transform duration-300 ease-[cubic-bezier(0.25,1,0.5,1)] group-hover:translate-x-0.5 group-hover:-translate-y-0.5"
                >
                    ↗
                </Text>
            </Link>
        </Box>
    );
}
