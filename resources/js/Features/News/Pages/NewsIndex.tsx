import { useRef } from "react";
import { Head, Link, usePage } from "@inertiajs/react";
import MainLayout from "@/Features/Landing/Layouts/MainLayout";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Image } from "@/Core/Components/Common/Image";
import { Text } from "@/Core/Components/Common/Text";
import Badge from "@/Core/Components/Shared/Badge/Badge";
import NewsCard from "@/Features/News/Components/NewsCatalogue/fragments/NewsCard";
import { useNewsIndexAnimation } from "@/Features/News/Hooks/useNewsIndexAnimation";
import { type NewsListItem } from "@/Features/News/Types/news.type";

interface NewsIndexProps {
    articles: NewsListItem[];
    [key: string]: unknown;
}

export default function NewsIndex() {
    const { articles } = usePage<NewsIndexProps>().props;
    const [featured, ...rest] = articles;
    const containerRef = useRef<HTMLElement>(null);
    useNewsIndexAnimation(containerRef);

    return (
        <>
            <Head title="Kabar Laboratorium" />
            <MainLayout>
                <Box as="section" ref={containerRef} className="relative">
                    {/* Masthead — navy, doubles as the fixed navbar's native dark backdrop */}
                    <Box
                        as="header"
                        className="relative bg-primary-950 pt-28 md:pt-36 pb-24 md:pb-32 px-6 md:px-12 overflow-hidden"
                    >
                        <Box
                            aria-hidden="true"
                            className="absolute inset-0 honeycomb-dark opacity-[0.05] pointer-events-none"
                        />

                        <Box className="news-index-masthead relative z-10 max-w-6xl mx-auto">
                            <Box className="flex items-center gap-3 mb-6">
                                <Box className="w-8 h-px bg-secondary-500/60" />
                                <Text
                                    as="span"
                                    className="text-[0.68rem] font-body font-semibold tracking-[0.3em] uppercase text-secondary-400/80"
                                >
                                    Kabar Laboratorium
                                </Text>
                            </Box>

                            <Heading level={1} className="font-display tracking-tight text-balance">
                                <Text
                                    as="span"
                                    className="block text-4xl md:text-6xl leading-[1.05] font-extrabold text-white"
                                >
                                    Liputan
                                </Text>
                                <Text
                                    as="span"
                                    className="block text-4xl md:text-6xl leading-[1.05] font-light italic text-secondary-400"
                                >
                                    kegiatan lab.
                                </Text>
                            </Heading>

                            <Text className="mt-6 text-lg md:text-xl font-body text-white/70 max-w-[60ch] leading-relaxed text-pretty">
                                Workshop, kunjungan, prestasi, dan kolaborasi — dari balik meja
                                laboratorium.
                            </Text>
                        </Box>
                    </Box>

                    {/* Featured lead — the elevated card breaches the navy → light seam */}
                    {featured && (
                        <Box className="news-index-lead relative px-6 md:px-12 -mt-16 md:-mt-24">
                            <Link
                                href={featured.href}
                                className="group block max-w-6xl mx-auto rounded-2xl overflow-hidden shadow-card-elevated bg-white focus-visible:outline-2 focus-visible:outline-secondary-500 focus-visible:outline-offset-4"
                            >
                                <Box className="grid lg:grid-cols-[7fr_5fr]">
                                    <Box className="relative aspect-3/2 lg:aspect-auto overflow-hidden">
                                        <Box className="absolute inset-0 transition-transform duration-500 ease-[cubic-bezier(0.25,1,0.5,1)] group-hover:scale-[1.04]">
                                            <Image
                                                src={featured.image}
                                                alt={featured.imageAlt}
                                                className="absolute inset-0 w-full h-full"
                                                objectFit="cover"
                                                priority="eager"
                                            />
                                        </Box>
                                    </Box>
                                    <Box className="flex flex-col justify-center p-8 md:p-10">
                                        <Box className="flex items-center gap-3">
                                            <Badge variant="tag">{featured.category}</Badge>
                                            <Text as="span" className="text-xs font-body text-primary-900/50">
                                                {featured.date}
                                            </Text>
                                        </Box>
                                        <Text className="mt-4 font-display font-extrabold text-2xl md:text-3xl leading-snug text-primary-950 text-balance transition-colors duration-300 group-hover:text-primary-700">
                                            {featured.title}
                                        </Text>
                                        <Text className="mt-3 text-sm md:text-base font-body text-primary-900/70 leading-relaxed line-clamp-3 text-pretty">
                                            {featured.excerpt}
                                        </Text>
                                        <Text
                                            as="span"
                                            className="mt-5 inline-flex items-center gap-2 font-body font-semibold text-sm text-primary-700"
                                        >
                                            Baca selengkapnya
                                            <Text
                                                as="span"
                                                aria-hidden="true"
                                                className="transition-transform duration-300 group-hover:translate-x-1"
                                            >
                                                &rarr;
                                            </Text>
                                        </Text>
                                    </Box>
                                </Box>
                            </Link>
                        </Box>
                    )}

                    {/* Light body — the rest of the catalogue */}
                    <Box className="relative bg-surface-base pt-20 md:pt-24 pb-24 md:pb-32 px-6 md:px-12">
                        {rest.length > 0 && (
                            <Box
                                className="news-index-grid max-w-6xl mx-auto grid gap-x-5 gap-y-10"
                                style={{ gridTemplateColumns: "repeat(auto-fit, minmax(260px, 1fr))" }}
                            >
                                {rest.map((article) => (
                                    <Box key={article.slug} className="news-index-card">
                                        <NewsCard article={article} />
                                    </Box>
                                ))}
                            </Box>
                        )}
                    </Box>
                </Box>
            </MainLayout>
        </>
    );
}
