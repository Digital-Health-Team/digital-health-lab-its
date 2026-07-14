import { Head, Link, usePage } from "@inertiajs/react";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Image } from "@/Core/Components/Common/Image";
import { Text } from "@/Core/Components/Common/Text";
import Badge from "@/Core/Components/Shared/Badge/Badge";
import NewsCard from "@/Features/News/Components/NewsCatalogue/fragments/NewsCard";
import { type NewsListItem } from "@/Features/News/Types/news.type";

interface NewsIndexProps {
    articles: NewsListItem[];
    [key: string]: unknown;
}

export default function NewsIndex() {
    const { articles } = usePage<NewsIndexProps>().props;
    const [featured, ...rest] = articles;

    return (
        <>
            <Head title="Kabar Laboratorium" />
            <DashboardLayout>
                <Box as="section">
                    <Text
                        as="span"
                        className="text-[0.68rem] font-body font-semibold tracking-[0.3em] uppercase text-primary-700/80"
                    >
                        Kabar Laboratorium
                    </Text>
                    <Heading
                        level={1}
                        className="mt-3 font-display text-3xl sm:text-4xl font-extrabold text-slate-900 leading-[1.1]"
                    >
                        Liputan kegiatan lab.
                    </Heading>
                    <Text className="mt-2 text-sm sm:text-base text-slate-500 max-w-[60ch]">
                        Workshop, kunjungan, prestasi, dan kolaborasi — dari balik meja
                        laboratorium.
                    </Text>
                </Box>

                {featured && (
                    <Link href={featured.href} className="group block">
                        <Box className="grid md:grid-cols-[7fr_5fr] gap-6 rounded-2xl border border-slate-200 overflow-hidden bg-white transition-colors duration-300 group-hover:border-primary-300">
                            <Box className="relative aspect-3/2 md:aspect-auto overflow-hidden">
                                <Box className="absolute inset-0 transition-transform duration-500 ease-[cubic-bezier(0.25,1,0.5,1)] group-hover:scale-[1.04]">
                                    <Image
                                        src={featured.image}
                                        alt={featured.imageAlt}
                                        className="absolute inset-0 w-full h-full"
                                        objectFit="cover"
                                    />
                                </Box>
                            </Box>
                            <Box className="flex flex-col justify-center p-6 sm:p-8">
                                <Box className="flex items-center gap-3">
                                    <Badge variant="tag">{featured.category}</Badge>
                                    <Text as="span" className="text-xs text-slate-400">
                                        {featured.date}
                                    </Text>
                                </Box>
                                <Heading
                                    level={2}
                                    className="mt-3 font-display text-xl sm:text-2xl font-bold text-slate-900 leading-snug text-balance group-hover:text-primary-700 transition-colors"
                                >
                                    {featured.title}
                                </Heading>
                                <Text className="mt-3 text-sm text-slate-500 leading-relaxed line-clamp-3">
                                    {featured.excerpt}
                                </Text>
                                <Text
                                    as="span"
                                    className="mt-4 inline-flex items-center gap-2 font-semibold text-sm text-primary-700"
                                >
                                    Baca selengkapnya
                                    <Text
                                        as="span"
                                        aria-hidden="true"
                                        className="transition-transform duration-300 group-hover:translate-x-1"
                                    >
                                        →
                                    </Text>
                                </Text>
                            </Box>
                        </Box>
                    </Link>
                )}

                {rest.length > 0 && (
                    <Box
                        className="grid gap-x-5 gap-y-8"
                        style={{ gridTemplateColumns: "repeat(auto-fit, minmax(260px, 1fr))" }}
                    >
                        {rest.map((article) => (
                            <NewsCard key={article.slug} article={article} />
                        ))}
                    </Box>
                )}
            </DashboardLayout>
        </>
    );
}
