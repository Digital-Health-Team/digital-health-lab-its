import { CSSProperties, ReactNode } from "react";
import { TeamMember } from "../../../Types/organizationSection.type";
import ViewProfileLink from "./ViewProfileLink";

interface MemberLedgerRowProps {
    member: TeamMember;
    memberClass: string;
    /** False for CMS sections with no org data — the third column becomes free text. */
    showOrgColumns: boolean;
}

/**
 * One roster entry as a table row: code · (photo + name + profile link) · unit · department.
 *
 * Unit and department are separate cells labelled once by the table header rather than
 * run together unlabelled, and cells wrap freely — the act hosting this is not pinned to
 * one viewport, so a long department list takes a second line instead of being clipped.
 *
 * The row is NOT a link. ViewProfileLink is an anchor, and an anchor inside an anchor is
 * invalid, so the explicit button is the single interactive target — which also beats a
 * four-column-wide link for anyone navigating by keyboard or screen reader.
 *
 * Hover/focus styling lives in `.ledger-row` (public.css), which also owns the grid
 * template. Keep resting background/box-shadow out of the inline style — inline beats
 * the stylesheet and would kill :hover — and keep that rule off transform/opacity, which
 * GSAP writes inline here via the act's `.act-N-member` reveal.
 */
export default function MemberLedgerRow({
    member,
    memberClass,
    showOrgColumns,
}: MemberLedgerRowProps) {
    const units = member.units ?? [];
    const departments = member.departments ?? [];

    return (
        <div className={`ledger-row ${memberClass}`}>
            {/* Org code. Hidden from AT — a reader spelling "I-Q-B" is noise, and the
                name in the next cell already identifies the row. */}
            <span aria-hidden="true" style={{ ...codeStyle, gridArea: "code" }}>
                {member.initials}
            </span>

            <span
                style={{
                    gridArea: "name",
                    display: "flex",
                    alignItems: "flex-start",
                    gap: "clamp(8px, 0.8vw, 12px)",
                    minWidth: 0,
                }}
            >
                <MemberAvatar member={member} />

                <span
                    style={{
                        display: "flex",
                        flexDirection: "column",
                        alignItems: "flex-start",
                        minWidth: 0,
                        gap: 2,
                    }}
                >
                    <span
                        className="ledger-row-name"
                        title={member.fullName ?? member.name}
                        style={{
                            fontFamily: "'Plus Jakarta Sans', sans-serif",
                            fontWeight: 700,
                            fontStyle: "italic",
                            fontSize: "clamp(0.92rem, 1.05vw, 1.02rem)",
                            letterSpacing: "-0.015em",
                            color: "#1E293B",
                            maxWidth: "100%",
                            overflow: "hidden",
                            textOverflow: "ellipsis",
                            whiteSpace: "nowrap",
                        }}
                    >
                        {member.name}
                    </span>

                    {member.role && (
                        <span
                            style={{
                                fontFamily:
                                    "'Inter', ui-sans-serif, sans-serif",
                                fontWeight: 600,
                                fontSize: "0.58rem",
                                textTransform: "uppercase",
                                letterSpacing: "0.14em",
                                color: "#00A8B5",
                            }}
                        >
                            {member.role}
                        </span>
                    )}

                    {member.href && (
                        <ViewProfileLink
                            href={member.href}
                            ariaLabel={`${member.fullName ?? member.name} — profil`}
                            className="mt-1"
                        />
                    )}
                </span>
            </span>

            {showOrgColumns ? (
                <>
                    <Cell area="unit" label="Unit" items={units} />
                    <Cell area="dept" label="Departemen" items={departments} />
                </>
            ) : (
                <span style={{ ...cellStyle, gridArea: "unit" }}>
                    {member.desc}
                </span>
            )}
        </div>
    );
}

/**
 * Circular portrait, falling back to the first letter of the short name.
 *
 * Deliberately not `member.initials` — that field doubles as the org code and is already
 * printed in the first column, so reusing it here would just repeat "IQB" twice per row.
 */
function MemberAvatar({ member }: { member: TeamMember }) {
    return (
        <span
            aria-hidden="true"
            style={{
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                width: "clamp(30px, 2.4vw, 38px)",
                height: "clamp(30px, 2.4vw, 38px)",
                borderRadius: "50%",
                background: "oklch(0.42 0.10 240 / 0.07)",
                border: "1px solid oklch(0.42 0.10 240 / 0.14)",
                overflow: "hidden",
                flexShrink: 0,
                marginTop: 1,
            }}
        >
            {member.image ? (
                <img
                    src={member.image}
                    alt=""
                    loading="lazy"
                    style={{
                        width: "100%",
                        height: "100%",
                        objectFit: "cover",
                        display: "block",
                    }}
                />
            ) : (
                <span
                    style={{
                        fontFamily: "'Plus Jakarta Sans', sans-serif",
                        fontWeight: 800,
                        fontStyle: "italic",
                        fontSize: "0.8rem",
                        letterSpacing: "-0.01em",
                        color: "oklch(0.22 0.06 240 / 0.6)",
                        userSelect: "none",
                    }}
                >
                    {member.name.charAt(0).toUpperCase()}
                </span>
            )}
        </span>
    );
}

/**
 * One data cell. The label only shows below lg — above it, the table header says the
 * same thing once for the whole column.
 */
function Cell({
    area,
    label,
    items,
}: {
    area: string;
    label: string;
    items: string[];
}): ReactNode {
    if (items.length === 0) return <span style={{ gridArea: area }} />;

    return (
        <span style={{ gridArea: area, minWidth: 0 }}>
            <span
                className="lg:hidden"
                style={{
                    display: "block",
                    fontFamily: "'Inter', ui-sans-serif, sans-serif",
                    fontWeight: 600,
                    fontSize: "0.55rem",
                    textTransform: "uppercase",
                    letterSpacing: "0.18em",
                    color: "#94A3B8",
                    marginBottom: 2,
                }}
            >
                {label}
            </span>
            <span style={cellStyle}>{items.join(" · ")}</span>
        </span>
    );
}

const codeStyle: CSSProperties = {
    fontFamily: "'Plus Jakarta Sans', sans-serif",
    fontWeight: 700,
    fontSize: "0.72rem",
    fontVariantNumeric: "tabular-nums lining-nums",
    letterSpacing: "0.06em",
    color: "oklch(0.42 0.10 240 / 0.6)",
    userSelect: "none",
    marginTop: 10,
};

const cellStyle: CSSProperties = {
    fontFamily: "'Inter', ui-sans-serif, sans-serif",
    fontWeight: 400,
    fontSize: "clamp(0.72rem, 0.8vw, 0.8rem)",
    lineHeight: 1.45,
    // #475569 on the section's #F8F9FA surface is ~7.5:1 — the old 9px #64748B run-on
    // line was the least readable thing on the page.
    color: "#475569",
    // Rows are locked to --roster-row inside the cycling window, so a third line would
    // be clipped mid-glyph. Two lines hold every department list in the roster.
    display: "-webkit-box",
    WebkitLineClamp: 2,
    WebkitBoxOrient: "vertical",
    overflow: "hidden",
};
