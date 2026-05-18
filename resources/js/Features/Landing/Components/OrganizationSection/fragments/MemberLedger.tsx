import { useCallback, useEffect, useRef, useState } from "react";
import { TeamMember } from "../../Types/organizationSection.type";
import MemberLedgerRow from "./MemberLedgerRow";

interface MemberLedgerProps {
    members: TeamMember[];
    align: "left" | "right";
    memberClass: string;
    connectorClass: string;
    showGold?: boolean;
}

const PANEL_ID = "ledger-margin-note";
const PANEL_H = 210;
const ease = "cubic-bezier(0.25, 1, 0.5, 1)";

// Shared helper — renders a circular photo or initials at any size
function MemberAvatar({
    member,
    size,
}: {
    member: TeamMember;
    size: number;
}) {
    return (
        <span
            aria-hidden="true"
            style={{
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                width: size,
                height: size,
                borderRadius: "50%",
                background: "oklch(0.42 0.10 240 / 0.07)",
                border: "1.5px solid oklch(0.42 0.10 240 / 0.14)",
                overflow: "hidden",
                flexShrink: 0,
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
                        fontSize: size * 0.28,
                        letterSpacing: "-0.01em",
                        color: "oklch(0.22 0.06 240 / 0.65)",
                        userSelect: "none",
                    }}
                >
                    {member.initials}
                </span>
            )}
        </span>
    );
}

