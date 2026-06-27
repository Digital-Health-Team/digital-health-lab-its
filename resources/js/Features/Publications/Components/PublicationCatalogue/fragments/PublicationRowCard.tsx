import { Link } from "@inertiajs/react";
import { ArrowUpRight, Eye, FileDown } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { type PublicationListItem } from "@/Features/Publications/Types/publication.type";

interface PublicationRowCardProps {
    publication: PublicationListItem;
    index: number;
}

function getYear(iso: string): string {
    return new Date(iso).getFullYear().toString();
}

function formatViews(n: number): string {
    return n >= 1000 ? `${(n / 1000).toFixed(1)}k` : String(n);
}

export default function PublicationRowCard({ publication, index }: PublicationRowCardProps) {
    const year = getYear(publication.publishedAt);

    return (
        <Link href={publication.href} className="block group">
            <Box className="relative flex gap-5 sm:gap-6 py-6 transition-colors hover:bg-surface-base -mx-6 px-6">

                {/* ── Ordinal ── */}
                <Text
                    as="span"
                    className="w-6 shrink-0 pt-1 text-sm font-semibold tabular-nums text-slate-300 text-right select-none"
                >
                    {String(index + 1).padStart(2, "0")}
                </Text>

                {/* ── Portrait thumbnail ── */}
                <Box className="shrink-0 w-24 sm:w-28 aspect-[3/4] rounded-md overflow-hidden ring-1 ring-primary-900/10">
                    {publication.thumbnailUrl ? (
                        <Box
                            className="w-full h-full"
                            style={{
                                backgroundImage: `url(${publication.thumbnailUrl})`,
                                backgroundSize: "cover",
                                backgroundPosition: "center top",
                            }}
                        />
                    ) : (
                        <Box className="w-full h-full bg-linear-to-br from-primary-900 to-primary-700" />
                    )}
                </Box>

                {/* ── Content ── */}
                <Box className="flex-1 min-w-0 flex flex-col">
                    {/* Category eyebrow */}
                    <Text
                        as="span"
                        className="text-[11px] font-medium uppercase tracking-[0.08em] text-slate-400 mb-1"
                    >
                        {publication.category}
                    </Text>

                    {/* Title — the hierarchy jump */}
                    <Text className="text-lg sm:text-xl font-bold text-primary-800 leading-snug line-clamp-2 transition-colors group-hover:text-secondary-500 group-hover:underline">
                        {publication.title}
                    </Text>

                    {/* Citation line */}
                    <Text className="text-sm text-slate-500 leading-relaxed mt-1.5">
                        {publication.author}
                        {publication.journal && (
                            <>
                                {" "}
                                <Text as="span" className="text-primary-700 font-medium">
                                    {publication.journal}.
                                </Text>
                            </>
                        )}
                        {" "}{year}.
                        {publication.pmid && (
                            <Text as="span" className="text-slate-400">
                                {" "}PMID:&nbsp;{publication.pmid}
                            </Text>
                        )}
                    </Text>

                    {/* Meta row — free marker + views + year */}
                    <Box className="flex flex-wrap items-center gap-x-3 gap-y-1 mt-3">
                        {publication.isFreeAccess && (
                            <Box className="inline-flex items-center gap-1 text-[11px] font-semibold text-secondary-500 bg-secondary-500/10 px-2 py-0.5 rounded-full">
                                <FileDown className="h-3 w-3 shrink-0" />
                                Free PDF
                            </Box>
                        )}
                        <Box className="flex items-center gap-1 text-xs text-slate-400">
                            <Eye className="h-3 w-3 shrink-0" />
                            <Text as="span">{formatViews(publication.viewCount)} views</Text>
                        </Box>
                        <Text as="span" className="text-xs text-slate-400 tabular-nums">
                            {year}
                        </Text>
                    </Box>
                </Box>

                {/* ── Affordance arrow ── */}
                <Box className="self-center shrink-0">
                    <ArrowUpRight className="h-4 w-4 text-slate-300 transition-all group-hover:text-secondary-500 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                </Box>
            </Box>
        </Link>
    );
}
