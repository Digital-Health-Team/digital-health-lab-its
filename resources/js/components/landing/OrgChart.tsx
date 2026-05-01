import { useRef } from "react";
import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

interface Member {
    name: string;
    role: string;
    initials: string;
    spec: string;
    coord: string;
}

const head: Member = {
    name: "Prof. Dr. Ir. Tri Arief Sardjono",
    role: "Head of Department",
    initials: "TA",
    spec: "SPEC-PRIMARY",
    coord: "Ø·00",
};

const members: Member[] = [
    {
        name: "Dr. Achmad Arifin",
        role: "Research Lead",
        initials: "AA",
        spec: "SPEC-01",
        coord: "A·01",
    },
    {
        name: "Dr. Mauridhi Hery P.",
        role: "Tech Director",
        initials: "MH",
        spec: "SPEC-02",
        coord: "B·01",
    },
    {
        name: "Dr. Torib Hamzah",
        role: "Product Head",
        initials: "TH",
        spec: "SPEC-03",
        coord: "C·01",
    },
    {
        name: "Dr. Srisulistiowati",
        role: "Events Head",
        initials: "SS",
        spec: "SPEC-04",
        coord: "D·01",
    },
    {
        name: "Dr. Bagus Setya B.",
        role: "Pub. Manager",
        initials: "BS",
        spec: "SPEC-05",
        coord: "E·01",
    },
    {
        name: "Dr. Nita Handayani",
        role: "Lab Director",
        initials: "NH",
        spec: "SPEC-06",
        coord: "F·01",
    },
];

// SVG connector constants — viewBox "0 0 1000 520"
// Head spec box occupies y: 0–130
// Stem: 130 → 230; H-bar at y=230; Drops: 230 → 330; Members: top=330
const STEM = "M 500 130 L 500 230";
const HBAR_L = "M 500 230 L 83 230";
const HBAR_R = "M 500 230 L 917 230";
const DROP_XS = [83, 250, 417, 583, 750, 917];
const DROP_PATHS = DROP_XS.map((x) => `M ${x} 230 L ${x} 330`);

function CornerMarks({
    color = "oklch(55% 0.12 195 / 0.65)",
}: {
    color?: string;
}) {
    const base: React.CSSProperties = {
        position: "absolute",
        width: 8,
        height: 8,
    };
    return (
        <>
            <span
                style={{
                    ...base,
                    top: 0,
                    left: 0,
                    borderTop: `1px solid ${color}`,
                    borderLeft: `1px solid ${color}`,
                }}
            />
            <span
                style={{
                    ...base,
                    top: 0,
                    right: 0,
                    borderTop: `1px solid ${color}`,
                    borderRight: `1px solid ${color}`,
                }}
            />
            <span
                style={{
                    ...base,
                    bottom: 0,
                    left: 0,
                    borderBottom: `1px solid ${color}`,
                    borderLeft: `1px solid ${color}`,
                }}
            />
            <span
                style={{
                    ...base,
                    bottom: 0,
                    right: 0,
                    borderBottom: `1px solid ${color}`,
                    borderRight: `1px solid ${color}`,
                }}
            />
        </>
    );
}

