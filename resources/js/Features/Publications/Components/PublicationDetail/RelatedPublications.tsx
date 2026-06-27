import { Link } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import PublicationCard from "@/Features/Publications/Components/PublicationCatalogue/fragments/PublicationCard";
import { type PublicationListItem } from "@/Features/Publications/Types/publication.type";

interface RelatedPublicationsProps {
    related: PublicationListItem[];
}

export default function RelatedPublications({ related }: RelatedPublicationsProps) {
    if (related.length === 0) return null;

    return (
        <Box as="section" className="mt-2">
            {/* Section header */}
            <Box className="flex items-end justify-between mb-6">
                <Box>
                    <Heading
                        level={2}
                        className="font-display text-xl font-bold text-slate-800 leading-tight"
                    >
                        Related Publications
                    </Heading>
                    <Text className="text-xs text-slate-400 mt-0.5">
                        More from the ITS Medical Technology repository
                    </Text>
                </Box>

                <Link
                    href="/publications"
                    className="shrink-0 px-4 py-1.5 rounded-full border border-primary-700 text-primary-700 text-xs font-semibold hover:bg-primary-700 hover:text-white transition-colors"
                >
                    View All
                </Link>
            </Box>

            {/* 4-col card grid */}
            <Box className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-x-5 gap-y-8">
                {related.map((pub) => (
                    <PublicationCard key={pub.id} publication={pub} />
                ))}
            </Box>
        </Box>
    );
}
