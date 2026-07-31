import { useRef, type CSSProperties } from "react";
import { usePage } from "@inertiajs/react";
import { ABOUT_MEDIA, capabilities, HEADLINE_ACCENT, HEADLINE_SENTENCE } from "../../Data/aboutSection.data";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import type { Capability, AboutHeadlineWord } from "../../Types/aboutSection.type";
import { safeJsonParse } from "../../Utils/safeJsonParse";
import { useAboutSectionAnimation } from "../../Hooks/useAboutSectionAnimation";
import ChapterIntro from "./fragments/ChapterIntro";
import CapabilityItem from "./fragments/CapabilityItem";
import AboutMedia from "./fragments/AboutMedia";

function parseHeadlineWords(headline: string, accentWord: string): AboutHeadlineWord[] {
    const parts = headline.split(" / ");
    const result: AboutHeadlineWord[] = [];
    parts.forEach((part, pi) => {
        part.trim().split(" ").forEach((w, wi, arr) => {
            result.push({
                word: w,
                accent: w === accentWord,
                lineBreakAfter: pi < parts.length - 1 && wi === arr.length - 1,
            });
        });
    });
    return result;
}

/** CMS rows are already per-locale; only the bundled defaults go through t(). */
function buildCapability(raw: string | undefined, fallback: Capability, t: (k: string) => string): Capability {
    const translated: Capability = {
        ...fallback,
        tag: t(fallback.tag),
        title: t(fallback.title),
        description: t(fallback.description),
        imageAlt: t(fallback.imageAlt),
    };
    const parsed = safeJsonParse<Record<string, string>>(raw, {});
    if (!parsed.title) return translated;
    return {
        tag: parsed.tag ?? translated.tag,
        title: parsed.title,
        description: parsed.description ?? translated.description,
        image: parsed.image_url ?? fallback.image,
        imageAlt: translated.imageAlt,
        accent: parsed.accent ?? fallback.accent,
        // Layout, not content — deliberately not a CMS field.
        imageSide: fallback.imageSide,
    };
}

