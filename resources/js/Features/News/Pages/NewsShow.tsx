import { useRef } from "react";
import { Head, Link, usePage } from "@inertiajs/react";
import { ChevronRight } from "lucide-react";
import MainLayout from "@/Features/Landing/Layouts/MainLayout";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Image } from "@/Core/Components/Common/Image";
import { Text } from "@/Core/Components/Common/Text";
import Badge from "@/Core/Components/Shared/Badge/Badge";
import { news } from "@/routes";
import NewsCard from "@/Features/News/Components/NewsCatalogue/fragments/NewsCard";
import { useNewsShowAnimation } from "@/Features/News/Hooks/useNewsShowAnimation";
import { type NewsDetail, type NewsListItem } from "@/Features/News/Types/news.type";

interface NewsShowProps {
    article: NewsDetail;
    related: NewsListItem[];
    [key: string]: unknown;
}

const WORDS_PER_MINUTE = 200;

// ponytail: word-count estimate, not a real reading-time model — good enough for a meta line.
function estimateReadingMinutes(paragraphs: string[]): number {
    const words = paragraphs.join(" ").trim().split(/\s+/).filter(Boolean).length;
    return Math.max(1, Math.round(words / WORDS_PER_MINUTE));
}

export default function NewsShow() {
    const { article, related } = usePage<NewsShowProps>().props;
    const containerRef = useRef<HTMLElement>(null);
    useNewsShowAnimation(containerRef);

    const readingMinutes = estimateReadingMinutes(article.body);
    const [lead, ...rest] = article.body;

    return (
        <>
            <Head title={article.title} />
            <MainLayout>
                <Box as="article" ref={containerRef} className="relative">
                    {/* Masthead — navy, doubles as the fixed navbar's native dark backdrop */}
                    <Box
                        as="header"
                        className="relative bg-primary-950 pt-28 md:pt-36 pb-24 md:pb-32 px-6 md:px-12 overflow-hidden"
                    >
                        <Box
                            aria-hidden="true"
                            className="absolute inset-0 honeycomb-dark opacity-[0.05] pointer-events-none"
                        />

                        <Box className="relative z-10 max-w-3xl mx-auto">
                            <Box className="news-show-masthead flex flex-col items-start">
                                <Box className="flex items-center gap-1.5 flex-wrap mb-8">
                                    <Link
                                        href="/"
                                        className="text-xs font-body text-white/60 hover:text-secondary-400 transition-colors"
                                    >
                                        Beranda
                                    </Link>
                                    <ChevronRight className="h-3 w-3 text-white/30 shrink-0" aria-hidden="true" />
                                    <Link
                                        href={news().url}
                                        className="text-xs font-body text-white/60 hover:text-secondary-400 transition-colors"
                                    >
                                        Kabar Laboratorium
                                    </Link>
                                    <ChevronRight className="h-3 w-3 text-white/30 shrink-0" aria-hidden="true" />
                                    <Text as="span" className="text-xs font-body text-white/40 line-clamp-1 max-w-[220px]">
                                        {article.title}
                                    </Text>
                                </Box>

                                <Badge
                                    variant="tag"
                                    className="bg-secondary-400/10 text-secondary-300 border-secondary-400/25 mb-5"
                                >
                                    {article.category}
                                </Badge>

                                <Heading
                                    level={1}
                                    className="font-display font-extrabold text-4xl sm:text-5xl md:text-6xl leading-[1.08] tracking-tight text-white text-balance"
                                >
                                    {article.title}
                                </Heading>

                                <Box className="news-show-meta flex items-center gap-3 mt-6 text-sm font-body text-white/60">
                                    <Text as="span">{article.date}</Text>
                                    <Text as="span" aria-hidden="true">
                                        &middot;
                                    </Text>
                                    <Text as="span">{readingMinutes} menit baca</Text>
                                </Box>
                            </Box>
                        </Box>
                    </Box>

                    {/* Hero image — bleeds across the navy → light seam */}
                    <Box className="relative px-6 md:px-12 -mt-16 md:-mt-24">
                        <Box className="news-show-hero relative max-w-5xl mx-auto aspect-3/2 sm:aspect-video rounded-2xl overflow-hidden shadow-card-elevated">
                            <Image
                                src={article.image}
                                alt={article.imageAlt}
                                className="absolute inset-0 w-full h-full"
                                objectFit="cover"
                                priority="eager"
                            />
                        </Box>
                    </Box>

                    {/* Light reading body */}
                    <Box className="relative bg-surface-base pt-16 md:pt-20 pb-24 md:pb-32 px-6 md:px-12">
                        <Box className="news-show-body max-w-[68ch] mx-auto flex flex-col gap-5">
                            <Text className="text-lg md:text-xl font-body font-medium text-primary-950 leading-relaxed text-pretty">
                                {lead}
                            </Text>
                            {rest.map((paragraph, i) => (
                                <Text
                                    key={i}
                                    className="text-base font-body text-primary-900/80 leading-relaxed text-pretty"
                                >
                                    {paragraph}
                                </Text>
                            ))}

                            <Link
                                href={news().url}
                                className="group inline-flex items-center gap-2 mt-4 font-body font-semibold text-sm text-primary-700 underline-offset-4 hover:underline w-fit focus-visible:outline-2 focus-visible:outline-secondary-500 focus-visible:outline-offset-4 rounded-sm"
                            >
                                <Text
                                    as="span"
                                    aria-hidden="true"
                                    className="transition-transform duration-300 group-hover:-translate-x-1"
                                >
                                    &larr;
                                </Text>
                                Kembali ke Kabar
                            </Link>
                        </Box>

                        {related.length > 0 && (
                            <Box className="news-show-related max-w-6xl mx-auto mt-20 md:mt-28 pt-16 border-t border-primary-900/10">
                                <Box className="flex items-center gap-3 mb-8">
                                    <Box className="w-8 h-px bg-secondary-500/60" />
                                    <Text
                                        as="span"
                                        className="text-[0.68rem] font-body font-semibold tracking-[0.3em] uppercase text-primary-700/80"
                                    >
                                        Kabar Lainnya
                                    </Text>
                                </Box>
                                <Box
                                    className="grid gap-x-5 gap-y-10"
                                    style={{ gridTemplateColumns: "repeat(auto-fit, minmax(260px, 1fr))" }}
                                >
                                    {related.map((item) => (
                                        <Box key={item.slug} className="news-show-related-card">
                                            <NewsCard article={item} />
                                        </Box>
                                    ))}
                                </Box>
                            </Box>
                        )}
                    </Box>
                </Box>
            </MainLayout>
        </>
    );
}
