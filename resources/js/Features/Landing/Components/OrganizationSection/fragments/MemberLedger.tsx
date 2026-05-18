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
const ease = "cubic-bezier(0.25, 1, 0.5, 1)";

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

    // Measure container height for the SVG rail path
    useEffect(() => {
        const el = containerRef.current;
        if (!el) return;
        const ro = new ResizeObserver(([entry]) => {
            setRailH(entry.contentRect.height);
        });
        ro.observe(el);
        return () => ro.disconnect();
    }, []);

    // Dismiss on outside pointer-down when a row is active
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

    // Vertical offset for the margin note — centered on the active row
    const PANEL_H = 118; // approximate panel height; CSS handles clipping
    const noteMidY =
        activeIndex !== null ? (rowMidYs.current[activeIndex] ?? 0) : 0;
    const noteTop = Math.max(0, noteMidY - PANEL_H / 2);

    const activeShift = activeIndex !== null;
    const noteShiftX = isRight ? "-6px" : "6px";

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
            {/* Rail SVG — GSAP queries connectorClass on the <path> for getTotalLength() */}
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

            {/* Ledger grid container */}
            <div ref={containerRef} style={{ position: "relative" }}>
                {/* Header row */}
                <div
                    style={{
                        display: "grid",
                        gridTemplateColumns: "2.4rem 1fr auto",
                        gap: "0 clamp(10px, 1.4vw, 18px)",
                        padding: "0 clamp(8px, 1.2vw, 14px)",
                        paddingBottom: "6px",
                        borderBottom:
                            "1px solid oklch(0.42 0.10 240 / 0.10)",
                        direction: "ltr",
                    }}
                    aria-hidden="true"
                >
                    {isRight ? (
                        <>
                            <span style={headerLabelStyle}>Nº</span>
                            <span style={headerLabelStyle}>NAMA</span>
                            <span style={headerLabelStyle}>DETAIL</span>
                        </>
                    ) : (
                        <>
                            <span style={headerLabelStyle}>Nº</span>
                            <span style={headerLabelStyle}>NAMA</span>
                            <span style={headerLabelStyle}>DETAIL</span>
                        </>
                    )}
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

            {/* Shared margin note — absolute, floats outside the ledger width */}
            <aside
                id={PANEL_ID}
                role="tooltip"
                aria-hidden={activeIndex === null}
                style={{
                    position: "absolute",
                    top: noteTop,
                    [noteEdge]: "calc(100% + 16px)",
                    width: "clamp(168px, 18vw, 208px)",
                    padding: "12px 14px 14px",
                    background: "#F8F9FA",
                    border: "1px solid oklch(0.42 0.10 240 / 0.08)",
                    opacity: activeShift ? 1 : 0,
                    transform: activeShift
                        ? "translateX(0)"
                        : `translateX(${noteShiftX})`,
                    transition: `opacity 240ms ${ease}, transform 240ms ${ease}, top 200ms ${ease}`,
                    pointerEvents: "none",
                    zIndex: 30,
                    willChange: "opacity, transform, top",
                }}
            >
                {activeIndex !== null && (
                    <>
                        <div
                            style={{
                                fontFamily:
                                    "'Inter', ui-sans-serif, sans-serif",
                                fontSize: "0.62rem",
                                fontWeight: 500,
                                letterSpacing: "0.24em",
                                textTransform: "uppercase",
                                color: "#64748B",
                                marginBottom: "8px",
                                lineHeight: 1.4,
                            }}
                        >
                            Nº {String(activeIndex + 1).padStart(2, "0")}
                        </div>
                        <div
                            style={{
                                fontFamily:
                                    "'Plus Jakarta Sans', sans-serif",
                                fontSize: "1.28rem",
                                fontWeight: 700,
                                fontStyle: "italic",
                                letterSpacing: "-0.018em",
                                color: "#1E293B",
                                lineHeight: 1.08,
                                maxWidth: "18ch",
                                marginBottom: "8px",
                                wordBreak: "break-word",
                            }}
                        >
                            {members[activeIndex].name}
                            {"."}
                        </div>
                        <div
                            style={{
                                height: "1px",
                                width: "2rem",
                                background: "#00A8B5",
                                opacity: 0.65,
                                marginBottom: "8px",
                            }}
                        />
                        <div
                            style={{
                                fontFamily:
                                    "'Inter', ui-sans-serif, sans-serif",
                                fontSize: "0.6rem",
                                fontWeight: 500,
                                letterSpacing: "0.24em",
                                textTransform: "uppercase",
                                color: "#64748B",
                                lineHeight: 1.4,
                            }}
                        >
                            {members[activeIndex].desc}
                        </div>
                    </>
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