export default function AboutSection() {
    const sectionRef = useRef<HTMLElement>(null);
    const { t } = useTranslation();

    useAboutSectionAnimation(sectionRef);

    const lc: Record<string, string> = (usePage().props as any).landingContent ?? {};

    // Translating one sentence and re-splitting beats maintaining a per-locale word
    // array — parseHeadlineWords already exists for the CMS path.
    const derivedHeadline = lc.about_headline
        ? parseHeadlineWords(lc.about_headline, lc.about_headline_accent ?? "")
        : parseHeadlineWords(t(HEADLINE_SENTENCE), t(HEADLINE_ACCENT));

    const derivedCapabilities = capabilities.map((cap, i) =>
        buildCapability(lc[`about_capability_${i + 1}`], cap, t),
    );

    // Decorative stills — no CMS override, so only the alt text needs translating.
    const derivedMedia = ABOUT_MEDIA.map((item) => ({ ...item, alt: t(item.alt) }));

    const body1 =
        lc.about_body_1 ??
        t("The ITS Medical Technology Laboratory is a pioneer bridging multidisciplinary academic research with the real needs of the national healthcare sector. We are dedicated to delivering biomedical engineering solutions that are innovative, precise, and produced to a high quality standard — clinically validated, comprehensively documented, and ready for distribution.");

    const body2 =
        lc.about_body_2 ??
        t("Through close collaboration between researchers, medical practitioners, and professional engineers, we have grown into a centre of excellence for prosthetics, custom implants, and other medical devices. Our commitment is to push past the conventional limits of medical manufacturing — improving patients' quality of life and strengthening the self-reliance of healthcare facilities across Indonesia.");

    return (
        <section
            ref={sectionRef}
            id="about"
            className="relative bg-primary-900"
        >
            {/* ═══════════════════════════════════════════════════
                ACT 1 — THE VISION
               ═══════════════════════════════════════════════════ */}
            <div className="chapter-container act-1 relative md:h-screen w-full overflow-hidden">
                <ChapterIntro
                    digitNum="01"
                    glyphText={t("About Us")}
                    subText={t("About the ITS Health Technology Laboratory")}
                />

                {/* Content Block (Dark) */}
                <div className="chapter-content md:absolute md:inset-0 z-10 md:h-full w-full bg-primary-900">
                    {/* Background layers */}
                    <div className="absolute inset-0 honeycomb-dark opacity-[0.04] pointer-events-none" />
                    <div
                        className="act1-glow absolute bottom-0 left-0 w-[70vw] h-[60vh] pointer-events-none select-none"
                        aria-hidden="true"
                        style={{
                            background:
                                "radial-gradient(ellipse at 20% 90%, rgba(0,168,181,0.1) 0%, transparent 55%)",
                        }}
                    />

                    {/* Content */}
                    <div className="act-content relative z-10 md:h-full flex flex-col md:justify-center justify-start py-24 md:py-0 px-[clamp(24px,5vw,48px)]">
                        {/* Vision copy left, image stack right. The stack only claims a
                            column at lg — see AboutMedia for why md is left alone. */}
                        <div className="max-w-5xl lg:max-w-6xl mx-auto w-full grid gap-14 lg:grid-cols-[minmax(0,1fr)_auto] lg:gap-16 lg:items-center">
                          <div>
                            {/* Chapter marker */}
                            <div className="act1-label anim-el flex items-center gap-3 mb-10">
                                <div className="w-10 h-px bg-secondary-400/40" />
                                <span className="text-[0.68rem] font-body font-semibold tracking-[0.3em] uppercase text-secondary-400/70">
                                    {t("About Us")}
                                </span>
                            </div>

                            {/* Headline — word-by-word reveal */}
                            <h2
                                className="font-display font-extrabold leading-[1.05] tracking-tight max-w-[18ch]"
                                style={{
                                    fontSize: "clamp(2.4rem, 6.5vw, 4.5rem)",
                                }}
                            >
                                {derivedHeadline.map((item, i) => (
                                    <span key={i}>
                                        <span className="inline-block overflow-hidden">
                                            <span
                                                className={`hw inline-block ${
                                                    item.accent
                                                        ? "text-secondary-400"
                                                        : "text-[#F8FAFC]"
                                                }`}
                                            >
                                                {item.word}
                                            </span>
                                        </span>
                                        {item.lineBreakAfter ? (
                                            <>
                                                {" "}
                                                <br className="hidden md:block" />
                                            </>
                                        ) : (
                                            " "
                                        )}
                                    </span>
                                ))}
                            </h2>

                            {/* Body text */}
                            <div className="act1-body anim-el mt-10 md:mt-14 flex flex-col gap-8 max-w-2xl">
                                <p
                                    className="font-body text-white leading-loose pl-5 border-l border-yellow-400"
                                    style={{
                                        fontSize:
                                            "clamp(0.95rem, 1.5vw, 1.08rem)",
                                    }}
                                >
                                    {body1}
                                </p>
                                <p
                                    className="font-body text-white leading-loose pl-5 border-l border-yellow-400"
                                    style={{
                                        fontSize:
                                            "clamp(0.95rem, 1.5vw, 1.08rem)",
                                    }}
                                >
                                    {body2}
                                </p>
                            </div>
                          </div>

                          <AboutMedia items={derivedMedia} />
                        </div>
                    </div>

                    {/* Act number */}
                    <div className="absolute bottom-8 right-8 md:right-12 text-[#F8FAFC]/6 font-display font-extrabold text-[6rem] md:text-[8rem] leading-none select-none pointer-events-none">
                        01
                    </div>
                </div>
            </div>

            {/* ═══════════════════════════════════════════════════
                ACT 2 — CAPABILITIES
               ═══════════════════════════════════════════════════ */}
            <div className="chapter-container act-2 relative md:h-screen w-full overflow-hidden">
                <ChapterIntro
                    digitNum="02"
                    glyphText={t("Capabilities")}
                    subText={t("Core Laboratory Capabilities")}
                />

                {/* Content Block (Dark) */}
                <div className="chapter-content md:absolute md:inset-0 z-10 md:h-full w-full bg-primary-900">
                    <div className="absolute inset-0 honeycomb-dark opacity-[0.04] pointer-events-none" />
                    <div
                        className="absolute top-0 right-0 w-[50vw] h-[50vh] pointer-events-none select-none"
                        aria-hidden="true"
                        style={{
                            background:
                                "radial-gradient(ellipse at 80% 20%, rgba(34,211,238,0.06) 0%, transparent 55%)",
                        }}
                    />

                    <div className="act-content relative z-10 md:h-full flex flex-col md:justify-center justify-start py-24 md:py-0 px-[clamp(24px,5vw,48px)]">
                        <div className="max-w-6xl mx-auto w-full">
                            {/* Header */}
                            <div className="act2-header anim-el">
                                <span className="inline-block text-[0.65rem] font-body font-semibold tracking-[0.25em] uppercase text-secondary-400/60 mb-3">
                                    {t("Capabilities")}
                                </span>
                                <h3
                                    className="font-display font-bold text-[#F8FAFC] leading-[1.12] tracking-tight"
                                    style={{
                                        fontSize:
                                            "clamp(1.6rem, 3.5vw, 2.4rem)",
                                    }}
                                >
                                    {t("Some of Our Core Competencies")}
                                </h3>
                            </div>

                            {/* Capability window — desktop shows three rows at a time and
                                the track steps up one row per scroll beat, so the list
                                cycles inside the pin. Mobile ignores the clip and stacks
                                all of them. The edge mask keeps a mid-step row from
                                looking hard-cut at the window boundary. */}
                            <div
                                className="cap-viewport mt-10 md:mt-12 md:h-[calc(var(--cap-row)*3)] md:overflow-hidden md:[mask-image:linear-gradient(to_bottom,transparent_0%,#000_9%,#000_91%,transparent_100%)]"
                                style={
                                    {
                                        "--cap-row": "clamp(150px, 22vh, 230px)",
                                    } as CSSProperties
                                }
                            >
                                <div className="cap-track">
                                    {derivedCapabilities.map((cap, i) => (
                                        <CapabilityItem
                                            key={cap.tag}
                                            cap={cap}
                                            index={i}
                                        />
                                    ))}
                                </div>
                            </div>
                        </div>

                        <div className="absolute bottom-8 right-8 md:right-12 text-[#F8FAFC]/6 font-display font-extrabold text-[6rem] md:text-[8rem] leading-none select-none pointer-events-none">
                            02
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
