import { useRef } from "react";
import {
    ACT2_COLLAGE,
    ACT3_COLLAGE,
    HEAD,
    HEX_ITEMS,
    HTECH,
    RCMED,
    SPINE_ROW_H,
} from "../../Constants/orgChartData";
import { useOrgChartAnimations } from "../../Hooks/useOrgChartAnimations";
import ChapterIntroBlock from "./fragments/ChapterIntroBlock";
import MemberRow from "./fragments/MemberRow";
import PhotoCollage from "./fragments/PhotoCollage";

export default function OrganizationSection() {
    const sectionRef = useRef<HTMLElement>(null);

    useOrgChartAnimations(sectionRef);

    const htechSpineH = HTECH.members.length * SPINE_ROW_H;
    const rcmedSpineH = RCMED.members.length * SPINE_ROW_H;

    return (
        <section
            ref={sectionRef}
            id="org"
            className="relative bg-surface-base"
            aria-labelledby="org-heading"
        >
            <h2 id="org-heading" className="sr-only">
                Tim Kepemimpinan IDIG Health Tech — IDIG Health Tech Leadership Team
            </h2>

            {/* Full-section honeycomb */}
            <div
                className="absolute inset-0 honeycomb-light opacity-[0.04] pointer-events-none"
                aria-hidden="true"
            />

            {/* ─────────────────────────────────────────────────────────
                ACT 1 — Kepala Laboratorium (2-column)
                ───────────────────────────────────────────────────────── */}
            <div className="chapter-container act-1 relative overflow-hidden w-full h-screen">
                <ChapterIntroBlock
                    digitNum="01"
                    glyphText="Kepala Laboratorium"
                    subText="IDIG Health Tech Leadership"
                />

                {/* Content Block */}
                <div className="chapter-content absolute inset-0 z-10 bg-surface-base flex flex-col justify-center px-[clamp(24px,7vw,120px)] py-[clamp(80px,14vh,120px)] md:py-0">
                    <div
                        className="absolute inset-y-0 left-0 w-[55vw] pointer-events-none"
                        aria-hidden="true"
                        style={{
                            background:
                                "radial-gradient(ellipse at -10% 50%, rgba(0,66,109,0.04) 0%, transparent 55%)",
                        }}
                    />

                    {/* Two-column grid */}
                    <div className="act-1-content relative z-10 grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-12 lg:gap-[clamp(48px,6vw,96px)] items-center max-w-340 mx-auto w-full">
                        {/* ── Left column: Profile ── */}
                        <div>
                            <div className="act-1-eyebrow flex items-center gap-3 mb-10">
                                <div className="w-8 h-px bg-secondary-500/30" />
                                <span className="font-body font-semibold uppercase tracking-[0.3em] text-[0.68rem] text-primary-700/50">
                                    Chapter 01 — Kepala Laboratorium
                                </span>
                            </div>

                            {/* Profile: avatar + text */}
                            <div className="flex flex-row items-center gap-6 mb-8">
                                <div className="act-1-avatar w-[clamp(4.4rem,12.8vw,10.1rem)] h-[clamp(4.4rem,12.8vw,10.1rem)] rounded-full bg-primary-700/[0.07] border border-primary-700/10 flex items-center justify-center shrink-0 overflow-hidden relative">
                                    <img
                                        src={HEAD.image}
                                        alt={HEAD.full}
                                        className="absolute inset-0 w-full h-full object-cover"
                                    />
                                </div>
                                <div className="min-w-0">
                                    <h3
                                        className="font-display font-extrabold italic leading-[0.92] tracking-[-0.02em] text-primary-900"
                                        style={{ fontSize: "clamp(2.4rem, 7vw, 5.5rem)" }}
                                        aria-label={HEAD.full}
                                    >
                                        {HEAD.display.map((word, i) => (
                                            <span
                                                key={i}
                                                className="block overflow-hidden pb-[0.3em] -mb-[0.3em] pt-[0.3em] -mt-[0.3em] px-[0.1em] -mx-[0.1em]"
                                            >
                                                <span className="block act-1-word">{word}</span>
                                            </span>
                                        ))}
                                    </h3>
                                </div>
                            </div>

                            <div className="mb-2">
                                <span
                                    className="act-1-role font-body font-semibold tracking-[0.04em] text-secondary-500"
                                    style={{ fontSize: "clamp(1rem, 1.35vw, 1.12rem)" }}
                                >
                                    {HEAD.roleId} · {HEAD.roleEn}
                                </span>
                                <div className="act-1-hairline h-px bg-secondary-500/30 mt-2 origin-left" />
                            </div>

                            <p
                                className="act-1-desc font-body text-slate-600 leading-[1.78] mt-6"
                                style={{ fontSize: "clamp(0.92rem, 1.25vw, 1.02rem)", maxWidth: "48ch" }}
                            >
                                {HEAD.desc}
                            </p>
                        </div>

                        {/* ── Right column: Pinwheel photo collage ── */}
                        <div
                            className="act-1-hexgrid relative hidden lg:block"
                            style={{
                                width: "clamp(300px, 30vw, 460px)",
                                height: "clamp(300px, 30vw, 460px)",
                            }}
                            aria-hidden="true"
                        >
                            {HEX_ITEMS.map((item, i) => (
                                <div
                                    key={i}
                                    className={`${item.center ? "act-1-hex-center" : "act-1-hex-item"} absolute group`}
                                    style={{
                                        left: `${item.cx}%`,
                                        top: `${item.cy}%`,
                                        width: `${item.size}%`,
                                        aspectRatio: "1 / 1",
                                        transform: `translate(-50%, -50%) rotate(${item.rot}deg)`,
                                        padding: "clamp(3px, 0.4vw, 5px)",
                                        background: "white",
                                        boxShadow: item.center
                                            ? "0 8px 32px rgba(0,66,109,0.28), 0 2px 8px rgba(0,66,109,0.14)"
                                            : "0 4px 18px rgba(0,66,109,0.22), 0 1px 5px rgba(0,66,109,0.10)",
                                        zIndex: item.center ? 10 : 2,
                                    }}
                                >
                                    <div className="relative w-full h-full overflow-hidden">
                                        <img
                                            src={item.image}
                                            alt={item.label}
                                            className="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110"
                                        />
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    <div
                        className="absolute bottom-8 right-10 md:right-14 font-display font-extrabold text-primary-700/[0.04] select-none pointer-events-none leading-none"
                        style={{ fontSize: "clamp(6rem, 15vw, 13rem)" }}
                        aria-hidden="true"
                    >
                        01
                    </div>
                </div>
            </div>

            {/* ─────────────────────────────────────────────────────────
                ACT 2 — IDIG HTECH
                ───────────────────────────────────────────────────────── */}
            <div className="chapter-container act-2 relative overflow-hidden w-full h-screen">
                <ChapterIntroBlock
                    digitNum="02"
                    glyphText="IDIG HTECH"
                    subText="Biomedical Instrumentation"
                />

                {/* Content Block */}
                <div className="chapter-content absolute inset-0 z-10 bg-surface-base flex flex-col justify-center px-[clamp(24px,7vw,120px)] py-[clamp(80px,14vh,120px)] md:py-0">
                    <div
                        className="absolute inset-y-0 right-0 w-[45vw] pointer-events-none"
                        aria-hidden="true"
                        style={{
                            background:
                                "radial-gradient(ellipse at 110% 50%, rgba(0,66,109,0.06), transparent 62%)",
                        }}
                    />

                    <div className="act-2-content relative z-10 grid grid-cols-1 lg:grid-cols-[auto_1fr] gap-12 lg:gap-[clamp(48px,6vw,96px)] items-center max-w-340 mx-auto w-full">
                        {/* ── Left column: Collage ── */}
                        <div
                            className="act-2-collage relative hidden lg:block"
                            style={{
                                width: "clamp(300px, 35vw, 500px)",
                                height: "clamp(350px, 40vw, 600px)",
                            }}
                            aria-hidden="true"
                        >
                            <PhotoCollage
                                items={ACT2_COLLAGE}
                                centerClass="act-2-collage-center"
                                itemClass="act-2-collage-item"
                            />
                        </div>

                        {/* ── Right column: Profile ── */}
                        <div className="text-right">
                            <div className="act-2-eyebrow flex items-center gap-3 mb-12 justify-end">
                                <span className="font-body font-semibold uppercase tracking-[0.3em] text-[0.68rem] text-primary-700/50">
                                    Chapter 02 — Tim IDIG HTECH
                                </span>
                                <div className="w-8 h-px bg-secondary-500/30" />
                            </div>

                            {/* Profile */}
                            <div className="flex flex-row items-center justify-end gap-6 mb-8">
                                <div className="min-w-0">
                                    <h3
                                        className="font-display font-extrabold italic leading-[0.92] tracking-[-0.02em] text-primary-900 text-right"
                                        style={{ fontSize: "clamp(2.4rem, 7vw, 5.5rem)" }}
                                        aria-label={HTECH.lead.full}
                                    >
                                        {HTECH.lead.display.map((word, i) => (
                                            <span
                                                key={i}
                                                className="block overflow-hidden pb-[0.3em] -mb-[0.3em] pt-[0.3em] -mt-[0.3em] px-[0.1em] -mx-[0.1em]"
                                            >
                                                <span className="block act-2-word">{word}</span>
                                            </span>
                                        ))}
                                    </h3>
                                </div>
                                <div className="act-2-avatar w-[clamp(4.4rem,12.8vw,10.1rem)] h-[clamp(4.4rem,12.8vw,10.1rem)] rounded-full bg-primary-700/[0.07] border border-primary-700/10 flex items-center justify-center shrink-0 overflow-hidden relative">
                                    <img
                                        src={HTECH.lead.image}
                                        alt={HTECH.lead.full}
                                        className="absolute inset-0 w-full h-full object-cover"
                                    />
                                </div>
                            </div>

                            <div className="mb-2">
                                <span
                                    className="act-2-role font-body font-semibold tracking-[0.04em] text-secondary-500"
                                    style={{ fontSize: "clamp(1rem, 1.35vw, 1.12rem)" }}
                                >
                                    {HTECH.lead.roleId} · {HTECH.lead.roleEn}
                                </span>
                                <div className="act-2-hairline h-px bg-secondary-500/30 mt-2 origin-right" />
                            </div>

                            <p
                                className="act-2-desc font-body text-slate-600 leading-[1.78] mt-4"
                                style={{ fontSize: "clamp(0.88rem, 1.1vw, 0.96rem)", maxWidth: "48ch", marginLeft: "auto" }}
                            >
                                {HTECH.lead.desc}
                            </p>

                            {/* Members + right-side spine */}
                            <div className="relative mt-[clamp(40px,6vh,72px)]" style={{ paddingRight: "10px" }}>
                                <svg
                                    className="absolute right-0 top-0 pointer-events-none"
                                    width="1"
                                    height={htechSpineH}
                                    style={{ overflow: "visible" }}
                                    aria-hidden="true"
                                >
                                    <path
                                        className="act-2-spine"
                                        d={`M 0.5 0 L 0.5 ${htechSpineH}`}
                                        stroke="#00A8B5"
                                        strokeWidth="1"
                                        fill="none"
                                        opacity="0.25"
                                    />
                                </svg>

                                {HTECH.members.map((member, i) => (
                                    <MemberRow
                                        key={i}
                                        member={member}
                                        align="right"
                                        memberClass="act-2-member"
                                    />
                                ))}
                            </div>
                        </div>
                    </div>

                    <div
                        className="absolute bottom-8 left-10 md:left-14 font-display font-extrabold text-primary-700/[0.04] select-none pointer-events-none leading-none"
                        style={{ fontSize: "clamp(6rem, 15vw, 13rem)" }}
                        aria-hidden="true"
                    >
                        02
                    </div>
                </div>
            </div>

            {/* ─────────────────────────────────────────────────────────
                ACT 3 — IDIG RCMED
                ───────────────────────────────────────────────────────── */}
            <div className="chapter-container act-3 relative overflow-hidden w-full h-screen">
                <ChapterIntroBlock
                    digitNum="03"
                    glyphText="IDIG RCMED"
                    subText="Computational Medicine"
                />

                {/* Content Block */}
                <div className="chapter-content absolute inset-0 z-10 bg-surface-base flex flex-col justify-center px-[clamp(24px,7vw,120px)] py-[clamp(80px,14vh,120px)] md:py-0">
                    <div
                        className="absolute inset-y-0 left-0 w-[55vw] pointer-events-none"
                        aria-hidden="true"
                        style={{
                            background:
                                "radial-gradient(ellipse at -8% 50%, rgba(0,66,109,0.04) 0%, transparent 55%)",
                        }}
                    />

                    <div className="act-3-content relative z-10 grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-12 lg:gap-[clamp(48px,6vw,96px)] items-center max-w-340 mx-auto w-full">
                        {/* ── Left column: Profile ── */}
                        <div>
                            <div className="act-3-eyebrow flex items-center gap-3 mb-12">
                                <div className="w-8 h-px bg-secondary-500/30" />
                                <span className="font-body font-semibold uppercase tracking-[0.3em] text-[0.68rem] text-primary-700/50">
                                    Chapter 03 — Tim IDIG RCMED
                                </span>
                            </div>

                            {/* Profile */}
                            <div className="flex flex-row items-center gap-6 mb-8">
                                <div className="act-3-avatar w-[clamp(4.4rem,12.8vw,10.1rem)] h-[clamp(4.4rem,12.8vw,10.1rem)] rounded-full bg-primary-700/[0.07] border border-primary-700/10 flex items-center justify-center shrink-0 overflow-hidden relative">
                                    <img
                                        src={RCMED.lead.image}
                                        alt={RCMED.lead.full}
                                        className="absolute inset-0 w-full h-full object-cover"
                                    />
                                </div>
                                <div className="min-w-0">
                                    <h3
                                        className="font-display font-extrabold italic leading-[0.92] tracking-[-0.02em] text-primary-900"
                                        style={{ fontSize: "clamp(2.4rem, 7vw, 5.5rem)" }}
                                        aria-label={RCMED.lead.full}
                                    >
                                        {RCMED.lead.display.map((word, i) => (
                                            <span
                                                key={i}
                                                className="block overflow-hidden pb-[0.3em] -mb-[0.3em] pt-[0.3em] -mt-[0.3em] px-[0.1em] -mx-[0.1em]"
                                            >
                                                <span className="block act-3-word">{word}</span>
                                            </span>
                                        ))}
                                    </h3>
                                </div>
                            </div>

                            <div className="mb-2">
                                <span
                                    className="act-3-role font-body font-semibold tracking-[0.04em] text-secondary-500"
                                    style={{ fontSize: "clamp(1rem, 1.35vw, 1.12rem)" }}
                                >
                                    {RCMED.lead.roleId} · {RCMED.lead.roleEn}
                                </span>
                                <div className="act-3-hairline h-px bg-secondary-500/30 mt-2 origin-left" />
                            </div>

                            <p
                                className="act-3-desc font-body text-slate-600 leading-[1.78] mt-4"
                                style={{ fontSize: "clamp(0.88rem, 1.1vw, 0.96rem)", maxWidth: "48ch" }}
                            >
                                {RCMED.lead.desc}
                            </p>

                            {/* Members + left-side spine */}
                            <div className="relative mt-[clamp(40px,6vh,72px)]" style={{ paddingLeft: "10px" }}>
                                <svg
                                    className="absolute left-0 top-0 pointer-events-none"
                                    width="1"
                                    height={rcmedSpineH}
                                    style={{ overflow: "visible" }}
                                    aria-hidden="true"
                                >
                                    <path
                                        className="act-3-spine"
                                        d={`M 0.5 0 L 0.5 ${rcmedSpineH}`}
                                        stroke="#00A8B5"
                                        strokeWidth="1"
                                        fill="none"
                                        opacity="0.25"
                                    />
                                </svg>

                                {RCMED.members.map((member, i) => (
                                    <MemberRow
                                        key={i}
                                        member={member}
                                        align="left"
                                        memberClass="act-3-member"
                                    />
                                ))}

                                {/* ITS Gold terminal flourish */}
                                <div
                                    data-gold
                                    className="mt-10 origin-left"
                                    style={{ height: "1px", background: "#FFC72C", width: "8rem" }}
                                />
                            </div>
                        </div>

                        {/* ── Right column: Collage ── */}
                        <div
                            className="act-3-collage relative hidden lg:block"
                            style={{
                                width: "clamp(300px, 35vw, 500px)",
                                height: "clamp(350px, 40vw, 600px)",
                            }}
                            aria-hidden="true"
                        >
                            <PhotoCollage
                                items={ACT3_COLLAGE}
                                centerClass="act-3-collage-center"
                                itemClass="act-3-collage-item"
                            />
                        </div>
                    </div>

                    <div
                        className="absolute bottom-8 right-10 md:right-14 font-display font-extrabold text-primary-700/[0.04] select-none pointer-events-none leading-none"
                        style={{ fontSize: "clamp(6rem, 15vw, 13rem)" }}
                        aria-hidden="true"
                    >
                        03
                    </div>
                </div>
            </div>
        </section>
    );
}
