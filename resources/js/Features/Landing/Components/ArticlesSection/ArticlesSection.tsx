import { useRef } from "react";
import { Link, usePage } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Image } from "@/Core/Components/Common/Image";
import { Text } from "@/Core/Components/Common/Text";
import Badge from "@/Core/Components/Shared/Badge/Badge";
import { news } from "@/routes";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { articleEntries, featuredArticle } from "../../Data/articlesSection.data";
import type { ArticleEntry } from "../../Types/articlesSection.type";
import { safeJsonParse } from "../../Utils/safeJsonParse";
import { useArticlesSectionAnimation } from "../../Hooks/useArticlesSectionAnimation";
import ArticleIndexRow from "./fragments/ArticleIndexRow";

/** CMS rows are already per-locale; only the bundled defaults go through t(). */
function buildArticle(raw: string | undefined, fallback: ArticleEntry, t: (k: string) => string): ArticleEntry {
    const translated: ArticleEntry = {
        ...fallback,
        title: t(fallback.title),
        category: t(fallback.category),
        date: t(fallback.date),
        excerpt: t(fallback.excerpt),
        imageAlt: t(fallback.imageAlt),
    };
    const parsed = safeJsonParse<Record<string, string>>(raw, {});
    if (!parsed.title) return translated;
    return {
        ...translated,
        title: parsed.title ?? translated.title,
        category: parsed.category ?? translated.category,
        date: parsed.date ?? translated.date,
        excerpt: parsed.excerpt ?? translated.excerpt,
        href: parsed.href ?? fallback.href,
        image: parsed.image_url ?? fallback.image,
        imageAlt: parsed.image_alt ?? translated.imageAlt,
    };
}

export default function ArticlesSection() {
    const containerRef = useRef<HTMLElement>(null);
    const { t } = useTranslation();

    useArticlesSectionAnimation(containerRef);

    const lc: Record<string, string> = (usePage().props as any).landingContent ?? {};

    const heading = lc.articles_heading ?? t("News from the Lab");
    const subheading = lc.articles_subheading ?? t("Latest Activity.");
    const body =
        lc.articles_body ??
        t("Coverage of workshops, visits, and moments from behind the laboratory bench — documented by the team itself.");

    const lead = buildArticle(lc.articles_featured, featuredArticle, t);
    const entries = articleEntries.map((entry, i) =>
        buildArticle(lc[`articles_entry_${i + 1}`], entry, t),
    );

    return (
        <Box
            as="section"
            id="articles"
            ref={containerRef}
            className="relative bg-surface-base pt-24 md:pt-32 pb-28 md:pb-36 px-6 md:px-12 overflow-hidden"
        >
            <Box className="relative z-10 max-w-6xl mx-auto">
                <Box className="articles-intro mb-14 md:mb-18">
                    <Box className="flex items-center gap-3 mb-8">
                        <Box className="w-8 h-px bg-secondary-500/60" />
                        <Text
                            as="span"
                            className="text-[0.68rem] font-body font-semibold tracking-[0.3em] uppercase text-primary-700/80"
                        >
                            Kabar Laboratorium
                        </Text>
                    </Box>

                    <Heading
                        level={2}
                        className="font-display tracking-tight text-balance"
                    >
                        <Text
                            as="span"
                            className="block text-4xl md:text-6xl leading-[1.05] font-extrabold text-black"
                        >
                            {heading}
                        </Text>
                        <Text
                            as="span"
                            className="block text-4xl md:text-6xl leading-[1.05] font-light italic text-primary-900"
                        >
                            {subheading}
                        </Text>
                    </Heading>

                    <Text className="mt-7 text-lg md:text-xl font-body text-primary-900/70 max-w-[65ch] leading-relaxed text-pretty">
                        {body}
                    </Text>
                </Box>

                <Box className="grid lg:grid-cols-[5fr_7fr] gap-12 lg:gap-16">
                    {/* Lead — the featured entry */}
                    <Box className="articles-lead">
                        <Link
                            href={lead.href}
                            className="group block focus-visible:outline-2 focus-visible:outline-secondary-500 focus-visible:outline-offset-4 rounded-lg"
                        >
                            <Box className="relative aspect-[3/2] rounded-lg overflow-hidden shadow-card-elevated transition-transform duration-700 ease-[cubic-bezier(0.25,1,0.5,1)] group-hover:scale-[1.015]">
                                <Image
                                    src={lead.image}
                                    alt={lead.imageAlt}
                                    className="absolute inset-0 w-full h-full"
                                    objectFit="cover"
                                />
                            </Box>
                            <Box className="mt-6">
                                <Box className="flex items-center gap-3">
                                    <Badge variant="tag">{lead.category}</Badge>
                                    <Text as="span" className="text-sm font-body text-primary-900/60">
                                        {lead.date}
                                    </Text>
                                </Box>
                                <Text className="mt-3 font-display font-extrabold text-2xl md:text-3xl leading-snug text-primary-950 text-balance transition-colors duration-300 group-hover:text-primary-700">
                                    {lead.title}
                                </Text>
                                <Text className="mt-3 text-sm md:text-base font-body text-primary-900/70 leading-relaxed line-clamp-3 text-pretty">
                                    {lead.excerpt}
                                </Text>
                                <Text
                                    as="span"
                                    className="mt-4 inline-flex items-center gap-2 font-body font-semibold text-sm text-primary-700"
                                >
                                    Baca selengkapnya
                                    <Text
                                        as="span"
                                        aria-hidden="true"
                                        className="text-sm text-inherit leading-none transition-transform duration-300 group-hover:translate-x-1"
                                    >
                                        →
                                    </Text>
                                </Text>
                            </Box>
                        </Link>
                    </Box>

                    {/* Index — recent entries */}
                    <Box className="flex flex-col">
                        <Text
                            as="span"
                            className="articles-index-label text-[0.68rem] font-body font-semibold tracking-[0.3em] uppercase text-primary-700/80 mb-4"
                        >
                            Kabar Lainnya
                        </Text>

                        <Box className="flex flex-col">
                            {entries.map((entry) => (
                                <ArticleIndexRow key={entry.href} entry={entry} />
                            ))}
                        </Box>

                        <Box className="articles-more lg:mt-auto">
                            <Box
                                aria-hidden="true"
                                className="articles-row-rule h-px bg-primary-900/10 origin-left"
                            />
                            <Link
                                href={news().url}
                                className="group inline-flex items-center gap-2 pt-6 font-body font-semibold text-base text-primary-700 underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-secondary-500 focus-visible:outline-offset-4 rounded-sm"
                            >
                                {t("See All News")}
                                <Text
                                    as="span"
                                    aria-hidden="true"
                                    className="text-base text-inherit leading-none transition-transform duration-300 group-hover:translate-x-1"
                                >
                                    →
                                </Text>
                            </Link>
                        </Box>
                    </Box>
                </Box>
            </Box>
        </Box>
    );
}
