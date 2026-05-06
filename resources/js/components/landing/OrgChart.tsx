import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { useRef } from "react";

gsap.registerPlugin(ScrollTrigger);

// ── Data ────────────────────────────────────────────────────────────

const SPINE_ROW_H = 100;

const HEAD = {
    display: ["Djoko", "Kuswanto."],
    full: "Djoko Kuswanto, S.T., M.Biotech.",
    roleId: "Kepala Laboratorium IDIG",
    roleEn: "Head of IDIG Laboratory",
    desc: "Leading the strategic vision and research initiatives at IDIG Health Tech, bridging engineering and medical innovation.",
    initials: "JK",
    image: "https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&q=80&w=400&h=400",
};

interface TeamMember {
    name: string;
    desc: string;
    initials: string;
}

const HTECH = {
    lead: {
        display: ["Achmad", "Arifin."],
        full: "Dr. Achmad Arifin, S.T., M.T.",
        roleId: "Pemimpin Tim",
        roleEn: "IDIG HTECH Lead",
        desc: "Directing biomedical instrumentation research with a focus on neural engineering and assistive technology systems.",
        initials: "AA",
        image: "https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&q=80&w=400&h=400",
    },
    members: [
        {
            name: "Dr. Torib Hamzah",
            desc: "Biomedical signal processing",
            initials: "TH",
        },
        {
            name: "Dr. Bagus Setya B.",
            desc: "Medical device prototyping",
            initials: "BS",
        },
        {
            name: "Dr. Siti Aminah",
            desc: "Clinical data analytics",
            initials: "SA",
        },
        {
            name: "Dr. Budi Santoso",
            desc: "Embedded systems engineering",
            initials: "BD",
        },
        {
            name: "Dr. Andi Wijaya",
            desc: "Biomechanics and rehabilitation",
            initials: "AW",
        },
    ] as TeamMember[],
};

const RCMED = {
    lead: {
        display: ["Mauridhi", "Hery P."],
        full: "Dr. Mauridhi Hery P., M.Eng., Ph.D.",
        roleId: "Pemimpin Tim",
        roleEn: "IDIG RCMED Lead",
        desc: "Pioneering computational medicine research, integrating AI-driven diagnostics with clinical decision support.",
        initials: "MH",
        image: "https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&q=80&w=400&h=400",
    },
    members: [
        {
            name: "Dr. Srisulistiowati",
            desc: "Medical imaging analysis",
            initials: "SS",
        },
        {
            name: "Dr. Nita Handayani",
            desc: "Pharmacoinformatics",
            initials: "NH",
        },
        {
            name: "Dr. Ratna Sari",
            desc: "Computational genomics",
            initials: "RS",
        },
        {
            name: "Dr. Hendra Gunawan",
            desc: "Health informatics systems",
            initials: "HG",
        },
        {
            name: "Dr. Dian Pertiwi",
            desc: "Telemedicine platforms",
            initials: "DP",
        },
    ] as TeamMember[],
};

// ── Pinwheel collage data for Act 1 ─────────────────────────────────
// Square container (same clamp W=H). cx/cy = photo center as % of container.
// rot = rotation in degrees to create pinwheel depth.
const HEX_ITEMS = [
    {
        cx: 50,
        cy: 50,
        size: 44,
        center: true,
        rot: 0,
        label: "Biomedical Research",
        image: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        cx: 50,
        cy: 14,
        size: 33,
        center: false,
        rot: -8,
        label: "3D Bioprinting",
        image: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        cx: 76,
        cy: 22,
        size: 33,
        center: false,
        rot: 20,
        label: "Neural Interface",
        image: "https://images.unsplash.com/photo-1559757148-5c350d0d3c56?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        cx: 84,
        cy: 50,
        size: 33,
        center: false,
        rot: 12,
        label: "Lab Research",
        image: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        cx: 76,
        cy: 78,
        size: 33,
        center: false,
        rot: -20,
        label: "Clinical Trials",
        image: "https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        cx: 50,
        cy: 86,
        size: 33,
        center: false,
        rot: 8,
        label: "Data Analysis",
        image: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        cx: 24,
        cy: 78,
        size: 33,
        center: false,
        rot: 20,
        label: "Wearable Sensors",
        image: "https://images.unsplash.com/photo-1576086213369-97a306d36557?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        cx: 16,
        cy: 50,
        size: 33,
        center: false,
        rot: -12,
        label: "AI Diagnostics",
        image: "https://images.unsplash.com/photo-1530497610245-94d3c16cda28?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        cx: 24,
        cy: 22,
        size: 33,
        center: false,
        rot: -20,
        label: "Smart Healthcare",
        image: "https://images.unsplash.com/photo-1584982751601-97dcc096659c?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        cx: 63,
        cy: 18,
        size: 22,
        center: false,
        rot: 15,
        label: "Biomedical Tech",
        image: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        cx: 80,
        cy: 64,
        size: 22,
        center: false,
        rot: -15,
        label: "Advanced Research",
        image: "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        cx: 37,
        cy: 82,
        size: 22,
        center: false,
        rot: 25,
        label: "Laboratory",
        image: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        cx: 20,
        cy: 36,
        size: 22,
        center: false,
        rot: -25,
        label: "Microscopy",
        image: "https://images.unsplash.com/photo-1518152006812-edab29b069ac?auto=format&fit=crop&q=80&w=400&h=400",
    },
] as const;

