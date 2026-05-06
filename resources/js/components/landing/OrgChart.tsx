import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { useRef } from "react";

gsap.registerPlugin(ScrollTrigger);

// ── Data ────────────────────────────────────────────────────────────

const SPINE_ROW_H = 100;

const HEAD = {
    display: ["Tri Arief", "Sardjono."],
    full: "Prof. Dr. Ir. Tri Arief Sardjono, S.T., M.T., Ph.D.",
    roleId: "Kepala Laboratorium",
    roleEn: "Head of Laboratory",
    desc: "Leading the strategic vision and research initiatives at IDIG Health Tech, bridging engineering and medical innovation.",
    initials: "TS",
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
    },
    members: [
        { name: "Dr. Torib Hamzah", desc: "Biomedical signal processing", initials: "TH" },
        { name: "Dr. Bagus Setya B.", desc: "Medical device prototyping", initials: "BS" },
        { name: "Dr. Siti Aminah", desc: "Clinical data analytics", initials: "SA" },
        { name: "Dr. Budi Santoso", desc: "Embedded systems engineering", initials: "BD" },
        { name: "Dr. Andi Wijaya", desc: "Biomechanics and rehabilitation", initials: "AW" },
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
    },
    members: [
        { name: "Dr. Srisulistiowati", desc: "Medical imaging analysis", initials: "SS" },
        { name: "Dr. Nita Handayani", desc: "Pharmacoinformatics", initials: "NH" },
        { name: "Dr. Ratna Sari", desc: "Computational genomics", initials: "RS" },
        { name: "Dr. Hendra Gunawan", desc: "Health informatics systems", initials: "HG" },
        { name: "Dr. Dian Pertiwi", desc: "Telemedicine platforms", initials: "DP" },
    ] as TeamMember[],
};