function PrimarySpec({ member }: { member: Member }) {
    return (
        <div
            className="blueprint-head relative flex flex-col"
            style={{
                width: 300,
                backgroundColor: "oklch(10% 0.025 220)",
                border: "1px solid oklch(55% 0.12 195 / 0.6)",
            }}
        >
            {/* Header bar */}
            <div
                className="flex items-center justify-between px-3 py-1.5"
                style={{
                    borderBottom: "1px solid oklch(55% 0.12 195 / 0.3)",
                    backgroundColor: "oklch(55% 0.12 195 / 0.08)",
                }}
            >
                <span
                    className="font-mono"
                    style={{
                        color: "oklch(65% 0.14 195)",
                        fontSize: "0.6rem",
                        letterSpacing: "0.1em",
                    }}
                >
                    {member.spec}
                </span>
                <span
                    className="font-mono"
                    style={{ color: "oklch(45% 0.08 195)", fontSize: "0.6rem" }}
                >
                    {member.coord}
                </span>
            </div>

            {/* Body */}
            <div className="flex items-center gap-4 px-4 py-4">
                <div
                    className="flex items-center justify-center shrink-0"
                    style={{
                        width: 44,
                        height: 44,
                        border: "1px solid oklch(55% 0.12 195 / 0.4)",
                        backgroundColor: "oklch(13% 0.03 220)",
                    }}
                >
                    <span
                        className="font-mono font-bold"
                        style={{
                            color: "oklch(72% 0.14 195)",
                            fontSize: "1rem",
                        }}
                    >
                        {member.initials}
                    </span>
                </div>
                <div>
                    <p
                        className="font-semibold leading-snug"
                        style={{
                            color: "oklch(91% 0.01 235)",
                            fontFamily: "'Plus Jakarta Sans', sans-serif",
                            fontSize: "0.85rem",
                        }}
                    >
                        {member.name}
                    </p>
                    <p
                        className="font-mono uppercase mt-1"
                        style={{
                            color: "oklch(58% 0.10 195)",
                            fontSize: "0.58rem",
                            letterSpacing: "0.1em",
                        }}
                    >
                        {member.role}
                    </p>
                </div>
            </div>

            <CornerMarks />
        </div>
    );
}

function SecondarySpec({ member }: { member: Member }) {
    return (
        <div
            className="org-member relative flex flex-col items-center"
            style={{ width: "14.28%" }}
        >
            <span
                className="font-mono mb-2 block"
                style={{
                    color: "oklch(42% 0.08 195)",
                    fontSize: "0.55rem",
                    letterSpacing: "0.1em",
                }}
            >
                {member.coord}
            </span>
            <div
                className="relative w-full flex flex-col"
                style={{
                    backgroundColor: "oklch(9% 0.02 220)",
                    border: "1px solid oklch(45% 0.10 195 / 0.42)",
                }}
            >
                {/* Header */}
                <div
                    className="px-2 py-1 text-center"
                    style={{
                        borderBottom: "1px solid oklch(45% 0.10 195 / 0.22)",
                        backgroundColor: "oklch(45% 0.10 195 / 0.06)",
                    }}
                >
                    <span
                        className="font-mono"
                        style={{
                            color: "oklch(52% 0.12 195)",
                            fontSize: "0.55rem",
                            letterSpacing: "0.08em",
                        }}
                    >
                        {member.spec}
                    </span>
                </div>
                {/* Body */}
                <div className="flex flex-col items-center px-2 py-3">
                    <div
                        className="flex items-center justify-center mb-2"
                        style={{
                            width: 30,
                            height: 30,
                            border: "1px solid oklch(45% 0.10 195 / 0.38)",
                            backgroundColor: "oklch(12% 0.025 220)",
                        }}
                    >
                        <span
                            className="font-mono font-bold"
                            style={{
                                color: "oklch(62% 0.12 195)",
                                fontSize: "0.7rem",
                            }}
                        >
                            {member.initials}
                        </span>
                    </div>
                    <p
                        className="text-center font-semibold leading-tight mb-2"
                        style={{
                            color: "oklch(87% 0.01 235)",
                            fontFamily: "'Plus Jakarta Sans', sans-serif",
                            fontSize: "0.63rem",
                        }}
                    >
                        {member.name}
                    </p>
                    <p
                        className="font-mono text-center uppercase"
                        style={{
                            color: "oklch(52% 0.09 195)",
                            fontSize: "0.53rem",
                            letterSpacing: "0.06em",
                        }}
                    >
                        {member.role}
                    </p>
                </div>
                <CornerMarks color="oklch(45% 0.10 195 / 0.55)" />
            </div>
        </div>
    );
}