const ACT2_COLLAGE = [
    {
        image: "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&q=80&w=600",
        width: "65%",
        aspectRatio: "3/4",
        top: "5%",
        left: "0%",
        rot: -3,
        z: 1,
        shadow: "0 12px 40px rgba(0,66,109,0.15)",
    },
    {
        image: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=400&h=400",
        width: "80%",
        aspectRatio: "4/3",
        top: "35%",
        left: "20%",
        rot: 2,
        z: 10,
        shadow: "0 24px 60px rgba(0,66,109,0.3)",
        center: true,
    },
    {
        image: "https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&q=80&w=600",
        width: "45%",
        aspectRatio: "1/1",
        top: "70%",
        left: "5%",
        rot: -5,
        z: 15,
        shadow: "0 16px 40px rgba(0,66,109,0.25)",
    },
    {
        image: "https://images.unsplash.com/photo-1559757148-5c350d0d3c56?auto=format&fit=crop&q=80&w=600",
        width: "50%",
        aspectRatio: "4/5",
        top: "10%",
        left: "45%",
        rot: 6,
        z: 5,
        shadow: "0 10px 30px rgba(0,66,109,0.1)",
    },
];

const ACT3_COLLAGE = [
    {
        image: "https://images.unsplash.com/photo-1576086213369-97a306d36557?auto=format&fit=crop&q=80&w=600",
        width: "65%",
        aspectRatio: "3/4",
        top: "10%",
        left: "35%",
        rot: 4,
        z: 1,
        shadow: "0 12px 40px rgba(0,66,109,0.15)",
    },
    {
        image: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=800",
        width: "80%",
        aspectRatio: "4/3",
        top: "40%",
        left: "0%",
        rot: -3,
        z: 10,
        shadow: "0 24px 60px rgba(0,66,109,0.3)",
        center: true,
    },
    {
        image: "https://images.unsplash.com/photo-1530497610245-94d3c16cda28?auto=format&fit=crop&q=80&w=600",
        width: "55%",
        aspectRatio: "1/1",
        top: "65%",
        left: "40%",
        rot: 8,
        z: 15,
        shadow: "0 18px 45px rgba(0,66,109,0.25)",
    },
    {
        image: "https://images.unsplash.com/photo-1584982751601-97dcc096659c?auto=format&fit=crop&q=80&w=600",
        width: "45%",
        aspectRatio: "4/5",
        top: "-5%",
        left: "10%",
        rot: -6,
        z: 5,
        shadow: "0 10px 30px rgba(0,66,109,0.1)",
    },
];

