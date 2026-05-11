import { useRef } from "react";
import { HEADING_WORDS, QUOTE_WORDS } from "../../Constants/sharingWisdomData";
import { useSharingWisdom } from "../../Hooks/useSharingWisdom";
import EcgLine from "./fragments/EcgLine";

export default function WisdomSection() {
    const sectionRef = useRef<HTMLElement>(null);

    useSharingWisdom(sectionRef);

    return (
        <section
            ref={sectionRef}
            id="sharing-wisdom"
            className="relative bg-primary-900"
        >
            {/* ═══════════════════════════════════════════════════
                ACT 1 — HEADING
               ═══════════════════════════════════════════════════ */}
            <div className="wg-act-1 relative h-screen overflow-hidden">
                {/* Honeycomb texture */}
                <div className="absolute inset-0 honeycomb-dark opacity-[0.06] pointer-events-none" />

                {/* ECG line */}
                <EcgLine />

                {/* Ambient glow — left */}
                <div
                    className="wg-glow-1 absolute bottom-0 left-0 w-[60vw] h-[55vh] pointer-events-none"
                    aria-hidden="true"
                    style={{
                        background:
                            "radial-gradient(ellipse at 15% 90%, rgba(0,168,181,0.12) 0%, transparent 55%)",
                    }}
                />

                <div className="wg-act-content-1 relative z-10 h-full flex flex-col justify-center px-[clamp(24px,6vw,80px)]">
                    <div className="max-w-5xl mx-auto w-full">
                        {/* Chapter eyebrow */}
                        <div className="wg-label flex items-center gap-3 mb-10">
                            <div className="w-8 h-px bg-secondary-400/40" />
                            <span className="text-[0.68rem] font-body font-semibold tracking-[0.3em] uppercase text-secondary-400/70">
                                Berbagi Kebijaksanaan
                            </span>
                        </div>

                        {/* Heading — word-by-word overflow-hidden reveal */}
                        <h2
                            className="font-display font-extrabold leading-[1.1] tracking-tight"
                            style={{ fontSize: "clamp(2.4rem, 6vw, 4.5rem)" }}
                        >
                            {HEADING_WORDS.map((w, i) => (
                                <span key={i} className="inline-block mr-[0.22em]">
                                    <span className="inline-block overflow-hidden">
                                        <span
                                            className={`wg-hw inline-block ${
                                                w.accent ? "text-secondary-400 italic" : "text-white"
                                            }`}
                                        >
                                            {w.text}
                                        </span>
                                    </span>
                                </span>
                            ))}
                        </h2>

                        {/* Body text */}
                        <p
                            className="wg-body mt-8 font-body text-white/60 leading-[1.75]"
                            style={{ fontSize: "clamp(0.95rem, 1.4vw, 1.05rem)", maxWidth: "48ch" }}
                        >
                            Kami ingin berbagi ilmu dan fasilitas agar bisa saling membantu. Kami
                            telah menguji dan memvalidasi konten-konten pada website ini.
                        </p>
                    </div>
                </div>

                {/* Act number */}
                <div className="absolute bottom-8 right-8 md:right-14 text-white/4 font-display font-extrabold text-[7rem] leading-none select-none pointer-events-none">
                    01
                </div>
            </div>

            {/* ═══════════════════════════════════════════════════
                ACT 2 — QUOTE + HEXAGON CASCADE
               ═══════════════════════════════════════════════════ */}
            <div className="wg-act-2 relative h-screen overflow-hidden">
                {/* Hexagon cascade — right half */}
                <div
                    className="wg-hex-panel absolute inset-y-0 right-0 w-3/5 honeycomb-dark pointer-events-none"
                    aria-hidden="true"
                    style={{ opacity: 0.22, clipPath: "inset(0 100% 0 0)" }}
                />

                {/* Ambient glow — right */}
                <div
                    className="absolute inset-y-0 right-0 w-1/2 pointer-events-none"
                    aria-hidden="true"
                    style={{
                        background:
                            "radial-gradient(ellipse 80% 80% at 85% 50%, rgba(0,66,109,0.45), transparent 70%)",
                    }}
                />

                <div className="wg-act-content-2 relative z-10 h-full flex flex-col justify-center px-[clamp(24px,6vw,80px)]">
                    <div className="max-w-6xl mx-auto w-full grid grid-cols-1 lg:grid-cols-[2fr_3fr] gap-12 lg:gap-20 items-center">
                        {/* Left — Logo */}
                        <div className="flex items-center justify-center lg:justify-end lg:pr-8">
                            <img
                                src="/assets/images/logo_idig_htech_white.png"
                                alt="iDIG Health Tech Logo"
                                className="w-48 lg:w-72 object-contain drop-shadow-[0_0_30px_rgba(34,211,238,0.15)]"
                            />
                        </div>

                        {/* Right — Quote */}
                        <blockquote
                            className="relative rounded-2xl px-8 pt-12 pb-8"
                            style={{
                                background: "rgba(6, 46, 92, 0.45)",
                                border: "1px solid rgba(34, 211, 238, 0.18)",
                                boxShadow:
                                    "0 8px 48px rgba(3, 16, 38, 0.55), inset 0 1px 0 rgba(34, 211, 238, 0.10)",
                            }}
                        >
                            <span
                                className="absolute top-3 left-6 text-8xl text-secondary-400 font-serif leading-none select-none"
                                style={{ opacity: 0.8 }}
                                aria-hidden="true"
                            >
                                &ldquo;
                            </span>

                            {/* Quote text — words revealed individually */}
                            <p className="text-lg md:text-xl font-body italic text-white/90 leading-relaxed">
                                {QUOTE_WORDS.map((w, i) => (
                                    <span
                                        key={i}
                                        className="wg-qword inline-block mr-[0.28em] overflow-hidden"
                                    >
                                        <span className="inline-block">{w}</span>
                                    </span>
                                ))}
                            </p>

                            <div className="wg-attr mt-8 flex items-center gap-4">
                                <div
                                    className="w-12 h-12 rounded-full flex items-center justify-center shrink-0"
                                    style={{
                                        background: "#00426d",
                                        boxShadow:
                                            "0 0 0 2px rgba(34,211,238,0.45), 0 0 12px rgba(34,211,238,0.18)",
                                    }}
                                >
                                    <span className="text-secondary-400 font-bold text-sm font-body">
                                        DK
                                    </span>
                                </div>

                                <div>
                                    <p className="text-white font-semibold font-body text-sm">
                                        Djoko Kuswanto, S.T., M.Biotech.
                                    </p>
                                    <p className="text-xs text-secondary-400 font-body mt-0.5">
                                        Kepala Laboratorium IDIG HTECH
                                    </p>
                                </div>
                            </div>
                        </blockquote>
                    </div>

                    {/* CTA */}
                    <div className="wg-cta mt-14 flex justify-center">
                        <a
                            href="#categories"
                            className="inline-flex items-center gap-3 px-10 py-4 rounded-full border border-secondary-400/55 text-white font-body font-medium text-base hover:border-secondary-400 hover:bg-secondary-400/10 active:scale-95 transition-all duration-200"
                        >
                            Explore Our Innovations
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>

                {/* Act number */}
                <div className="absolute bottom-8 right-8 md:right-14 text-white/4 font-display font-extrabold text-[7rem] leading-none select-none pointer-events-none">
                    02
                </div>
            </div>
        </section>
    );
}