// ── Component ────────────────────────────────────────────────────────

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
                ACT 1 — Kepala Laboratorium
                ───────────────────────────────────────────────────────── */}
            <div className="act-1 relative overflow-hidden flex flex-col justify-center px-[clamp(24px,7vw,120px)] py-[clamp(80px,14vh,120px)] md:h-screen md:py-0">
                <div
                    className="absolute inset-y-0 left-0 w-[55vw] pointer-events-none"
                    aria-hidden="true"
                    style={{
                        background:
                            "radial-gradient(ellipse at -10% 50%, rgba(0,66,109,0.04) 0%, transparent 55%)",
                    }}
                />

                <div className="act-1-content max-w-4xl relative z-10">
                    <div className="act-1-eyebrow flex items-center gap-3 mb-12">
                        <div className="w-8 h-px bg-secondary-500/30" />
                        <span className="font-body font-semibold uppercase tracking-[0.3em] text-[0.68rem] text-primary-700/50">
                            Chapter 01 — Kepala Laboratorium
                        </span>
                    </div>

                    {/* Avatar */}
                    <div className="act-1-avatar mb-8 flex items-center gap-6">
                        <div className="w-20 h-20 md:w-24 md:h-24 rounded-full bg-primary-700/[0.07] border border-primary-700/10 flex items-center justify-center shrink-0">
                            <span className="font-display font-bold text-primary-700/40 text-xl md:text-2xl">{HEAD.initials}</span>
                        </div>
                    </div>

                    <h3
                        className="font-display font-extrabold italic leading-none tracking-[-0.02em] text-primary-900 mb-8"
                        style={{ fontSize: "clamp(2.8rem, 9vw, 7rem)" }}
                        aria-label={HEAD.full}
                    >
                        {HEAD.display.map((word, i) => (
                            <span key={i} className="block overflow-hidden">
                                <span className="block act-1-word">{word}</span>
                            </span>
                        ))}
                    </h3>

                    <div className="mb-2">
                        <span
                            className="act-1-role font-body font-medium text-secondary-500"
                            style={{
                                fontSize: "clamp(0.82rem, 1.05vw, 0.92rem)",
                            }}
                        >
                            {HEAD.roleId} · {HEAD.roleEn}
                        </span>
                        <div className="act-1-hairline h-px bg-secondary-500/30 mt-2 origin-left" />
                    </div>

                    <p
                        className="act-1-desc font-body text-slate-600 leading-[1.78] mt-8"
                        style={{
                            fontSize: "clamp(0.92rem, 1.25vw, 1.02rem)",
                            maxWidth: "54ch",
                        }}
                    >
                        {HEAD.desc}
                    </p>
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

                <div className="act-2-content max-w-4xl ml-auto text-right relative z-10">
                    <div className="act-2-eyebrow flex items-center gap-3 mb-12 justify-end">
                        <span className="font-body font-semibold uppercase tracking-[0.3em] text-[0.68rem] text-primary-700/50">
                            Chapter 02 — Tim IDIG HTECH
                        </span>
                        <div className="w-8 h-px bg-secondary-500/30" />
                    </div>

                    {/* Lead Avatar */}
                    <div className="act-2-avatar mb-8 flex items-center gap-5 justify-end">
                        <div className="w-16 h-16 md:w-20 md:h-20 rounded-full bg-primary-700/[0.07] border border-primary-700/10 flex items-center justify-center shrink-0">
                            <span className="font-display font-bold text-primary-700/40 text-lg md:text-xl">{HTECH.lead.initials}</span>
                        </div>
                    </div>

                    <h3
                        className="font-display font-extrabold italic leading-none tracking-[-0.02em] text-primary-900 mb-8"
                        style={{ fontSize: "clamp(2.8rem, 9vw, 7rem)" }}
                        aria-label={HTECH.lead.full}
                    >
                        {HTECH.lead.display.map((word, i) => (
                            <span key={i} className="block overflow-hidden">
                                <span className="block act-2-word">{word}</span>
                            </span>
                        ))}
                    </h3>

                    <div className="mb-2">
                        <span
                            className="act-2-role font-body font-medium text-secondary-500"
                            style={{
                                fontSize: "clamp(0.82rem, 1.05vw, 0.92rem)",
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
                                            fontSize: "clamp(1.1rem, 2.2vw, 1.6rem)",
                                        }}
                                    >
                                        {member.name}
                                    </span>
                                    <span className="font-body text-slate-500 text-[0.78rem] block mt-0.5">
                                        {member.desc}
                                    </span>
                                </div>
                                <div className="w-10 h-10 rounded-full bg-primary-700/[0.05] border border-primary-700/[0.08] flex items-center justify-center shrink-0">
                                    <span className="font-display font-semibold text-primary-700/30 text-[0.7rem]">{member.initials}</span>
                                </div>
                            </div>
                        ))}
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

                <div className="act-3-content max-w-4xl relative z-10">
                    <div className="act-3-eyebrow flex items-center gap-3 mb-12">
                        <div className="w-8 h-px bg-secondary-500/30" />
                        <span className="font-body font-semibold uppercase tracking-[0.3em] text-[0.68rem] text-primary-700/50">
                            Chapter 03 — Tim IDIG RCMED
                        </span>
                    </div>

                    {/* Lead Avatar */}
                    <div className="act-3-avatar mb-8 flex items-center gap-5">
                        <div className="w-16 h-16 md:w-20 md:h-20 rounded-full bg-primary-700/[0.07] border border-primary-700/10 flex items-center justify-center shrink-0">
                            <span className="font-display font-bold text-primary-700/40 text-lg md:text-xl">{RCMED.lead.initials}</span>
                        </div>
                    </div>

                    <h3
                        className="font-display font-extrabold italic leading-none tracking-[-0.02em] text-primary-900 mb-8"
                        style={{ fontSize: "clamp(2.8rem, 9vw, 7rem)" }}
                        aria-label={RCMED.lead.full}
                    >
                        {RCMED.lead.display.map((word, i) => (
                            <span key={i} className="block overflow-hidden">
                                <span className="block act-3-word">{word}</span>
                            </span>
                        ))}
                    </h3>

                    <div className="mb-2">
                        <span
                            className="act-3-role font-body font-medium text-secondary-500"
                            style={{
                                fontSize: "clamp(0.82rem, 1.05vw, 0.92rem)",
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
                                    <span className="font-display font-semibold text-primary-700/30 text-[0.7rem]">{member.initials}</span>
                                </div>
                                <div className="act-3-member">
                                    <span
                                        className="font-display font-semibold italic text-primary-900/80 block"
                                        style={{
                                            fontSize: "clamp(1.1rem, 2.2vw, 1.6rem)",
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

    gsap.set(a1eb, { y: 28, opacity: 0 });
    gsap.set(a1avatar, { scale: 0.7, opacity: 0 });
    gsap.set(a1words, { y: "110%" });
    gsap.set(a1role, { x: -18, opacity: 0 });
    gsap.set(a1hl, { scaleX: 0 });
    gsap.set(a1desc, { y: 34, opacity: 0 });

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
        .to(a1avatar, { scale: 1, opacity: 1, duration: 0.12, ease: "power3.out" }, 0.02)
        .to(
            a1words,
            { y: "0%", duration: 0.28, stagger: 0.08, ease: "power3.out" },
            0.08,
        )
        .to(a1role, { x: 0, opacity: 1, duration: 0.1, ease: "none" }, 0.3)
        .to(a1hl, { scaleX: 1, duration: 0.12, ease: "none" }, 0.37)
        .to(a1desc, { y: 0, opacity: 1, duration: 0.14, ease: "none" }, 0.44)
        .to({}, { duration: 0.22 })
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

    gsap.set(a2eb, { y: 28, opacity: 0 });
    gsap.set(a2avatar, { scale: 0.7, opacity: 0 });
    gsap.set(a2words, { y: "110%" });
    gsap.set(a2role, { x: 18, opacity: 0 });
    gsap.set(a2hl, { scaleX: 0 });
    gsap.set(a2desc, { y: 24, opacity: 0 });
    gsap.set(a2members, { y: 28, opacity: 0 });
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
        .to(a2avatar, { scale: 1, opacity: 1, duration: 0.12, ease: "power3.out" }, 0.02)
        .to(
            a2words,
            { y: "0%", duration: 0.28, stagger: 0.08, ease: "power3.out" },
            0.08,
        )
        .to(a2role, { x: 0, opacity: 1, duration: 0.1, ease: "none" }, 0.3)
        .to(a2hl, { scaleX: 1, duration: 0.12, ease: "none" }, 0.37)
        .to(a2desc, { y: 0, opacity: 1, duration: 0.1, ease: "none" }, 0.42)
        .to(spine2, { strokeDashoffset: 0, duration: 0.2, ease: "none" }, 0.46)
        .to(
            a2members,
            { y: 0, opacity: 1, duration: 0.08, stagger: 0.045, ease: "power3.out" },
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

    gsap.set(a3eb, { y: 28, opacity: 0 });
    gsap.set(a3avatar, { scale: 0.7, opacity: 0 });
    gsap.set(a3words, { y: "110%" });
    gsap.set(a3role, { x: -18, opacity: 0 });
    gsap.set(a3hl, { scaleX: 0 });
    gsap.set(a3desc, { y: 24, opacity: 0 });
    gsap.set(a3members, { y: 28, opacity: 0 });
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
        .to(a3avatar, { scale: 1, opacity: 1, duration: 0.12, ease: "power3.out" }, 0.02)
        .to(
            a3words,
            { y: "0%", duration: 0.28, stagger: 0.08, ease: "power3.out" },
            0.08,
        )
        .to(a3role, { x: 0, opacity: 1, duration: 0.1, ease: "none" }, 0.3)
        .to(a3hl, { scaleX: 1, duration: 0.12, ease: "none" }, 0.37)
        .to(a3desc, { y: 0, opacity: 1, duration: 0.1, ease: "none" }, 0.42)
        .to(spine3, { strokeDashoffset: 0, duration: 0.2, ease: "none" }, 0.46)
        .to(
            a3members,
            { y: 0, opacity: 1, duration: 0.08, stagger: 0.045, ease: "power3.out" },
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
