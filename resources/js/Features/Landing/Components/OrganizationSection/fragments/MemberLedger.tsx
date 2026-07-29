import { CSSProperties, useEffect, useRef, useState } from "react";
import {
    ROSTER_ROW_HEIGHT,
    ROSTER_VISIBLE_ROWS,
} from "../../../Data/organizationSection.data";
import { TeamMember } from "../../../Types/organizationSection.type";
import MemberLedgerRow from "./MemberLedgerRow";

interface MemberLedgerProps {
    members: TeamMember[];
    align: "left" | "right";
    memberClass: string;
    connectorClass: string;
    showGold?: boolean;
    /**
     * Run the rows through a fixed, stepping window instead of listing them all.
     *
     * Only for acts whose timeline actually advances `.roster-track` — without that,
     * the window would clip everything past the sixth row with no way to reach it.
     * CMS-created acts have no such timeline, so they list normally.
     */
    windowed?: boolean;
}

/**
 * The roster table: one labelled row per person.
 *
 * The column headers are the point — Unit and Departemen are named once here instead of
 * every row repeating them or, worse, running them together unlabelled.
 *
 * At md+ the rows run through a fixed window that shows ROSTER_VISIBLE_ROWS at a time;
 * the animation hook steps `.roster-track` up exactly one row per scroll beat, so the
 * top rows leave as the bottom ones arrive and the whole act still fits 100vh. Below md
 * the window is dropped and all rows simply stack.
 *
 * The mask softens both edges so a row caught mid-step doesn't look hard-cut.
 */
export default function MemberLedger({
    members,
    align,
    memberClass,
    connectorClass,
    showGold = false,
    windowed = false,
}: MemberLedgerProps) {
    const [railH, setRailH] = useState(200);
    const viewportRef = useRef<HTMLDivElement>(null);

    // The rail spans the window, not the track — so its drawn length must be the
    // window's measured height. A hardcoded path length would finish drawing long
    // before the visible portion did.
    useEffect(() => {
        const el = viewportRef.current;
        if (!el) return;
        const ro = new ResizeObserver(([entry]) => {
            setRailH(entry.contentRect.height);
        });
        ro.observe(el);
        return () => ro.disconnect();
    }, []);

    const isRight = align === "right";
    const railEdge = isRight ? "left" : "right";

    // CMS-created sections carry no org data; their rows fall back to a single free-text
    // column, so the header shouldn't promise a Unit and a Departemen that aren't there.
    const showOrgColumns = members.some(
        (m) => (m.units?.length ?? 0) > 0 || (m.departments?.length ?? 0) > 0,
    );

    return (
        <div
            style={{
                position: "relative",
                marginTop: "clamp(18px, 2.4vh, 30px)",
            }}
        >
            {/* Column headers sit outside the window so they don't scroll with the track.
                Hidden below lg, where each cell labels itself — see MemberLedgerRow. */}
            <div
                className="ledger-head hidden lg:grid"
                style={{
                    gap: "6px clamp(10px, 1vw, 16px)",
                    padding: "0 clamp(8px, 1.2vw, 14px) 8px",
                    borderBottom: "1px solid oklch(0.42 0.10 240 / 0.14)",
                }}
                aria-hidden="true"
            >
                <span style={{ ...headerLabelStyle, gridArea: "code" }}>
                    KODE
                </span>
                <span style={{ ...headerLabelStyle, gridArea: "name" }}>
                    ANGGOTA
                </span>
                {showOrgColumns && (
                    <>
                        <span style={{ ...headerLabelStyle, gridArea: "unit" }}>
                            UNIT
                        </span>
                        <span style={{ ...headerLabelStyle, gridArea: "dept" }}>
                            DEPARTEMEN
                        </span>
                    </>
                )}
            </div>

            <div
                ref={viewportRef}
                className={
                    windowed
                        ? "roster-viewport relative md:h-[calc(var(--roster-row)*var(--roster-visible))] md:overflow-hidden md:[mask-image:linear-gradient(to_bottom,transparent_0%,#000_8%,#000_92%,transparent_100%)]"
                        : "roster-viewport relative"
                }
                style={
                    // Unset when not windowed, so .ledger-row's `var(--roster-row, auto)`
                    // falls back to content height.
                    windowed
                        ? ({
                              "--roster-row": ROSTER_ROW_HEIGHT,
                              "--roster-visible": ROSTER_VISIBLE_ROWS,
                          } as CSSProperties)
                        : undefined
                }
            >
                {/* Rail — a sibling of the track, so it stays put while the track steps */}
                <svg
                    style={{
                        position: "absolute",
                        top: 0,
                        [railEdge]: 0,
                        width: 1,
                        height: railH,
                        overflow: "visible",
                        pointerEvents: "none",
                        zIndex: 0,
                    }}
                    aria-hidden="true"
                >
                    <path
                        className={connectorClass}
                        d={`M 0.5 0 V ${railH}`}
                        stroke="#062E5C"
                        strokeOpacity="0.22"
                        strokeWidth="1"
                        fill="none"
                    />
                </svg>

                <div className="roster-track">
                    {members.map((member, i) => (
                        <MemberLedgerRow
                            key={i}
                            member={member}
                            memberClass={memberClass}
                            showOrgColumns={showOrgColumns}
                        />
                    ))}
                </div>
            </div>

            {/* ITS Gold flourish */}
            {showGold && (
                <div
                    data-gold
                    style={{
                        position: "absolute",
                        bottom: -0.5,
                        [railEdge]: 0,
                        height: 1,
                        width: "clamp(48px, 6vw, 96px)",
                        background: "#FFC72C",
                        transformOrigin: railEdge,
                    }}
                />
            )}
        </div>
    );
}

const headerLabelStyle: React.CSSProperties = {
    fontFamily: "'Inter', ui-sans-serif, sans-serif",
    fontWeight: 600,
    fontSize: "0.58rem",
    textTransform: "uppercase",
    letterSpacing: "0.26em",
    color: "#64748B",
    userSelect: "none",
};
