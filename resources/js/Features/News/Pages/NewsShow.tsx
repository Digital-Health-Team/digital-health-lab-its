import { Head, Link, usePage } from "@inertiajs/react";
import { ChevronRight } from "lucide-react";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Image } from "@/Core/Components/Common/Image";
import { Text } from "@/Core/Components/Common/Text";
import Badge from "@/Core/Components/Shared/Badge/Badge";
import { news } from "@/routes";
import NewsCard from "@/Features/News/Components/NewsCatalogue/fragments/NewsCard";
import { type NewsDetail, type NewsListItem } from "@/Features/News/Types/news.type";

interface NewsShowProps {
    article: NewsDetail;
    related: NewsListItem[];
    [key: string]: unknown;
}

export default function NewsShow() {
    const { article, related } = usePage<NewsShowProps>().props;

    return (
        <>
            <Head title={article.title} />
            <DashboardLayout>
                {/* Breadcrumb */}
                <Box className="flex items-center gap-1 flex-wrap">
                    <Link href="/" className="text-xs text-slate-400 hover:text-primary-700 transition-colors">
                        Home
                    </Link>
                    <ChevronRight className="h-3 w-3 text-slate-300 shrink-0" />
                    <Link href={news().url} className="text-xs text-slate-400 hover:text-primary-700 transition-colors">
                        Kabar Laboratorium
                    </Link>
                    <ChevronRight className="h-3 w-3 text-slate-300 shrink-0" />
                    <Text as="span" className="text-xs font-medium text-slate-700 line-clamp-1 max-w-[240px]">
                        {article.title}
                    </Text>
                </Box>

                <Box as="article" className="max-w-[68ch]">
                    <Box className="flex items-center gap-3">
                        <Badge variant="tag">{article.category}</Badge>
                        <Text as="span" className="text-xs text-slate-400">
                            {article.date}
                        </Text>
                    </Box>

                    <Heading
                        level={1}
                        className="mt-4 font-display text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 leading-[1.15] text-balance"
                    >
                        {article.title}
                    </Heading>

                    <Box className="relative mt-8 aspect-3/2 sm:aspect-video rounded-2xl overflow-hidden shadow-card-elevated">
                        <Image
                            src={article.image}
                            alt={article.imageAlt}
                            className="absolute inset-0 w-full h-full"
                            objectFit="cover"
                        />
                    </Box>

                    <Box className="mt-8 flex flex-col gap-4">
                        {article.body.map((paragraph, i) => (
                            <Text key={i} className="text-base text-slate-600 leading-relaxed text-pretty">
                                {paragraph}
                            </Text>
                        ))}
                    </Box>
                </Box>

                {related.length > 0 && (
                    <Box as="section">
                        <Heading
                            level={2}
                            className="font-display text-xl font-bold text-slate-800 leading-tight mb-6"
                        >
                            Kabar Lainnya
                        </Heading>
                        <Box
                            className="grid gap-x-5 gap-y-8"
                            style={{ gridTemplateColumns: "repeat(auto-fit, minmax(260px, 1fr))" }}
                        >
                            {related.map((article) => (
                                <NewsCard key={article.slug} article={article} />
                            ))}
                        </Box>
                    </Box>
                )}
            </DashboardLayout>
        </>
    );
}