export default function OrgChart() {
    const sectionRef = useRef<HTMLElement>(null);

    useGSAP(
        () => {
            const section = sectionRef.current!;
            const mm = gsap.matchMedia();

            mm.add(
                {
                    isDesktop:
                        "(min-width: 768px) and (prefers-reduced-motion: no-preference)",
                    isMobile:
                        "(max-width: 767px), (prefers-reduced-motion: reduce)",
                },
                (ctx) => {
                    const { isDesktop } = ctx.conditions!;
                    isDesktop ? buildDesktop(section) : buildMobile(section);
                },
            );
        },
        { scope: sectionRef },
    );

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
                Tim Kepemimpinan IDIG Health Tech — IDIG Health Tech Leadership
                Team
            </h2>

            {/* Full-section honeycomb */}
            <div
                className="absolute inset-0 honeycomb-light opacity-[0.04] pointer-events-none"
                aria-hidden="true"
            />

            {/* ─────────────────────────────────────────────────────────
                ACT 1 — Kepala Laboratorium (2-column)
                ───────────────────────────────────────────────────────── */}
            <div className="act-1 relative overflow-hidden flex flex-col justify-center px-[clamp(24px,7vw,120px)] py-[clamp(80px,14vh,120px)] md:min-h-screen md:py-0">
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

                        {/* Profile: avatar + text in flex-row */}
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
                                    style={{
                                        fontSize: "clamp(2.4rem, 7vw, 5.5rem)",
                                    }}
                                    aria-label={HEAD.full}
                                >
                                    {HEAD.display.map((word, i) => (
                                        <span
                                            key={i}
                                            className="block overflow-hidden pb-[0.3em] -mb-[0.3em] pt-[0.3em] -mt-[0.3em] px-[0.1em] -mx-[0.1em]"
                                        >
                                            <span className="block act-1-word">
                                                {word}
                                            </span>
                                        </span>
                                    ))}
                                </h3>
                            </div>
                        </div>

                        <div className="mb-2">
                            <span
                                className="act-1-role font-body font-semibold tracking-[0.04em] text-secondary-500"
                                style={{
                                    fontSize: "clamp(1rem, 1.35vw, 1.12rem)",
                                }}
                            >
                                {HEAD.roleId} · {HEAD.roleEn}
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
                            {HEAD.desc}
                        </p>
                    </div>

                    {/* ── Right column: Pinwheel photo collage (1 center + 8 ring) ── */}
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

            {/* ─────────────────────────────────────────────────────────
                ACT 2 — IDIG HTECH
                ───────────────────────────────────────────────────────── */}
            <div className="act-2 relative overflow-hidden flex flex-col justify-center px-[clamp(24px,7vw,120px)] py-[clamp(80px,14vh,120px)] md:h-screen md:py-0">
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
                        {ACT2_COLLAGE.map((item, i) => (
                            <div
                                key={i}
                                className={`absolute group ${item.center ? "act-2-collage-center" : "act-2-collage-item"}`}
                                style={{
                                    left: item.left,
                                    top: item.top,
                                    width: item.width,
                                    aspectRatio: item.aspectRatio,
                                    transform: `rotate(${item.rot}deg)`,
                                    padding: "clamp(4px, 0.5vw, 8px)",
                                    background: "white",
                                    boxShadow: item.shadow,
                                    zIndex: item.z,
                                }}
                            >
                                <div className="relative w-full h-full overflow-hidden">
                                    <img
                                        src={item.image}
                                        alt=""
                                        className="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 ease-out group-hover:scale-105"
                                    />
                                </div>
                            </div>
                        ))}
                    </div>

                    {/* ── Right column: Profile ── */}
                    <div className="text-right">
                        <div className="act-2-eyebrow flex items-center gap-3 mb-12 justify-end">
                            <span className="font-body font-semibold uppercase tracking-[0.3em] text-[0.68rem] text-primary-700/50">
                                Chapter 02 — Tim IDIG HTECH
                            </span>
                            <div className="w-8 h-px bg-secondary-500/30" />
                        </div>

                        {/* Profile: text + avatar in flex-row */}
                        <div className="flex flex-row items-center justify-end gap-6 mb-8">
                            <div className="min-w-0">
                                <h3
                                    className="font-display font-extrabold italic leading-[0.92] tracking-[-0.02em] text-primary-900 text-right"
                                    style={{
                                        fontSize: "clamp(2.4rem, 7vw, 5.5rem)",
                                    }}
                                    aria-label={HTECH.lead.full}
                                >
                                    {HTECH.lead.display.map((word, i) => (
                                        <span
                                            key={i}
                                            className="block overflow-hidden pb-[0.3em] -mb-[0.3em] pt-[0.3em] -mt-[0.3em] px-[0.1em] -mx-[0.1em]"
                                        >
                                            <span className="block act-2-word">
                                                {word}
                                            </span>
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
                                style={{
                                    fontSize: "clamp(1rem, 1.35vw, 1.12rem)",
                                }}
                            >
                                {HTECH.lead.roleId} · {HTECH.lead.roleEn}
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
                            {HTECH.lead.desc}
                        </p>

                        {/* Members + right-side spine */}
                        <div
                            className="relative mt-[clamp(40px,6vh,72px)]"
                            style={{ paddingRight: "10px" }}
                        >
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
                                <div
                                    key={i}
                                    className="overflow-hidden py-[clamp(10px,1.5vh,16px)] flex items-center gap-4 justify-end"
                                >
                                    <div className="act-2-member text-right">
                                        <span
                                            className="font-display font-semibold italic text-primary-900/80 block"
                                            style={{
                                                fontSize:
                                                    "clamp(1.1rem, 2.2vw, 1.6rem)",
                                            }}
                                        >
                                            {member.name}
                                        </span>
                                        <span className="font-body text-slate-500 text-[0.78rem] block mt-0.5">
                                            {member.desc}
                                        </span>
                                    </div>
                                    <div className="w-10 h-10 rounded-full bg-primary-700/[0.05] border border-primary-700/[0.08] flex items-center justify-center shrink-0">
                                        <span className="font-display font-semibold text-primary-700/30 text-[0.7rem]">
                                            {member.initials}
                                        </span>
                                    </div>
                                </div>
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

            {/* ─────────────────────────────────────────────────────────
                ACT 3 — IDIG RCMED
                ───────────────────────────────────────────────────────── */}
            <div className="act-3 relative overflow-hidden flex flex-col justify-center px-[clamp(24px,7vw,120px)] py-[clamp(80px,14vh,120px)] md:h-screen md:py-0">
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

                        {/* Profile: avatar + text in flex-row */}
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
                                    style={{
                                        fontSize: "clamp(2.4rem, 7vw, 5.5rem)",
                                    }}
                                    aria-label={RCMED.lead.full}
                                >
                                    {RCMED.lead.display.map((word, i) => (
                                        <span
                                            key={i}
                                            className="block overflow-hidden pb-[0.3em] -mb-[0.3em] pt-[0.3em] -mt-[0.3em] px-[0.1em] -mx-[0.1em]"
                                        >
                                            <span className="block act-3-word">
                                                {word}
                                            </span>
                                        </span>
                                    ))}
                                </h3>
                            </div>
                        </div>

                        <div className="mb-2">
                            <span
                                className="act-3-role font-body font-semibold tracking-[0.04em] text-secondary-500"
                                style={{
                                    fontSize: "clamp(1rem, 1.35vw, 1.12rem)",
                                }}
                            >
                                {RCMED.lead.roleId} · {RCMED.lead.roleEn}
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
                            {RCMED.lead.desc}
                        </p>

                        {/* Members + left-side spine */}
                        <div
                            className="relative mt-[clamp(40px,6vh,72px)]"
                            style={{ paddingLeft: "10px" }}
                        >
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
                                <div
                                    key={i}
                                    className="overflow-hidden py-[clamp(10px,1.5vh,16px)] flex items-center gap-4"
                                >
                                    <div className="w-10 h-10 rounded-full bg-primary-700/[0.05] border border-primary-700/[0.08] flex items-center justify-center shrink-0">
                                        <span className="font-display font-semibold text-primary-700/30 text-[0.7rem]">
                                            {member.initials}
                                        </span>
                                    </div>
                                    <div className="act-3-member">
                                        <span
                                            className="font-display font-semibold italic text-primary-900/80 block"
                                            style={{
                                                fontSize:
                                                    "clamp(1.1rem, 2.2vw, 1.6rem)",
                                            }}
                                        >
                                            {member.name}
                                        </span>
                                        <span className="font-body text-slate-500 text-[0.78rem] block mt-0.5">
                                            {member.desc}
                                        </span>
                                    </div>
                                </div>
                            ))}

                            {/* ITS Gold terminal flourish */}
                            <div
                                data-gold
                                className="mt-10 origin-left"
                                style={{
                                    height: "1px",
                                    background: "#FFC72C",
                                    width: "8rem",
                                }}
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
                        {ACT3_COLLAGE.map((item, i) => (
                            <div
                                key={i}
                                className={`absolute group ${item.center ? "act-3-collage-center" : "act-3-collage-item"}`}
                                style={{
                                    left: item.left,
                                    top: item.top,
                                    width: item.width,
                                    aspectRatio: item.aspectRatio,
                                    transform: `rotate(${item.rot}deg)`,
                                    padding: "clamp(4px, 0.5vw, 8px)",
                                    background: "white",
                                    boxShadow: item.shadow,
                                    zIndex: item.z,
                                }}
                            >
                                <div className="relative w-full h-full overflow-hidden">
                                    <img
                                        src={item.image}
                                        alt=""
                                        className="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 ease-out group-hover:scale-105"
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
                    03
                </div>
            </div>
        </section>
    );
}

// ── Desktop pinned choreography ──────────────────────────────────────

function buildDesktop(section: HTMLElement) {
    /* ACT 1 ── Head of Laboratory */
    const act1 = section.querySelector<HTMLElement>(".act-1")!;
    const a1c = act1.querySelector<HTMLElement>(".act-1-content")!;
    const a1eb = act1.querySelector(".act-1-eyebrow");
    const a1avatar = act1.querySelector(".act-1-avatar");
    const a1words = act1.querySelectorAll(".act-1-word");
    const a1role = act1.querySelector(".act-1-role");
    const a1hl = act1.querySelector(".act-1-hairline");
    const a1desc = act1.querySelector(".act-1-desc");
    const a1hexCenter = act1.querySelector(".act-1-hex-center");
    const a1hexItems = act1.querySelectorAll(".act-1-hex-item");

    gsap.set(a1eb, { y: 28, opacity: 0 });
    gsap.set(a1avatar, { scale: 0.7, opacity: 0 });
    gsap.set(a1words, { y: "110%" });
    gsap.set(a1role, { x: -18, opacity: 0 });
    gsap.set(a1hl, { scaleX: 0 });
    gsap.set(a1desc, { y: 34, opacity: 0 });
    gsap.set(a1hexCenter, { scale: 0.55, opacity: 0 });
    gsap.set(a1hexItems, { scale: 0.4, opacity: 0 });

    const tl1 = gsap.timeline({
        scrollTrigger: {
            trigger: act1,
            start: "top top",
            end: "+=150%",
            pin: true,
            scrub: 1,
            anticipatePin: 1,
        },
    });

    tl1.to(a1eb, { y: 0, opacity: 1, duration: 0.06, ease: "none" }, 0)
        .to(
            a1avatar,
            { scale: 1, opacity: 1, duration: 0.12, ease: "power3.out" },
            0.02,
        )
        .to(
            a1words,
            { y: "0%", duration: 0.28, stagger: 0.08, ease: "power3.out" },
            0.08,
        )
        .to(a1role, { x: 0, opacity: 1, duration: 0.1, ease: "none" }, 0.3)
        .to(a1hl, { scaleX: 1, duration: 0.12, ease: "none" }, 0.37)
        .to(a1desc, { y: 0, opacity: 1, duration: 0.14, ease: "none" }, 0.44)
        .to(
            a1hexCenter,
            { scale: 1, opacity: 1, duration: 0.16, ease: "power3.out" },
            0.14,
        )
        .to(
            a1hexItems,
            {
                scale: 1,
                opacity: 1,
                duration: 0.12,
                stagger: 0.05,
                ease: "power3.out",
            },
            0.27,
        )
        .to({}, { duration: 0.18 })
        .to(a1c, { y: -52, opacity: 0, duration: 0.16, ease: "none" });

    /* ACT 2 ── IDIG HTECH */
    const act2 = section.querySelector<HTMLElement>(".act-2")!;
    const a2c = act2.querySelector<HTMLElement>(".act-2-content")!;
    const a2eb = act2.querySelector(".act-2-eyebrow");
    const a2avatar = act2.querySelector(".act-2-avatar");
    const a2words = act2.querySelectorAll(".act-2-word");
    const a2role = act2.querySelector(".act-2-role");
    const a2hl = act2.querySelector(".act-2-hairline");
    const a2desc = act2.querySelector(".act-2-desc");
    const a2members = act2.querySelectorAll(".act-2-member");
    const spine2 = act2.querySelector<SVGPathElement>(".act-2-spine");
    const a2collageCenter = act2.querySelector(".act-2-collage-center");
    const a2collageItems = act2.querySelectorAll(".act-2-collage-item");

    gsap.set(a2eb, { y: 28, opacity: 0 });
    gsap.set(a2avatar, { scale: 0.7, opacity: 0 });
    gsap.set(a2words, { y: "110%" });
    gsap.set(a2role, { x: 18, opacity: 0 });
    gsap.set(a2hl, { scaleX: 0 });
    gsap.set(a2desc, { y: 24, opacity: 0 });
    gsap.set(a2members, { y: 28, opacity: 0 });
    gsap.set(a2collageCenter, { scale: 0.8, opacity: 0 });
    gsap.set(a2collageItems, { y: 40, opacity: 0 });
    if (spine2) {
        const len2 = spine2.getTotalLength();
        gsap.set(spine2, { strokeDasharray: len2, strokeDashoffset: len2 });
    }

    const tl2 = gsap.timeline({
        scrollTrigger: {
            trigger: act2,
            start: "top top",
            end: "+=150%",
            pin: true,
            scrub: 1,
            anticipatePin: 1,
        },
    });

    tl2.to(a2eb, { y: 0, opacity: 1, duration: 0.06, ease: "none" }, 0)
        .to(
            a2avatar,
            { scale: 1, opacity: 1, duration: 0.12, ease: "power3.out" },
            0.02,
        )
        .to(
            a2words,
            { y: "0%", duration: 0.28, stagger: 0.08, ease: "power3.out" },
            0.08,
        )
        .to(a2role, { x: 0, opacity: 1, duration: 0.1, ease: "none" }, 0.3)
        .to(a2hl, { scaleX: 1, duration: 0.12, ease: "none" }, 0.37)
        .to(a2desc, { y: 0, opacity: 1, duration: 0.1, ease: "none" }, 0.42)
        .to(
            a2collageCenter,
            { scale: 1, opacity: 1, duration: 0.16, ease: "power3.out" },
            0.14,
        )
        .to(
            a2collageItems,
            {
                y: 0,
                opacity: 1,
                duration: 0.12,
                stagger: 0.05,
                ease: "power3.out",
            },
            0.2,
        )
        .to(spine2, { strokeDashoffset: 0, duration: 0.2, ease: "none" }, 0.46)
        .to(
            a2members,
            {
                y: 0,
                opacity: 1,
                duration: 0.08,
                stagger: 0.045,
                ease: "power3.out",
            },
            0.5,
        )
        .to({}, { duration: 0.16 })
        .to(a2c, { y: -52, opacity: 0, duration: 0.16, ease: "none" });

    /* ACT 3 ── IDIG RCMED */
    const act3 = section.querySelector<HTMLElement>(".act-3")!;
    const a3eb = act3.querySelector(".act-3-eyebrow");
    const a3avatar = act3.querySelector(".act-3-avatar");
    const a3words = act3.querySelectorAll(".act-3-word");
    const a3role = act3.querySelector(".act-3-role");
    const a3hl = act3.querySelector(".act-3-hairline");
    const a3desc = act3.querySelector(".act-3-desc");
    const a3members = act3.querySelectorAll(".act-3-member");
    const spine3 = act3.querySelector<SVGPathElement>(".act-3-spine");
    const goldEl = act3.querySelector<HTMLElement>("[data-gold]");
    const a3collageCenter = act3.querySelector(".act-3-collage-center");
    const a3collageItems = act3.querySelectorAll(".act-3-collage-item");

    gsap.set(a3eb, { y: 28, opacity: 0 });
    gsap.set(a3avatar, { scale: 0.7, opacity: 0 });
    gsap.set(a3words, { y: "110%" });
    gsap.set(a3role, { x: -18, opacity: 0 });
    gsap.set(a3hl, { scaleX: 0 });
    gsap.set(a3desc, { y: 24, opacity: 0 });
    gsap.set(a3members, { y: 28, opacity: 0 });
    gsap.set(a3collageCenter, { scale: 0.8, opacity: 0 });
    gsap.set(a3collageItems, { y: 40, opacity: 0 });
    gsap.set(goldEl, { scaleX: 0 });
    if (spine3) {
        const len3 = spine3.getTotalLength();
        gsap.set(spine3, { strokeDasharray: len3, strokeDashoffset: len3 });
    }

    const tl3 = gsap.timeline({
        scrollTrigger: {
            trigger: act3,
            start: "top top",
            end: "+=150%",
            pin: true,
            scrub: 1,
            anticipatePin: 1,
        },
    });

    tl3.to(a3eb, { y: 0, opacity: 1, duration: 0.06, ease: "none" }, 0)
        .to(
            a3avatar,
            { scale: 1, opacity: 1, duration: 0.12, ease: "power3.out" },
            0.02,
        )
        .to(
            a3words,
            { y: "0%", duration: 0.28, stagger: 0.08, ease: "power3.out" },
            0.08,
        )
        .to(a3role, { x: 0, opacity: 1, duration: 0.1, ease: "none" }, 0.3)
        .to(a3hl, { scaleX: 1, duration: 0.12, ease: "none" }, 0.37)
        .to(a3desc, { y: 0, opacity: 1, duration: 0.1, ease: "none" }, 0.42)
        .to(
            a3collageCenter,
            { scale: 1, opacity: 1, duration: 0.16, ease: "power3.out" },
            0.14,
        )
        .to(
            a3collageItems,
            {
                y: 0,
                opacity: 1,
                duration: 0.12,
                stagger: 0.05,
                ease: "power3.out",
            },
            0.2,
        )
        .to(spine3, { strokeDashoffset: 0, duration: 0.2, ease: "none" }, 0.46)
        .to(
            a3members,
            {
                y: 0,
                opacity: 1,
                duration: 0.08,
                stagger: 0.045,
                ease: "power3.out",
            },
            0.5,
        )
        .to(goldEl, { scaleX: 1, duration: 0.14, ease: "none" }, 0.88)
        .to({}, { duration: 0.08 });
}

// ── Mobile / reduced-motion fallback ────────────────────────────────

function buildMobile(section: HTMLElement) {
    const prefersReduced = window.matchMedia(
        "(prefers-reduced-motion: reduce)",
    ).matches;

    // Ensure spines always render fully drawn
    section
        .querySelectorAll<SVGPathElement>("path[class*='-spine']")
        .forEach((spine) => {
            const len = spine.getTotalLength();
            gsap.set(spine, { strokeDasharray: len, strokeDashoffset: 0 });
        });

    if (prefersReduced) return; // Leave everything in natural visible state

    (["1", "2", "3"] as const).forEach((n) => {
        const act = section.querySelector<HTMLElement>(`.act-${n}`);
        if (!act) return;

        const eyebrow = act.querySelector(`.act-${n}-eyebrow`);
        const avatar = act.querySelector(`.act-${n}-avatar`);
        const words = act.querySelectorAll(`.act-${n}-word`);
        const role = act.querySelector(`.act-${n}-role`);
        const hl = act.querySelector(`.act-${n}-hairline`);
        const desc = act.querySelector(`.act-${n}-desc`);
        const members = act.querySelectorAll(`.act-${n}-member`);
        const goldEl = act.querySelector<HTMLElement>("[data-gold]");
        const spine = act.querySelector<SVGPathElement>(
            "path[class*='-spine']",
        );

        const fadeEls = [
            eyebrow,
            avatar,
            ...Array.from(words),
            role,
            ...Array.from(members),
        ].filter(Boolean);
        if (desc) fadeEls.push(desc);

        gsap.set(fadeEls, { y: 28, opacity: 0 });
        if (hl) gsap.set(hl, { scaleX: 0 });
        if (goldEl) gsap.set(goldEl, { scaleX: 0 });

        gsap.to(fadeEls, {
            y: 0,
            opacity: 1,
            duration: 0.75,
            stagger: 0.07,
            ease: "power3.out",
            scrollTrigger: { trigger: act, start: "top 84%" },
        });

        if (hl) {
            gsap.to(hl, {
                scaleX: 1,
                duration: 0.55,
                ease: "power2.out",
                scrollTrigger: { trigger: act, start: "top 82%" },
            });
        }
        if (goldEl) {
            gsap.to(goldEl, {
                scaleX: 1,
                duration: 0.55,
                ease: "power2.out",
                scrollTrigger: { trigger: act, start: "top 72%" },
            });
        }
        if (spine) {
            const len = spine.getTotalLength();
            gsap.set(spine, { strokeDasharray: len, strokeDashoffset: len });
            gsap.to(spine, {
                strokeDashoffset: 0,
                duration: 1.1,
                ease: "power2.out",
                scrollTrigger: { trigger: act, start: "top 78%" },
            });
        }
    });
}
