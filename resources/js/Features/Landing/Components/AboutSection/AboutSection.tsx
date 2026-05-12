import { useRef } from "react";
import { capabilities, headlineWords } from "../../Data/aboutSection.data";
import { useAboutSection } from "../../Hooks/useAboutSection";
import ChapterIntro from "./fragments/ChapterIntro";
import CapabilityItem from "./fragments/CapabilityItem";

export default function AboutSection() {
    const sectionRef = useRef<HTMLElement>(null);

    useAboutSection(sectionRef);

    return (
        <section
            ref={sectionRef}
            id="about"
            className="relative bg-primary-900"
        >
            {/* ═══════════════════════════════════════════════════
                ACT 1 — THE VISION
               ═══════════════════════════════════════════════════ */}
            <div className="chapter-container act-1 relative h-screen w-full overflow-hidden">
                <ChapterIntro
                    digitNum="01"
                    glyphText="THE VISION"
                    subText="Strategic Alignment"
                />

                {/* Content Block (Dark) */}
                <div className="chapter-content absolute inset-0 z-10 h-full w-full bg-primary-900">
                    {/* Background layers */}
                    <div className="absolute inset-0 honeycomb-dark opacity-[0.04] pointer-events-none" />
                    <div
                        className="act1-glow absolute bottom-0 left-0 w-[70vw] h-[60vh] pointer-events-none select-none"
                        aria-hidden="true"
                        style={{
                            background:
                                "radial-linear(ellipse at 20% 90%, rgba(0,168,181,0.1) 0%, transparent 55%)",
                        }}
                    />

                    {/* Content */}
                    <div className="act-content relative z-10 h-full flex flex-col justify-center px-[clamp(24px,5vw,48px)]">
                        <div className="max-w-5xl mx-auto w-full">
                            {/* Chapter marker */}
                            <div className="act1-label anim-el flex items-center gap-3 mb-10">
                                <div className="w-10 h-px bg-secondary-400/40" />
                                <span className="text-[0.68rem] font-body font-semibold tracking-[0.3em] uppercase text-secondary-400/70">
                                    Tentang Kami
                                </span>
                            </div>

                            {/* Headline — word-by-word reveal */}
                            <h2
                                className="font-display font-extrabold leading-[1.05] tracking-tight max-w-[18ch]"
                                style={{
                                    fontSize: "clamp(2.4rem, 6.5vw, 4.5rem)",
                                }}
                            >
                                {headlineWords.map((item, i) => (
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
                                    Laboratorium Teknologi Medis ITS berdiri
                                    sebagai pionir yang menjembatani dunia riset
                                    akademis multidisiplin dengan kebutuhan
                                    nyata pada sektor layanan kesehatan
                                    nasional. Kami berdedikasi penuh untuk
                                    menghadirkan berbagai solusi rekayasa
                                    biomedis yang inovatif, presisi, serta
                                    diproduksi dengan standar kualitas tinggi
                                    yang telah tervalidasi secara klinis,
                                    terdokumentasi secara komprehensif, dan siap
                                    untuk didistribusikan.
                                </p>
                                <p
                                    className="font-body text-white leading-loose pl-5 border-l border-yellow-400"
                                    style={{
                                        fontSize:
                                            "clamp(0.95rem, 1.5vw, 1.08rem)",
                                    }}
                                >
                                    Melalui sinergi kuat antara peneliti,
                                    praktisi medis, dan insinyur profesional,
                                    kami bertransformasi menjadi pusat unggulan
                                    dalam pengembangan prostetik, implan kustom,
                                    serta perangkat medis lainnya. Komitmen
                                    utama kami adalah mendobrak batas
                                    konvensional teknologi manufaktur medis demi
                                    meningkatkan kualitas hidup pasien serta
                                    mendorong kemandirian fasilitas kesehatan di
                                    seluruh Indonesia.
                                </p>
                            </div>
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
            <div className="chapter-container act-2 relative h-screen w-full overflow-hidden">
                <ChapterIntro
                    digitNum="02"
                    glyphText="CAPABILITIES"
                    subText="Core Competencies"
                />

                {/* Content Block (Dark) */}
                <div className="chapter-content absolute inset-0 z-10 h-full w-full bg-primary-900">
                    <div className="absolute inset-0 honeycomb-dark opacity-[0.04] pointer-events-none" />
                    <div
                        className="absolute top-0 right-0 w-[50vw] h-[50vh] pointer-events-none select-none"
                        aria-hidden="true"
                        style={{
                            background:
                                "radial-linear(ellipse at 80% 20%, rgba(34,211,238,0.06) 0%, transparent 55%)",
                        }}
                    />

                    <div className="act-content relative z-10 h-full flex flex-col justify-center px-[clamp(24px,5vw,48px)]">
                        <div className="max-w-5xl mx-auto w-full">
                            {/* Header */}
                            <div className="act2-header anim-el">
                                <span className="inline-block text-[0.65rem] font-body font-semibold tracking-[0.25em] uppercase text-secondary-400/60 mb-3">
                                    Kapabilitas
                                </span>
                                <h3
                                    className="font-display font-bold text-[#F8FAFC] leading-[1.12] tracking-tight"
                                    style={{
                                        fontSize:
                                            "clamp(1.6rem, 3.5vw, 2.4rem)",
                                    }}
                                >
                                    Tiga Pilar Keunggulan
                                </h3>
                            </div>

                            {/* Capability items with spine */}
                            <div className="mt-12 md:mt-16 relative">
                                {/* Vertical progress spine */}
                                <div
                                    className="cap-spine absolute left-[clamp(24px,3vw,36px)] top-0 bottom-0 w-px hidden md:block"
                                    style={{
                                        background:
                                            "linear-linear(to bottom, rgba(0,168,181,0.4), rgba(34,211,238,0.15) 50%, rgba(255,199,44,0.3))",
                                        transformOrigin: "top",
                                    }}
                                />

                                <div className="space-y-0">
                                    {capabilities.map((cap, i) => (
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
