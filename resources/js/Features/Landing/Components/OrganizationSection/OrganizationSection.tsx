import { useRef } from "react";
import { usePage } from "@inertiajs/react";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import {
    act2Collage,
    act3Collage,
    head,
    hexItems,
    htech,
    rcmed,
} from "../../Data/organizationSection.data";
import { useOrganizationSectionAnimation } from "../../Hooks/useOrganizationSectionAnimation";
import ChapterIntroBlock from "./fragments/ChapterIntroBlock";
import ChapterIntroOrg from "./fragments/ChapterIntroOrg";
import LeaderProfileBlock from "./fragments/LeaderProfileBlock";
import MemberLedger from "./fragments/MemberLedger";
import PhotoCollage from "./fragments/PhotoCollage";
import ViewProfileLink from "./fragments/ViewProfileLink";

interface CollageSlot { url: string; sort_order: number; is_primary: boolean }
interface PersonProp {
    full: string; display: string[]; roleId: string; roleEn: string;
    desc: string | null; initials: string; image: string | null; href: string | null;
}
interface MemberProp { name: string; desc: string; initials: string; image: string | null; bio: string | null; href: string | null }
interface SectionProp {
    label_id: string; label_en: string;
    leader: PersonProp | null;
    members: MemberProp[];
    collage: CollageSlot[];
}

