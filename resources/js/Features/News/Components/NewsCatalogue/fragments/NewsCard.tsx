import { Link } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Image } from "@/Core/Components/Common/Image";
import { Text } from "@/Core/Components/Common/Text";
import Badge from "@/Core/Components/Shared/Badge/Badge";
import { type NewsListItem } from "@/Features/News/Types/news.type";

interface NewsCardProps {
    article: NewsListItem;
}

export default function NewsCard({ article }: NewsCardProps) {
    return (
        <Link href={article.href} className="group block">
            <Box className="relative w-full aspect-3/2 rounded-2xl overflow-hidden shadow-card-soft card-hover-lift">
                <Box className="absolute inset-0 transition-transform duration-500 ease-[cubic-bezier(0.25,1,0.5,1)] group-hover:scale-[1.05]">
                    <Image
                        src={article.image}
                        alt={article.imageAlt}
                        className="absolute inset-0 w-full h-full"
                        objectFit="cover"
                    />
                </Box>
                <Box className="absolute top-2.5 left-2.5 z-10">
                    <Badge variant="tag">{article.category}</Badge>
                </Box>
            </Box>

            <Box className="mt-3 px-0.5">
                <Text className="text-[11px] font-semibold tracking-wide uppercase text-slate-400 mb-1.5">
                    {article.date}
                </Text>
                <Text className="text-sm font-bold text-slate-800 line-clamp-2 leading-snug mb-1 group-hover:text-primary-700 transition-colors">
                    {article.title}
                </Text>
                <Text className="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                    {article.excerpt}
                </Text>
            </Box>
        </Link>
    );
}
