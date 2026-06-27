import { User, Calendar, Eye, Tag, Link as LinkIcon } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import Badge from "@/Core/Components/Shared/Badge/Badge";
import { type PublicationDetail } from "@/Features/Publications/Types/publication.type";

interface PublicationInfoProps {
    publication: PublicationDetail;
}

function formatDate(iso: string): string {
    return new Date(iso).toLocaleDateString("en-GB", {
        day: "numeric",
        month: "long",
        year: "numeric",
    });
}

function formatViews(n: number): string {
    return n >= 1000 ? `${(n / 1000).toFixed(1)}k` : String(n);
}

export default function PublicationInfo({ publication }: PublicationInfoProps) {
    return (
        <Box className="flex flex-col gap-4">
            {/* ── Title + category ── */}
            <Box>
                <Badge variant="tag" className="mb-2">
                    {publication.category}
                </Badge>
                <Heading
                    level={2}
                    className="font-display text-2xl font-bold text-slate-900 leading-snug"
                >
                    {publication.title}
                </Heading>
            </Box>

            {/* ── Meta row ── */}
            <Box className="flex flex-wrap gap-4 text-xs text-slate-500 border-b border-slate-100 pb-4">
                <Box className="flex items-center gap-1.5">
                    <User className="h-3.5 w-3.5 shrink-0" />
                    <Text as="span">{publication.author}</Text>
                </Box>
                <Box className="flex items-center gap-1.5">
                    <Calendar className="h-3.5 w-3.5 shrink-0" />
                    <Text as="span">{formatDate(publication.publishedAt)}</Text>
                </Box>
                <Box className="flex items-center gap-1.5">
                    <Eye className="h-3.5 w-3.5 shrink-0" />
                    <Text as="span">{formatViews(publication.viewCount)} views</Text>
                </Box>
            </Box>

            {/* ── Abstract ── */}
            <Box className="rounded-2xl border border-slate-200 p-5 bg-white">
                <Heading
                    level={4}
                    className="font-display text-sm font-bold text-slate-700 uppercase tracking-wide mb-2"
                >
                    Abstract
                </Heading>
                <Text className="text-sm text-slate-600 leading-relaxed">
                    {publication.abstract}
                </Text>
            </Box>

            {/* ── Description paragraphs ── */}
            {publication.description.length > 0 && (
                <Box className="rounded-2xl border border-slate-200 p-5 bg-white flex flex-col gap-3">
                    <Heading
                        level={4}
                        className="font-display text-sm font-bold text-slate-700 uppercase tracking-wide"
                    >
                        Full Description
                    </Heading>
                    {publication.description.map((para, i) => (
                        <Text key={i} className="text-sm text-slate-600 leading-relaxed">
                            {para}
                        </Text>
                    ))}
                </Box>
            )}

            {/* ── Keywords ── */}
            {publication.keywords.length > 0 && (
                <Box className="rounded-2xl border border-slate-200 p-5 bg-white">
                    <Box className="flex items-center gap-1.5 mb-3">
                        <Tag className="h-3.5 w-3.5 text-slate-400 shrink-0" />
                        <Heading
                            level={4}
                            className="font-display text-sm font-bold text-slate-700 uppercase tracking-wide"
                        >
                            Keywords
                        </Heading>
                    </Box>
                    <Box className="flex flex-wrap gap-2">
                        {publication.keywords.map((kw) => (
                            <Badge key={kw} variant="neutral">
                                {kw}
                            </Badge>
                        ))}
                    </Box>
                </Box>
            )}

            {/* ── DOI / Reference ── */}
            {publication.doi && (
                <Box className="rounded-2xl border border-slate-200 p-5 bg-white">
                    <Box className="flex items-center gap-1.5 mb-2">
                        <LinkIcon className="h-3.5 w-3.5 text-slate-400 shrink-0" />
                        <Heading
                            level={4}
                            className="font-display text-sm font-bold text-slate-700 uppercase tracking-wide"
                        >
                            DOI
                        </Heading>
                    </Box>
                    <a
                        href={`https://doi.org/${publication.doi}`}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="text-xs text-primary-700 hover:underline break-all"
                    >
                        {publication.doi}
                    </a>
                </Box>
            )}
        </Box>
    );
}
