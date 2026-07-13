import { useRef } from "react";
import { Link, usePage } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Image } from "@/Core/Components/Common/Image";
import { Text } from "@/Core/Components/Common/Text";
import { publications } from "@/routes";
import { articleEntries, featuredArticle } from "../../Data/articlesSection.data";
import type { ArticleEntry, ArticleFeature } from "../../Types/articlesSection.type";
import { safeJsonParse } from "../../Utils/safeJsonParse";
import { useArticlesSectionAnimation } from "../../Hooks/useArticlesSectionAnimation";
import ArticleIndexRow from "./fragments/ArticleIndexRow";

function buildEntry(raw: string | undefined, fallback: ArticleEntry): ArticleEntry {
    const parsed = safeJsonParse<Record<string, string>>(raw, {});
    if (!parsed.title) return fallback;
    return {
        ...fallback,
        title: parsed.title ?? fallback.title,
        author: parsed.author ?? fallback.author,
        category: parsed.category ?? fallback.category,
        year: parsed.year ?? fallback.year,
        href: parsed.href ?? fallback.href,
    };
}

function buildFeature(raw: string | undefined, fallback: ArticleFeature): ArticleFeature {
    const parsed = safeJsonParse<Record<string, string>>(raw, {});
    if (!parsed.title) return fallback;
    return {
        ...buildEntry(raw, fallback),
        image: parsed.image_url ?? fallback.image,
        imageAlt: parsed.image_alt ?? fallback.imageAlt,
    };
}

export default function ArticlesSection() {
    const containerRef = useRef<HTMLElement>(null);

    useArticlesSectionAnimation(containerRef);

    const lc: Record<string, string> = (usePage().props as any).landingContent ?? {};

    const heading = lc.articles_heading ?? "Dari Meja Riset";
    const subheading = lc.articles_subheading ?? "Publikasi Terbaru.";
    const body =
        lc.articles_body ??
        "Jurnal, paper, dan riset terbaru dari laboratorium — terdokumentasi dan terbuka untuk dipelajari.";

    const lead = buildFeature(lc.articles_featured, featuredArticle);
    const entries = articleEntries.map((entry, i) =>
        buildEntry(lc[`articles_entry_${i + 1}`], entry),
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
                            Publikasi & Riset
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
                            className="block text-4xl md:text-6xl leading-[1.05] font-light italic text-[#062e5c]"
                        >
                            {subheading}
                        </Text>
                    </Heading>

                    <Text className="mt-7 text-lg md:text-xl font-body text-[#062e5c]/70 max-w-[65ch] leading-relaxed text-pretty">
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
                            <Box className="relative aspect-[3/4] max-w-md rounded-lg overflow-hidden shadow-card-elevated transition-transform duration-700 ease-[cubic-bezier(0.25,1,0.5,1)] group-hover:scale-[1.015]">
                                <Image
                                    src={lead.image}
                                    alt={lead.imageAlt}
                                    className="absolute inset-0 w-full h-full"
                                    objectFit="cover"
                                />
                            </Box>
                            <Box className="mt-6 max-w-md">
                                <Text
                                    as="p"
                                    className="text-[0.72rem] font-body font-bold tracking-[0.22em] uppercase text-primary-700"
                                >
                                    {lead.category} · {lead.year}
                                </Text>
                                <Text className="mt-3 font-display font-extrabold text-2xl md:text-3xl leading-snug text-primary-950 text-balance transition-colors duration-300 group-hover:text-primary-700">
                                    {lead.title}
                                </Text>
                                <Text className="mt-3 text-sm md:text-base font-body text-[#062e5c]/70">
                                    {lead.author}
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
                            Entri Terbaru
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
                                href={publications().url}
                                className="group inline-flex items-center gap-2 pt-6 font-body font-semibold text-base text-primary-700 underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-secondary-500 focus-visible:outline-offset-4 rounded-sm"
                            >
                                Lihat Semua Publikasi
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