export default function OrgChart() {
    const sectionRef = useRef<HTMLElement>(null);
    const chartRef = useRef<HTMLDivElement>(null);
    const scanLineRef = useRef<HTMLDivElement>(null);
    const svgRef = useRef<SVGSVGElement>(null);

    useGSAP(
        () => {
            const prefersReduced = window.matchMedia(
                "(prefers-reduced-motion: reduce)",
            ).matches;

            if (prefersReduced) {
                gsap.set(
                    [".blueprint-head", ".org-member", ".connector-path"],
                    { opacity: 1, clearProps: "y" },
                );
                return;
            }

            // Prepare connector draw animation
            svgRef.current
                ?.querySelectorAll<SVGPathElement>(".connector-path")
                .forEach((p) => {
                    const len = p.getTotalLength();
                    gsap.set(p, {
                        strokeDasharray: len,
                        strokeDashoffset: len,
                    });
                });

            gsap.set(".blueprint-head", { opacity: 0, y: -10 });
            gsap.set(".org-member", { opacity: 0, y: 14 });
            gsap.set(scanLineRef.current, { top: -2, opacity: 0 });

            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: sectionRef.current,
                    start: "top 72%",
                    once: true,
                },
            });

            // Scan-line sweeps top → bottom of chart (duration 1.4s)
            tl.to(scanLineRef.current, {
                opacity: 1,
                duration: 0.1,
                ease: "none",
            });
            tl.to(
                scanLineRef.current,
                { top: "100%", duration: 1.4, ease: "power1.inOut" },
                "<",
            );

            // Head reveals as scan reaches ~15%
            tl.to(
                ".blueprint-head",
                { opacity: 1, y: 0, duration: 0.5, ease: "power2.out" },
                "<0.21",
            );

            // Connectors draw as scan reaches ~45%
            tl.to(
                ".stem-path",
                { strokeDashoffset: 0, duration: 0.3, ease: "power2.out" },
                "<0.42",
            );
            tl.to(
                ".hbar-path",
                { strokeDashoffset: 0, duration: 0.55, ease: "power2.out" },
                "<0.15",
            );
            tl.to(
                ".drop-path",
                {
                    strokeDashoffset: 0,
                    duration: 0.28,
                    stagger: 0.05,
                    ease: "power2.out",
                },
                "<0.35",
            );

            // Members reveal as scan reaches ~70%
            tl.to(
                ".org-member",
                {
                    opacity: 1,
                    y: 0,
                    duration: 0.38,
                    stagger: 0.07,
                    ease: "power2.out",
                },
                "<0.2",
            );

            // Scan-line fades out
            tl.to(
                scanLineRef.current,
                { opacity: 0, duration: 0.25, ease: "none" },
                "<0.35",
            );
        },
        { scope: sectionRef },
    );

    return (
        <section
            ref={sectionRef}
            className="relative py-24 px-4 md:px-12 overflow-hidden bg-primary-950"
            style={{
                // backgroundColor: "oklch(8% 0.015 220)",
                backgroundImage:
                    "linear-gradient(oklch(38% 0.01 220 / 0.11) 1px, transparent 1px), linear-gradient(90deg, oklch(38% 0.01 220 / 0.11) 1px, transparent 1px)",
                backgroundSize: "48px 48px",
            }}
        >
            {/* Center depth gradient */}
            <div
                aria-hidden
                className="absolute inset-0 pointer-events-none"
                style={{
                    background:
                        "radial-gradient(ellipse 70% 55% at 50% 38%, oklch(13% 0.025 220 / 0.75) 0%, transparent 68%)",
                }}
            />

            <div className="relative z-10 max-w-6xl mx-auto">
                {/* Section header */}
                <div className="mb-16">
                    <p
                        className="font-mono text-xs tracking-[0.25em] mb-3"
                        style={{ color: "oklch(48% 0.10 195)" }}
                    >
                        SCHEMATIC / REV-1.0 / IDIG-LAB
                    </p>
                    <h2
                        className="text-4xl md:text-5xl font-bold"
                        style={{
                            fontFamily: "'Plus Jakarta Sans', sans-serif",
                            color: "oklch(91% 0.01 235)",
                            letterSpacing: "-0.02em",
                            lineHeight: 1.1,
                        }}
                    >
                        Our Organization
                    </h2>
                    <p
                        className="mt-3 max-w-sm"
                        style={{
                            color: "oklch(58% 0.04 230)",
                            lineHeight: 1.65,
                            fontSize: "0.95rem",
                        }}
                    >
                        The dedicated team behind IDIG Health Tech's mission and
                        vision.
                    </p>
                </div>

                {/* Blueprint chart container */}
                <div
                    ref={chartRef}
                    className="relative"
                    style={{ height: 520 }}
                >
                    {/* Scan-line — positioned inside chart */}
                    <div
                        ref={scanLineRef}
                        aria-hidden
                        className="absolute left-0 right-0 pointer-events-none z-20"
                        style={{
                            height: 2,
                            top: 0,
                            opacity: 0,
                            background:
                                "linear-gradient(to right, transparent 5%, oklch(80% 0.13 195 / 0.65) 30%, oklch(92% 0.10 195) 50%, oklch(80% 0.13 195 / 0.65) 70%, transparent 95%)",
                            boxShadow: "0 0 10px 3px oklch(55% 0.14 195 / 0.3)",
                        }}
                    />

                    {/* SVG connector layer */}
                    <svg
                        ref={svgRef}
                        aria-hidden
                        className="absolute inset-0 w-full h-full pointer-events-none"
                        viewBox="0 0 1000 520"
                        preserveAspectRatio="xMidYMid meet"
                    >
                        <defs>
                            <marker
                                id="bp-arrow"
                                markerWidth="5"
                                markerHeight="5"
                                refX="4"
                                refY="2.5"
                                orient="auto"
                            >
                                <path
                                    d="M 0 0 L 5 2.5 L 0 5 Z"
                                    fill="oklch(50% 0.10 195 / 0.55)"
                                />
                            </marker>
                        </defs>

                        <path
                            className="connector-path stem-path"
                            d={STEM}
                            stroke="oklch(52% 0.11 195 / 0.6)"
                            strokeWidth="1"
                            fill="none"
                        />
                        <path
                            className="connector-path hbar-path"
                            d={HBAR_L}
                            stroke="oklch(50% 0.10 195 / 0.52)"
                            strokeWidth="1"
                            fill="none"
                        />
                        <path
                            className="connector-path hbar-path"
                            d={HBAR_R}
                            stroke="oklch(50% 0.10 195 / 0.52)"
                            strokeWidth="1"
                            fill="none"
                        />

                        {DROP_PATHS.map((d, i) => (
                            <path
                                key={i}
                                className="connector-path drop-path"
                                d={d}
                                stroke="oklch(46% 0.09 195 / 0.48)"
                                strokeWidth="1"
                                fill="none"
                                markerEnd="url(#bp-arrow)"
                            />
                        ))}
                    </svg>

                    {/* Primary spec — centered at top */}
                    <div
                        className="absolute flex justify-center"
                        style={{
                            top: 0,
                            left: "50%",
                            transform: "translateX(-50%)",
                        }}
                    >
                        <PrimarySpec member={head} />
                    </div>

                    {/* Members row */}
                    <div
                        className="absolute left-0 right-0 flex justify-around items-start"
                        style={{ top: 330 }}
                    >
                        {members.map((m) => (
                            <SecondarySpec key={m.spec} member={m} />
                        ))}
                    </div>
                </div>

                {/* Bottom rule */}
                <div
                    aria-hidden
                    className="mt-6 w-full flex items-center gap-3"
                >
                    <div
                        className="h-px flex-1"
                        style={{
                            backgroundColor: "oklch(32% 0.06 195 / 0.32)",
                        }}
                    />
                    <span
                        className="font-mono shrink-0"
                        style={{
                            color: "oklch(38% 0.08 195)",
                            fontSize: "0.55rem",
                            letterSpacing: "0.14em",
                        }}
                    >
                        END SCHEMATIC
                    </span>
                    <div
                        className="h-px flex-1"
                        style={{
                            backgroundColor: "oklch(32% 0.06 195 / 0.32)",
                        }}
                    />
                </div>
            </div>
        </section>
    );
}