export default function MemberLedger({
    members,
    align,
    memberClass,
    connectorClass,
    showGold = false,
}: MemberLedgerProps) {
    const [activeIndex, setActiveIndex] = useState<number | null>(null);
    const [railH, setRailH] = useState(200);
    const containerRef = useRef<HTMLDivElement>(null);
    const rowMidYs = useRef<Record<number, number>>({});

    useEffect(() => {
        const el = containerRef.current;
        if (!el) return;
        const ro = new ResizeObserver(([entry]) => {
            setRailH(entry.contentRect.height);
        });
        ro.observe(el);
        return () => ro.disconnect();
    }, []);

    useEffect(() => {
        if (activeIndex === null) return;
        const handler = (e: PointerEvent) => {
            if (
                containerRef.current &&
                !containerRef.current.contains(e.target as Node)
            ) {
                setActiveIndex(null);
            }
        };
        document.addEventListener("pointerdown", handler);
        return () => document.removeEventListener("pointerdown", handler);
    }, [activeIndex]);

    const rowRefCallback = useCallback(
        (i: number) => (el: HTMLButtonElement | null) => {
            if (!el) return;
            rowMidYs.current[i] = el.offsetTop + el.offsetHeight / 2;
        },
        [],
    );

    const isRight = align === "right";
    const railEdge = isRight ? "left" : "right";
    const noteEdge = isRight ? "right" : "left";

    const noteMidY =
        activeIndex !== null ? (rowMidYs.current[activeIndex] ?? 0) : 0;
    const noteTop = Math.max(0, noteMidY - PANEL_H / 2);

    const cardVisible = activeIndex !== null;
    const noteShiftX = isRight ? "-8px" : "8px";

    const activeMember =
        activeIndex !== null ? members[activeIndex] : null;

    return (
        <div
            style={{
                position: "relative",
                marginTop: "clamp(28px, 4.5vh, 56px)",
                maxWidth: "48ch",
                marginLeft: isRight ? "auto" : undefined,
                marginRight: isRight ? undefined : "auto",
                overflow: "visible",
            }}
        >
            <style>{`
@keyframes ledger-card-fadeup {
    0%   { opacity: 0; transform: translateY(4px); }
    100% { opacity: 1; transform: translateY(0); }
}
@media (prefers-reduced-motion: reduce) {
    @keyframes ledger-card-fadeup {
        0%, 100% { opacity: 1; transform: none; }
    }
}
`}</style>

            {/* Ledger grid container */}
            <div ref={containerRef} style={{ position: "relative" }}>
                {/* Rail SVG — inside containerRef so top:0 is co-planar with the table's top edge */}
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
                {/* "Daftar Anggota" editorial subheading */}
                <div
                    aria-hidden="true"
                    style={{
                        fontFamily: "'Inter', ui-sans-serif, sans-serif",
                        fontWeight: 600,
                        fontSize: "0.7rem",
                        textTransform: "uppercase",
                        letterSpacing: "0.32em",
                        color: "oklch(0.42 0.10 240 / 0.65)",
                        marginBottom: "10px",
                        padding: "0 clamp(8px, 1.2vw, 14px)",
                    }}
                >
                    Daftar Anggota
                </div>

                {/* Column header row — grid matches row template */}
                <div
                    style={{
                        display: "grid",
                        gridTemplateColumns: "2rem 28px 1fr auto",
                        gap: "0 clamp(8px, 1.2vw, 14px)",
                        padding: "0 clamp(8px, 1.2vw, 14px)",
                        paddingBottom: "6px",
                        borderBottom:
                            "1px solid oklch(0.42 0.10 240 / 0.10)",
                    }}
                    aria-hidden="true"
                >
                    <span style={headerLabelStyle}>Nº</span>
                    {/* Avatar column: no label */}
                    <span />
                    <span style={headerLabelStyle}>NAMA</span>
                    <span style={headerLabelStyle}>DETAIL</span>
                </div>

                {/* Member rows */}
                {members.map((member, i) => (
                    <MemberLedgerRow
                        key={i}
                        ref={rowRefCallback(i)}
                        member={member}
                        index={i}
                        align={align}
                        isActive={activeIndex === i}
                        anyActive={activeIndex !== null}
                        memberClass={memberClass}
                        panelId={PANEL_ID}
                        onActivate={setActiveIndex}
                    />
                ))}

                {/* ITS Gold flourish (RCMED only) */}
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

            {/* Active Card — floats outside the ledger width into chapter interior */}
            <aside
                id={PANEL_ID}
                role="tooltip"
                aria-hidden={!cardVisible}
                style={{
                    position: "absolute",
                    top: noteTop,
                    [noteEdge]: "calc(100% + 16px)",
                    width: "clamp(228px, 22vw, 288px)",
                    padding: "16px 18px 18px",
                    background: "#F8F9FA",
                    border: "1px solid oklch(0.42 0.10 240 / 0.08)",
                    opacity: cardVisible ? 1 : 0,
                    transform: cardVisible
                        ? "translateX(0)"
                        : `translateX(${noteShiftX})`,
                    transition: `opacity 240ms ${ease}, transform 240ms ${ease}, top 200ms ${ease}`,
                    pointerEvents: "none",
                    zIndex: 30,
                    willChange: "opacity, transform, top",
                }}
            >
                {activeMember !== null && activeIndex !== null && (
                    <div
                        key={activeIndex}
                        style={{
                            animation: `ledger-card-fadeup 200ms ${ease} both`,
                        }}
                    >
                        {/* Photo + Nº row */}
                        <div
                            style={{
                                display: "flex",
                                alignItems: "flex-start",
                                justifyContent: "space-between",
                                marginBottom: "14px",
                            }}
                        >
                            <MemberAvatar
                                member={activeMember}
                                size={56}
                            />
                            <span
                                style={{
                                    fontFamily:
                                        "'Inter', ui-sans-serif, sans-serif",
                                    fontWeight: 500,
                                    fontSize: "0.62rem",
                                    letterSpacing: "0.24em",
                                    textTransform: "uppercase",
                                    color: "#64748B",
                                    lineHeight: 1.4,
                                    paddingTop: "2px",
                                }}
                            >
                                Nº{" "}
                                {String(activeIndex + 1).padStart(2, "0")}
                            </span>
                        </div>

                        {/* Italic display name */}
                        <div
                            style={{
                                fontFamily:
                                    "'Plus Jakarta Sans', sans-serif",
                                fontWeight: 700,
                                fontStyle: "italic",
                                fontSize: "1.18rem",
                                letterSpacing: "-0.018em",
                                color: "#1E293B",
                                lineHeight: 1.1,
                                marginBottom: "10px",
                                wordBreak: "break-word",
                            }}
                        >
                            {activeMember.name}.
                        </div>

                        {/* Teal hairline */}
                        <div
                            style={{
                                height: 1,
                                width: "2rem",
                                background: "#00A8B5",
                                opacity: 0.65,
                                marginBottom: "8px",
                            }}
                        />

                        {/* Role */}
                        <div
                            style={{
                                fontFamily:
                                    "'Inter', ui-sans-serif, sans-serif",
                                fontWeight: 500,
                                fontSize: "0.6rem",
                                letterSpacing: "0.24em",
                                textTransform: "uppercase",
                                color: "#64748B",
                                lineHeight: 1.4,
                                marginBottom: activeMember.bio
                                    ? "10px"
                                    : 0,
                            }}
                        >
                            {activeMember.desc}
                        </div>

                        {/* Bio */}
                        {activeMember.bio && (
                            <p
                                style={{
                                    fontFamily:
                                        "'Inter', ui-sans-serif, sans-serif",
                                    fontSize: "0.78rem",
                                    fontWeight: 400,
                                    lineHeight: 1.55,
                                    color: "#475569",
                                    margin: 0,
                                    display: "-webkit-box",
                                    WebkitLineClamp: 3,
                                    WebkitBoxOrient: "vertical",
                                    overflow: "hidden",
                                }}
                            >
                                {activeMember.bio}
                            </p>
                        )}
                    </div>
                )}
            </aside>
        </div>
    );
}

const headerLabelStyle: React.CSSProperties = {
    fontFamily: "'Inter', ui-sans-serif, sans-serif",
    fontWeight: 600,
    fontSize: "0.58rem",
    textTransform: "uppercase",
    letterSpacing: "0.28em",
    color: "#94A3B8",
    userSelect: "none",
};
