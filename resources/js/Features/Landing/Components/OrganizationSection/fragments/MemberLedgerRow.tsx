import { forwardRef } from "react";
import { TeamMember } from "../../../Types/organizationSection.type";

interface MemberLedgerRowProps {
    member: TeamMember;
    index: number;
    align: "left" | "right";
    isActive: boolean;
    anyActive: boolean;
    memberClass: string;
    panelId: string;
    onActivate: (index: number | null) => void;
}

const ease = "cubic-bezier(0.25, 1, 0.5, 1)";
const transition = [
    `color 280ms ${ease}`,
    `background 280ms ${ease}`,
    `box-shadow 280ms ${ease}`,
    `opacity 280ms ${ease}`,
    `transform 280ms ${ease}`,
].join(", ");

const MemberLedgerRow = forwardRef<HTMLButtonElement, MemberLedgerRowProps>(
    (
        {
            member,
            index,
            align,
            isActive,
            anyActive,
            memberClass,
            panelId,
            onActivate,
        },
        ref,
    ) => {
        const isRight = align === "right";
        const nameShift = isActive
            ? isRight
                ? "translateX(-4px)"
                : "translateX(4px)"
            : "none";
        const chevronShift = isActive
            ? isRight
                ? "translateX(-4px)"
                : "translateX(4px)"
            : "none";

        return (
            <button
                ref={ref}
                className={memberClass}
                aria-label={`${member.name} — ${member.desc}`}
                aria-describedby={panelId}
                onMouseEnter={() => onActivate(index)}
                onMouseLeave={() => onActivate(null)}
                onFocus={() => onActivate(index)}
                onBlur={() => onActivate(null)}
                style={{
                    display: "grid",
                    gridTemplateColumns: "2rem 28px 1fr auto",
                    alignItems: "center",
                    gap: "0 clamp(8px, 1.2vw, 14px)",
                    width: "100%",
                    height: "clamp(48px, 6vh, 60px)",
                    padding: "0 clamp(8px, 1.2vw, 14px)",
                    border: "none",
                    cursor: "crosshair",
                    textAlign: "left",
                    background: isActive
                        ? "oklch(0.42 0.10 240 / 0.025)"
                        : "transparent",
                    opacity: anyActive && !isActive ? 0.48 : 1,
                    boxShadow: isActive
                        ? `inset 0 -1.5px 0 oklch(0.72 0.16 195 / 0.90)`
                        : `inset 0 -1px 0 oklch(0.42 0.10 240 / 0.10)`,
                    outline: "none",
                    transition,
                }}
            >
                {/* Index numeral */}
                <span
                    aria-hidden="true"
                    style={{
                        fontFamily: "'Plus Jakarta Sans', sans-serif",
                        fontWeight: isActive ? 700 : 500,
                        fontSize: "clamp(0.68rem, 0.88vw, 0.8rem)",
                        fontVariantNumeric: "tabular-nums lining-nums",
                        fontFeatureSettings: '"tnum" 1, "lnum" 1',
                        letterSpacing: "0.02em",
                        color: isActive
                            ? "#00A8B5"
                            : "oklch(0.42 0.10 240 / 0.55)",
                        transition,
                        userSelect: "none",
                        flexShrink: 0,
                    }}
                >
                    {String(index + 1).padStart(2, "0")}
                </span>

                {/* Avatar */}
                <span
                    aria-hidden="true"
                    style={{
                        width: 28,
                        height: 28,
                        borderRadius: "50%",
                        background: "oklch(0.42 0.10 240 / 0.07)",
                        border: "1px solid oklch(0.42 0.10 240 / 0.14)",
                        boxShadow: isActive
                            ? "0 0 0 1.5px #00A8B5"
                            : "0 0 0 0 transparent",
                        overflow: "hidden",
                        display: "flex",
                        alignItems: "center",
                        justifyContent: "center",
                        flexShrink: 0,
                        transition: `box-shadow 280ms ${ease}`,
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
                                fontWeight: 700,
                                fontStyle: "italic",
                                fontSize: "0.62rem",
                                letterSpacing: "-0.01em",
                                color: "oklch(0.22 0.06 240 / 0.65)",
                                userSelect: "none",
                            }}
                        >
                            {member.initials}
                        </span>
                    )}
                </span>

                {/* Member name */}
                <span
                    style={{
                        fontFamily: "'Plus Jakarta Sans', sans-serif",
                        fontWeight: 700,
                        fontStyle: "italic",
                        fontSize: "clamp(0.86rem, 1.15vw, 1rem)",
                        letterSpacing: "-0.015em",
                        color: "#1E293B",
                        transform: nameShift,
                        transition,
                        whiteSpace: "nowrap",
                        overflow: "hidden",
                        textOverflow: "ellipsis",
                        userSelect: "none",
                    }}
                >
                    {member.name}
                </span>

                {/* Role + chevron */}
                <span
                    style={{
                        display: "flex",
                        alignItems: "center",
                        gap: "6px",
                        flexShrink: 0,
                        transition,
                    }}
                >
                    <span
                        style={{
                            fontFamily: "'Inter', ui-sans-serif, sans-serif",
                            fontWeight: 500,
                            fontSize: "0.58rem",
                            textTransform: "uppercase",
                            letterSpacing: "0.2em",
                            color: "#64748B",
                            whiteSpace: "nowrap",
                            userSelect: "none",
                        }}
                    >
                        {member.desc}
                    </span>
                    <span
                        aria-hidden="true"
                        style={{
                            fontSize: "0.58rem",
                            color: isActive
                                ? "#00A8B5"
                                : "oklch(0.42 0.10 240 / 0.45)",
                            transform: chevronShift,
                            transition,
                            userSelect: "none",
                            display: "inline-block",
                        }}
                    >
                        ▸
                    </span>
                </span>
            </button>
        );
    },
);

MemberLedgerRow.displayName = "MemberLedgerRow";
export default MemberLedgerRow;