export default function OrganizationSection() {
    const sectionRef = useRef<HTMLElement>(null);
    const { t, lang } = useTranslation();

    // Roles and chapter labels are shown in BOTH languages by design (large glyph +
    // subtitle). Localising means leading with the active locale, not dropping one.
    const primary = (id: string, en: string) => (lang === "en" ? en : id);
    const secondary = (id: string, en: string) => (lang === "en" ? id : en);
    const bilingual = (id: string, en: string) => `${primary(id, en)} · ${secondary(id, en)}`;
    const { teamSections = [] } = usePage<{ props: { teamSections: SectionProp[] } }>().props as unknown as { teamSections: SectionProp[] };

    const act1 = teamSections[0] ?? null;
    const act2 = teamSections[1] ?? null;
    const act3 = teamSections[2] ?? null;

    // Merge DB leader data over static fallback shape
    const headData = act1?.leader
        ? { ...head, full: act1.leader.full, display: act1.leader.display, roleId: act1.leader.roleId, roleEn: act1.leader.roleEn, desc: act1.leader.desc ?? head.desc, initials: act1.leader.initials, image: act1.leader.image ?? head.image, href: act1.leader.href ?? undefined }
        : head;
    const toTeamMembers = (members: MemberProp[]) =>
        members.map((m) => ({ ...m, image: m.image ?? undefined, bio: m.bio ?? undefined, href: m.href ?? undefined }));

    // Bundled member copy is English source; DB-backed members arrive already resolved.
    const translateMembers = (members: typeof htech.members) =>
        members.map((m) => ({ ...m, desc: t(m.desc), bio: m.bio ? t(m.bio) : m.bio }));

    const htechData = act2
        ? { lead: { ...htech.lead, full: act2.leader?.full ?? htech.lead.full, display: act2.leader?.display ?? htech.lead.display, roleId: act2.leader?.roleId ?? htech.lead.roleId, roleEn: act2.leader?.roleEn ?? htech.lead.roleEn, desc: act2.leader?.desc ?? htech.lead.desc, initials: act2.leader?.initials ?? htech.lead.initials, image: act2.leader?.image ?? htech.lead.image, href: act2.leader?.href ?? undefined }, members: act2.members.length ? toTeamMembers(act2.members) : translateMembers(htech.members) }
        : { ...htech, members: translateMembers(htech.members) };
    const rcmedData = act3
        ? { lead: { ...rcmed.lead, full: act3.leader?.full ?? rcmed.lead.full, display: act3.leader?.display ?? rcmed.lead.display, roleId: act3.leader?.roleId ?? rcmed.lead.roleId, roleEn: act3.leader?.roleEn ?? rcmed.lead.roleEn, desc: act3.leader?.desc ?? rcmed.lead.desc, initials: act3.leader?.initials ?? rcmed.lead.initials, image: act3.leader?.image ?? rcmed.lead.image, href: act3.leader?.href ?? undefined }, members: act3.members.length ? toTeamMembers(act3.members) : translateMembers(rcmed.members) }
        : { ...rcmed, members: translateMembers(rcmed.members) };

    // Overlay DB images onto fixed layout slots — layout params (rot, top, left, etc.) stay hardcoded
    const resolvedHexItems = hexItems.map((item, i) => ({
        ...item,
        label: t(item.label),
        image: act1?.collage?.[i]?.url ?? item.image,
    }));
    const resolvedAct2Collage = act2Collage.map((item, i) => ({
        ...item,
        image: act2?.collage?.[i]?.url ?? item.image,
    }));
    const resolvedAct3Collage = act3Collage.map((item, i) => ({
        ...item,
        image: act3?.collage?.[i]?.url ?? item.image,
    }));

    useOrganizationSectionAnimation(sectionRef);

    return (
        <section
            ref={sectionRef}
            id="org"
            className="relative bg-surface-base"
            aria-labelledby="org-heading"
        >
            <h2 id="org-heading" className="sr-only">
                {t("IDIG Health Tech Leadership Team")}
            </h2>

            {/* Full-section honeycomb */}
            <div
                className="absolute inset-0 honeycomb-light opacity-[0.04] pointer-events-none"
                aria-hidden="true"
            />

            {/* ═══════════════════════════════════════════════════
                ACT 0 — Struktur Organisasi Introduction
               ═══════════════════════════════════════════════════ */}
            <div className="chapter-container act-0 relative overflow-hidden w-full md:h-screen">
                <ChapterIntroOrg
                    digitNum="00"
                    glyphText={t("Organisational Structure")}
                    subText="IDIG Health Tech"
                />
            </div>

            {/* ─────────────────────────────────────────────────────────
                ACT 1 — Kepala Laboratorium (2-column)
                ───────────────────────────────────────────────────────── */}
            <div className="chapter-container act-1 relative overflow-hidden w-full md:h-screen">
                <ChapterIntroBlock
                    digitNum="01"
                    glyphText="Kepala Laboratorium"
                    subText="IDIG Health Tech Leadership"
                />

                {/* Content Block */}
                <div className="chapter-content md:absolute md:inset-0 z-10 bg-surface-base flex flex-col justify-start md:justify-center px-[clamp(24px,7vw,120px)] py-[clamp(80px,14vh,120px)] md:py-0">
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
                            <LeaderProfileBlock
                                actKey="act-1"
                                align="left"
                                image={headData.image}
                                full={headData.full}
                                display={headData.display}
                                href={headData.href}
                            />

                            <div className="mb-2">
                                <span
                                    className="act-1-role font-body font-semibold tracking-[0.04em] text-secondary-500"
                                    style={{
                                        fontSize:
                                            "clamp(1rem, 1.35vw, 1.12rem)",
                                    }}
                                >
                                    {bilingual(headData.roleId, headData.roleEn)}
                                </span>
                                <div className="act-1-hairline h-px bg-secondary-500/30 mt-2 origin-left" />
                            </div>

                            <p
                                className="act-1-desc font-body text-slate-600 leading-[1.78] mt-6"
                                style={{
                                    fontSize: "clamp(0.92rem, 1.25vw, 1.02rem)",
                                    maxWidth: "48ch",
                                }}
                            >
                                {t(headData.desc)}
                            </p>

                            {headData.href && (
                                <ViewProfileLink href={headData.href} align="left" className="mt-4" />
                            )}
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
                            {resolvedHexItems.map((item, i) => (
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
            <div className="chapter-container act-2 relative overflow-hidden w-full md:h-screen">
                <ChapterIntroBlock
                    digitNum="02"
                    glyphText="IDIG HTECH"
                    subText="Institut Teknologi Sepuluh Nopember"
                />

                {/* Content Block */}
                <div className="chapter-content md:absolute md:inset-0 z-10 bg-surface-base flex flex-col justify-start md:justify-center px-[clamp(24px,7vw,120px)] py-[clamp(80px,14vh,120px)] md:py-0">
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
                                items={resolvedAct2Collage}
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
                            <LeaderProfileBlock
                                actKey="act-2"
                                align="right"
                                image={htechData.lead.image}
                                full={htechData.lead.full}
                                display={htechData.lead.display}
                                href={htechData.lead.href}
                            />

                            <div className="mb-2">
                                <span
                                    className="act-2-role font-body font-semibold tracking-[0.04em] text-secondary-500"
                                    style={{
                                        fontSize:
                                            "clamp(1rem, 1.35vw, 1.12rem)",
                                    }}
                                >
                                    {bilingual(htechData.lead.roleId, htechData.lead.roleEn)}
                                </span>
                                <div className="act-2-hairline h-px bg-secondary-500/30 mt-2 origin-right" />
                            </div>

                            <p
                                className="act-2-desc font-body text-slate-600 leading-[1.78] mt-4"
                                style={{
                                    fontSize: "clamp(0.88rem, 1.1vw, 0.96rem)",
                                    maxWidth: "48ch",
                                    marginLeft: "auto",
                                }}
                            >
                                {t(htechData.lead.desc)}
                            </p>

                            {htechData.lead.href && (
                                <ViewProfileLink href={htechData.lead.href} align="right" className="mt-4" />
                            )}

                            {/* Members — Manifest Ledger */}
                            <MemberLedger
                                members={htechData.members}
                                align="right"
                                memberClass="act-2-member"
                                connectorClass="act-2-connector"
                            />
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
            <div className="chapter-container act-3 relative overflow-hidden w-full md:h-screen">
                <ChapterIntroBlock
                    digitNum="03"
                    glyphText="IDIG RCMED"
                    subText="Universitas Airlangga"
                />

                {/* Content Block */}
                <div className="chapter-content md:absolute md:inset-0 z-10 bg-surface-base flex flex-col justify-start md:justify-center px-[clamp(24px,7vw,120px)] py-[clamp(80px,14vh,120px)] md:py-0">
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
                            <LeaderProfileBlock
                                actKey="act-3"
                                align="left"
                                image={rcmedData.lead.image}
                                full={rcmedData.lead.full}
                                display={rcmedData.lead.display}
                                href={rcmedData.lead.href}
                            />

                            <div className="mb-2">
                                <span
                                    className="act-3-role font-body font-semibold tracking-[0.04em] text-secondary-500"
                                    style={{
                                        fontSize:
                                            "clamp(1rem, 1.35vw, 1.12rem)",
                                    }}
                                >
                                    {bilingual(rcmedData.lead.roleId, rcmedData.lead.roleEn)}
                                </span>
                                <div className="act-3-hairline h-px bg-secondary-500/30 mt-2 origin-left" />
                            </div>

                            <p
                                className="act-3-desc font-body text-slate-600 leading-[1.78] mt-4"
                                style={{
                                    fontSize: "clamp(0.88rem, 1.1vw, 0.96rem)",
                                    maxWidth: "48ch",
                                }}
                            >
                                {t(rcmedData.lead.desc)}
                            </p>

                            {rcmedData.lead.href && (
                                <ViewProfileLink href={rcmedData.lead.href} align="left" className="mt-4" />
                            )}

                            {/* Members — Manifest Ledger */}
                            <MemberLedger
                                members={rcmedData.members}
                                align="left"
                                memberClass="act-3-member"
                                connectorClass="act-3-connector"
                                showGold
                            />
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
                                items={resolvedAct3Collage}
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

            {/* ─────────────────────────────────────────────────────────
                Dynamic Acts (4+) — driven by DB sections beyond the first 3
                ───────────────────────────────────────────────────────── */}
            {teamSections.slice(3).map((sec, i) => {
                const actNum = i + 4;
                const actKey = `act-${actNum}`;
                const chapterNum = String(actNum).padStart(2, "0");
                const isProfileLeft = i % 2 === 0;

                // Reuse act3/act2 collage layout params as position templates
                const collageTemplate = isProfileLeft ? act3Collage : act2Collage;
                const collageItems = collageTemplate.map((slot, j) => ({
                    ...slot,
                    image: sec.collage?.[j]?.url ?? slot.image,
                }));

                const { leader, members } = sec;

                return (
                    <div key={actKey} className={`chapter-container ${actKey} relative overflow-hidden w-full h-screen`}>
                        <ChapterIntroBlock
                            digitNum={chapterNum}
                            glyphText={primary(sec.label_id, sec.label_en)}
                            subText={secondary(sec.label_id, sec.label_en)}
                        />

                        <div className="chapter-content absolute inset-0 z-10 bg-surface-base flex flex-col justify-center px-[clamp(24px,7vw,120px)] py-[clamp(80px,14vh,120px)] md:py-0">
                            <div
                                className="absolute inset-y-0 pointer-events-none"
                                aria-hidden="true"
                                style={isProfileLeft
                                    ? { left: 0, width: "55vw", background: "radial-gradient(ellipse at -8% 50%, rgba(0,66,109,0.04) 0%, transparent 55%)" }
                                    : { right: 0, width: "45vw", background: "radial-gradient(ellipse at 110% 50%, rgba(0,66,109,0.06), transparent 62%)" }
                                }
                            />

                            <div className={`${actKey}-content relative z-10 grid grid-cols-1 ${isProfileLeft ? "lg:grid-cols-[1fr_auto]" : "lg:grid-cols-[auto_1fr]"} gap-12 lg:gap-[clamp(48px,6vw,96px)] items-center max-w-340 mx-auto w-full`}>
                                {isProfileLeft ? (
                                    <>
                                        {/* ── Left column: Profile ── */}
                                        <div>
                                            <div className={`${actKey}-eyebrow flex items-center gap-3 mb-12`}>
                                                <div className="w-8 h-px bg-secondary-500/30" />
                                                <span className="font-body font-semibold uppercase tracking-[0.3em] text-[0.68rem] text-primary-700/50">
                                                    {t("Chapter")} {chapterNum} — {primary(sec.label_id, sec.label_en)}
                                                </span>
                                            </div>
                                            {leader && (
                                                <>
                                                    <LeaderProfileBlock
                                                        actKey={actKey}
                                                        align="left"
                                                        image={leader.image ?? undefined}
                                                        full={leader.full}
                                                        display={leader.display}
                                                        href={leader.href ?? undefined}
                                                    />
                                                    <div className="mb-2">
                                                        <span className={`${actKey}-role font-body font-semibold tracking-[0.04em] text-secondary-500`} style={{ fontSize: "clamp(1rem, 1.35vw, 1.12rem)" }}>
                                                            {bilingual(leader.roleId, leader.roleEn)}
                                                        </span>
                                                        <div className={`${actKey}-hairline h-px bg-secondary-500/30 mt-2 origin-left`} />
                                                    </div>
                                                    {leader.desc && (
                                                        <p className={`${actKey}-desc font-body text-slate-600 leading-[1.78] mt-4`} style={{ fontSize: "clamp(0.88rem, 1.1vw, 0.96rem)", maxWidth: "48ch" }}>
                                                            {leader.desc}
                                                        </p>
                                                    )}
                                                    {leader.href && (
                                                        <ViewProfileLink href={leader.href} align="left" className="mt-4" />
                                                    )}
                                                </>
                                            )}
                                            {members.length > 0 && (
                                                <MemberLedger members={members as any} align="left" memberClass={`${actKey}-member`} connectorClass={`${actKey}-connector`} />
                                            )}
                                        </div>

                                        {/* ── Right column: Collage ── */}
                                        <div className={`${actKey}-collage relative hidden lg:block`} style={{ width: "clamp(300px, 35vw, 500px)", height: "clamp(350px, 40vw, 600px)" }} aria-hidden="true">
                                            <PhotoCollage items={collageItems} centerClass={`${actKey}-collage-center`} itemClass={`${actKey}-collage-item`} />
                                        </div>
                                    </>
                                ) : (
                                    <>
                                        {/* ── Left column: Collage ── */}
                                        <div className={`${actKey}-collage relative hidden lg:block`} style={{ width: "clamp(300px, 35vw, 500px)", height: "clamp(350px, 40vw, 600px)" }} aria-hidden="true">
                                            <PhotoCollage items={collageItems} centerClass={`${actKey}-collage-center`} itemClass={`${actKey}-collage-item`} />
                                        </div>

                                        {/* ── Right column: Profile ── */}
                                        <div className="text-right">
                                            <div className={`${actKey}-eyebrow flex items-center gap-3 mb-12 justify-end`}>
                                                <span className="font-body font-semibold uppercase tracking-[0.3em] text-[0.68rem] text-primary-700/50">
                                                    {t("Chapter")} {chapterNum} — {primary(sec.label_id, sec.label_en)}
                                                </span>
                                                <div className="w-8 h-px bg-secondary-500/30" />
                                            </div>
                                            {leader && (
                                                <>
                                                    <LeaderProfileBlock
                                                        actKey={actKey}
                                                        align="right"
                                                        image={leader.image ?? undefined}
                                                        full={leader.full}
                                                        display={leader.display}
                                                        href={leader.href ?? undefined}
                                                    />
                                                    <div className="mb-2">
                                                        <span className={`${actKey}-role font-body font-semibold tracking-[0.04em] text-secondary-500`} style={{ fontSize: "clamp(1rem, 1.35vw, 1.12rem)" }}>
                                                            {bilingual(leader.roleId, leader.roleEn)}
                                                        </span>
                                                        <div className={`${actKey}-hairline h-px bg-secondary-500/30 mt-2 origin-right`} />
                                                    </div>
                                                    {leader.desc && (
                                                        <p className={`${actKey}-desc font-body text-slate-600 leading-[1.78] mt-4`} style={{ fontSize: "clamp(0.88rem, 1.1vw, 0.96rem)", maxWidth: "48ch", marginLeft: "auto" }}>
                                                            {leader.desc}
                                                        </p>
                                                    )}
                                                    {leader.href && (
                                                        <ViewProfileLink href={leader.href} align="right" className="mt-4" />
                                                    )}
                                                </>
                                            )}
                                            {members.length > 0 && (
                                                <MemberLedger members={members as any} align="right" memberClass={`${actKey}-member`} connectorClass={`${actKey}-connector`} />
                                            )}
                                        </div>
                                    </>
                                )}
                            </div>

                            <div
                                className={`absolute bottom-8 ${isProfileLeft ? "right-10 md:right-14" : "left-10 md:left-14"} font-display font-extrabold text-primary-700/[0.04] select-none pointer-events-none leading-none`}
                                style={{ fontSize: "clamp(6rem, 15vw, 13rem)" }}
                                aria-hidden="true"
                            >
                                {chapterNum}
                            </div>
                        </div>
                    </div>
                );
            })}
        </section>
    );
}
