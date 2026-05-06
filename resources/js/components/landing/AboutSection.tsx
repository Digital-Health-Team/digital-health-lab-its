import { useRef } from "react";
import { useGSAP } from "@gsap/react";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

/* ══════════════════════════════════════════════════════════════════
   CONTENT DATA
   ══════════════════════════════════════════════════════════════════ */

const capabilities = [
    {
        tag: "3D Innovation",
        title: "Cetak Tiga Dimensi Presisi Tinggi",
        description:
            "Perancangan dan fabrikasi implan, prostetik, serta model anatomi menggunakan teknologi additive manufacturing dengan material biokompatibel.",
        accent: "#00A8B5",
    },
    {
        tag: "Custom Order",
        title: "Layanan Desain & Produksi Kustom",
        description:
            "Layanan berbasis pesanan untuk rumah sakit, klinik, dan institusi pendidikan. Dari konsep digital hingga produk fisik siap pakai.",
        accent: "#FFC72C",
    },
    {
        tag: "Digital Repository",
        title: "Repositori Publikasi Terpusat",
        description:
            "Sentralisasi jurnal, laporan riset, dan dokumentasi teknis dalam satu platform terbuka yang mendukung akses dan kolaborasi lintas disiplin.",
        accent: "#22D3EE",
    },
] as const;

interface TeamMember {
    name: string;
    degree: string;
    position: string;
    initials: string;
}

const teamMembers: TeamMember[] = [
    {
        name: "Prof. Dr. Ir. Tri Arief Sardjono",
        degree: "M.Eng., Ph.D.",
        position: "Kepala Laboratorium",
        initials: "TA",
    },
    {
        name: "Dr. Achmad Arifin",
        degree: "S.T., M.Eng.",
        position: "Ketua Penelitian",
        initials: "AA",
    },
    {
        name: "Dr. Mauridhi Hery Purnomo",
        degree: "M.Eng.",
        position: "Direktur Teknologi",
        initials: "MH",
    },
    {
        name: "Dr. Nita Handayani",
        degree: "S.T., M.T.",
        position: "Direktur Laboratorium",
        initials: "NH",
    },
    {
        name: "Dr. Torib Hamzah",
        degree: "S.T., M.T.",
        position: "Kepala Produk",
        initials: "TH",
    },
];

/* Headline words for Act 1 word-by-word reveal */
const headlineWords: {
    word: string;
    accent: boolean;
    lineBreakAfter: boolean;
}[] = [
    { word: "Menjembatani", accent: false, lineBreakAfter: false },
    { word: "Inovasi", accent: false, lineBreakAfter: true },
    { word: "Kesehatan", accent: false, lineBreakAfter: false },
    { word: "dan", accent: false, lineBreakAfter: false },
    { word: "Rekayasa.", accent: true, lineBreakAfter: false },
];

/* ══════════════════════════════════════════════════════════════════
   MAIN ABOUT SECTION — SCROLL-PINNED CINEMATIC CHAPTERS

   Three acts, each pinned to viewport during scroll:
   Act 1: Vision — word-by-word headline reveal
   Act 2: Capabilities — scrubbed capability stack-up
   Act 3: Team — staggered member reveals

   Design decisions:
   – Dark ground state per DESIGN.md "Institute Midnight Rule"
   – Only opacity and transform animated (no layout properties)
   – prefers-reduced-motion: shows all content statically
   – Desktop: full pinned experience; Mobile: simple trigger animations
   ══════════════════════════════════════════════════════════════════ */

export default function AboutSection() {
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
                        "(max-width: 767px) and (prefers-reduced-motion: no-preference)",
                },
                (context) => {
                    const { isDesktop } = context.conditions!;

                    if (isDesktop) {
                        buildDesktopPinnedExperience(section);
                    } else {
                        buildMobileAnimations(section);
                    }
                },
            );
        },
        { scope: sectionRef },
    );

    return (
        <section
            ref={sectionRef}
            id="about"
            className="relative bg-primary-900"
        >
            {/* ═══════════════════════════════════════════════════
                ACT 1 — THE VISION
                Pinned full-viewport. Headline words cascade in.
               ═══════════════════════════════════════════════════ */}
            <div className="act-1 relative h-screen overflow-hidden">
                {/* Background layers */}
                <div className="absolute inset-0 honeycomb-dark opacity-[0.04] pointer-events-none" />
                <div
                    className="act1-glow absolute bottom-0 left-0 w-[70vw] h-[60vh] pointer-events-none select-none"
                    aria-hidden="true"
                    style={{
                        background:
                            "radial-linear(ellipse at 20% 90%, rgba(0,168,181,0.1) 0%, transparent 55%)",
                    }}
                />

                {/* Content */}
                <div className="act-content relative z-10 h-full flex flex-col justify-center px-[clamp(24px,5vw,48px)]">
                    <div className="max-w-5xl mx-auto w-full">
                        {/* Chapter marker */}
                        <div className="act1-label anim-el flex items-center gap-3 mb-10">
                            <div className="w-10 h-px bg-secondary-400/40" />
                            <span className="text-[0.68rem] font-body font-semibold tracking-[0.3em] uppercase text-secondary-400/70">
                                Tentang Kami
                            </span>
                        </div>

                        {/* Headline — word-by-word reveal */}
                        <h2
                            className="font-display font-extrabold leading-[1.05] tracking-tight max-w-[18ch]"
                            style={{ fontSize: "clamp(2.4rem, 6.5vw, 4.5rem)" }}
                        >
                            {headlineWords.map((item, i) => (
                                <span key={i}>
                                    <span className="inline-block overflow-hidden">
                                        <span
                                            className={`hw inline-block ${
                                                item.accent
                                                    ? "text-secondary-400"
                                                    : "text-[#F8FAFC]"
                                            }`}
                                        >
                                            {item.word}
                                        </span>
                                    </span>
                                    {item.lineBreakAfter ? (
                                        <>
                                            {" "}
                                            <br className="hidden md:block" />
                                        </>
                                    ) : (
                                        " "
                                    )}
                                </span>
                            ))}
                        </h2>

                        {/* Body text */}
                        <div className="act1-body anim-el mt-10 md:mt-14 flex flex-col gap-8 max-w-2xl">
                            <p
                                className="font-body text-white leading-loose pl-5 border-l border-yellow-400"
                                style={{
                                    fontSize: "clamp(0.95rem, 1.5vw, 1.08rem)",
                                }}
                            >
                                Laboratorium Teknologi Medis ITS berdiri sebagai pionir yang menjembatani dunia riset akademis multidisiplin dengan kebutuhan nyata pada sektor layanan kesehatan nasional. Kami berdedikasi penuh untuk menghadirkan berbagai solusi rekayasa biomedis yang inovatif, presisi, serta diproduksi dengan standar kualitas tinggi yang telah tervalidasi secara klinis, terdokumentasi secara komprehensif, dan siap untuk didistribusikan.
                            </p>
                            <p
                                className="font-body text-white leading-loose pl-5 border-l border-yellow-400"
                                style={{
                                    fontSize: "clamp(0.95rem, 1.5vw, 1.08rem)",
                                }}
                            >
                                Melalui sinergi kuat antara peneliti, praktisi medis, dan insinyur profesional, kami bertransformasi menjadi pusat unggulan dalam pengembangan prostetik, implan kustom, serta perangkat medis lainnya. Komitmen utama kami adalah mendobrak batas konvensional teknologi manufaktur medis demi meningkatkan kualitas hidup pasien serta mendorong kemandirian fasilitas kesehatan di seluruh Indonesia.
                            </p>
                        </div>
                    </div>
                </div>

                {/* Act number — subtle chapter indicator */}
                <div className="absolute bottom-8 right-8 md:right-12 text-[#F8FAFC]/6 font-display font-extrabold text-[6rem] md:text-[8rem] leading-none select-none pointer-events-none">
                    01
                </div>
            </div>

            {/* ═══════════════════════════════════════════════════
                ACT 2 — CAPABILITIES
                Pinned. Capabilities stack up as you scroll.
                Vertical spine grows between items.
               ═══════════════════════════════════════════════════ */}
            <div className="act-2 relative h-screen overflow-hidden">
                <div className="absolute inset-0 honeycomb-dark opacity-[0.04] pointer-events-none" />
                <div
                    className="absolute top-0 right-0 w-[50vw] h-[50vh] pointer-events-none select-none"
                    aria-hidden="true"
                    style={{
                        background:
                            "radial-linear(ellipse at 80% 20%, rgba(34,211,238,0.06) 0%, transparent 55%)",
                    }}
                />

                <div className="act-content relative z-10 h-full flex flex-col justify-center px-[clamp(24px,5vw,48px)]">
                    <div className="max-w-5xl mx-auto w-full">
                        {/* Header */}
                        <div className="act2-header anim-el">
                            <span className="inline-block text-[0.65rem] font-body font-semibold tracking-[0.25em] uppercase text-secondary-400/60 mb-3">
                                Kapabilitas
                            </span>
                            <h3
                                className="font-display font-bold text-[#F8FAFC] leading-[1.12] tracking-tight"
                                style={{
                                    fontSize: "clamp(1.6rem, 3.5vw, 2.4rem)",
                                }}
                            >
                                Tiga Pilar Keunggulan
                            </h3>
                        </div>

                        {/* Capability items with spine */}
                        <div className="mt-12 md:mt-16 relative">
                            {/* Vertical progress spine */}
                            <div
                                className="cap-spine absolute left-[clamp(24px,3vw,36px)] top-0 bottom-0 w-px hidden md:block"
                                style={{
                                    background:
                                        "linear-linear(to bottom, rgba(0,168,181,0.4), rgba(34,211,238,0.15) 50%, rgba(255,199,44,0.3))",
                                    transformOrigin: "top",
                                }}
                            />

                            <div className="space-y-0">
                                {capabilities.map((cap, i) => (
                                    <div
                                        key={cap.tag}
                                        className="cap-item anim-el flex flex-col md:flex-row md:items-start gap-4 md:gap-10 border-t border-[#F8FAFC]/6 py-8 md:py-10 first:border-t-0 first:pt-0 md:pl-[clamp(56px,5vw,72px)]"
                                    >
                                        {/* Number */}
                                        <span
                                            className="cap-num font-display font-bold leading-none shrink-0"
                                            style={{
                                                fontSize:
                                                    "clamp(2rem, 3.5vw, 2.8rem)",
                                                color: cap.accent,
                                                opacity: 0.35,
                                                width: "clamp(40px, 5vw, 60px)",
                                            }}
                                        >
                                            {String(i + 1).padStart(2, "0")}
                                        </span>

                                        <div className="flex-1 min-w-0">
                                            <span className="text-[0.65rem] font-body font-semibold tracking-[0.2em] uppercase text-secondary-400/50 block mb-2">
                                                {cap.tag}
                                            </span>
                                            <h4
                                                className="font-display font-bold text-[#F8FAFC] leading-snug mb-3"
                                                style={{
                                                    fontSize:
                                                        "clamp(1.05rem, 1.8vw, 1.3rem)",
                                                }}
                                            >
                                                {cap.title}
                                            </h4>
                                            <p
                                                className="font-body text-[#94A3B8] leading-[1.7]"
                                                style={{
                                                    fontSize:
                                                        "clamp(0.85rem, 1.2vw, 0.94rem)",
                                                    maxWidth: "52ch",
                                                }}
                                            >
                                                {cap.description}
                                            </p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>

                <div className="absolute bottom-8 right-8 md:right-12 text-[#F8FAFC]/6 font-display font-extrabold text-[6rem] md:text-[8rem] leading-none select-none pointer-events-none">
                    02
                </div>
            </div>
        </section>
    );
}

/* ══════════════════════════════════════════════════════════════════
   DESKTOP PINNED EXPERIENCE
   Each act pins to viewport and scrubs through a GSAP timeline.
   ══════════════════════════════════════════════════════════════════ */

function buildDesktopPinnedExperience(section: HTMLElement) {
    /* ── ACT 1: THE VISION ── */
    const act1 = section.querySelector<HTMLElement>(".act-1")!;
    const act1Words = act1.querySelectorAll(".hw");
    const act1Label = act1.querySelector(".act1-label");
    const act1Body = act1.querySelector(".act1-body");
    const act1Glow = act1.querySelector(".act1-glow");

    /* Set initial states for word reveal */
    gsap.set(act1Words, { y: "110%" });
    gsap.set(act1Label, { y: 30, opacity: 0 });
    gsap.set(act1Body, { y: 40, opacity: 0 });

    const tl1 = gsap.timeline({
        scrollTrigger: {
            trigger: act1,
            start: "top top",
            end: "+=130%",
            pin: true,
            scrub: 1,
            anticipatePin: 1,
        },
    });

    tl1.to(act1Label, { y: 0, opacity: 1, duration: 0.08, ease: "none" })
        .to(
            act1Words,
            {
                y: "0%",
                duration: 0.3,
                stagger: 0.04,
                ease: "power3.out",
            },
            0.04,
        )
        .to(act1Body, { y: 0, opacity: 1, duration: 0.15, ease: "none" }, 0.35)
        /* Glow shifts subtly */
        .to(act1Glow, { x: 30, y: -20, duration: 0.5, ease: "none" }, 0)
        /* Hold content visible */
        .to({}, { duration: 0.2 })
        /* Exit: fade out and drift up */
        .to(act1.querySelector(".act-content")!, {
            opacity: 0,
            y: -50,
            duration: 0.15,
            ease: "none",
        });

    /* ── ACT 2: CAPABILITIES ── */
    const act2 = section.querySelector<HTMLElement>(".act-2")!;
    const act2Header = act2.querySelector(".act2-header");
    const act2Items = act2.querySelectorAll(".cap-item");
    const act2Spine = act2.querySelector(".cap-spine");

    gsap.set(act2Header, { y: 40, opacity: 0 });
    gsap.set(act2Items, { y: 60, opacity: 0 });
    if (act2Spine) {
        gsap.set(act2Spine, { scaleY: 0, transformOrigin: "top" });
    }

    const tl2 = gsap.timeline({
        scrollTrigger: {
            trigger: act2,
            start: "top top",
            end: "+=220%",
            pin: true,
            scrub: 1,
            anticipatePin: 1,
        },
    });

    tl2.to(act2Header, { y: 0, opacity: 1, duration: 0.08, ease: "none" });

    /* Each capability enters progressively */
    act2Items.forEach((item, i) => {
        const startPos = 0.08 + i * 0.2;
        tl2.to(
            item,
            { y: 0, opacity: 1, duration: 0.12, ease: "none" },
            startPos,
        );

        /* Animate the number scaling in */
        const num = item.querySelector(".cap-num");
        if (num) {
            tl2.from(
                num,
                { scale: 0.5, duration: 0.1, ease: "back.out(1.4)" },
                startPos + 0.04,
            );
        }
    });

    /* Spine grows with capabilities */
    if (act2Spine) {
        tl2.to(act2Spine, { scaleY: 1, duration: 0.55, ease: "none" }, 0.1);
    }

    /* Hold, then exit */
    tl2.to({}, { duration: 0.15 }).to(act2.querySelector(".act-content")!, {
        opacity: 0,
        y: -50,
        duration: 0.12,
        ease: "none",
    });
    /* Final act: no fade-out, just release */
}

/* ══════════════════════════════════════════════════════════════════
   MOBILE ANIMATIONS (no pinning)
   Simple scroll-triggered fade-in for each act's content.
   ══════════════════════════════════════════════════════════════════ */

function buildMobileAnimations(section: HTMLElement) {
    const acts = section.querySelectorAll(".act-content");

    acts.forEach((act) => {
        const elements = act.querySelectorAll(
            ".anim-el, .hw, .cap-item, .lead-card, .support-card, .act2-header, .act3-header",
        );

        gsap.from(elements, {
            y: 40,
            opacity: 0,
            duration: 0.8,
            stagger: 0.12,
            ease: "power3.out",
            scrollTrigger: {
                trigger: act,
                start: "top 78%",
                toggleActions: "play none none reverse",
            },
        });
    });
}

/* ══════════════════════════════════════════════════════════════════
   LEAD MEMBER CARD
   Distinct from supporting cards: larger avatar, gold accent pip.
   ══════════════════════════════════════════════════════════════════ */

function LeadMemberCard({ member }: { member: TeamMember }) {
    return (
        <div className="lead-card anim-el shrink-0 md:w-[240px]">
            <div className="relative w-20 h-20 mb-5">
                <div className="w-full h-full rounded-full bg-primary-800 flex items-center justify-center ring-2 ring-secondary-400/20 ring-offset-2 ring-offset-primary-900">
                    <span className="font-display font-bold text-xl text-[#F8FAFC]/90 tracking-tight">
                        {member.initials}
                    </span>
                </div>
                <div className="absolute -bottom-0.5 -right-0.5 w-4 h-4 rounded-full bg-accent-400 border-[2.5px] border-primary-900" />
            </div>

            <h4 className="font-display font-bold text-[#F8FAFC] text-[0.95rem] leading-snug">
                {member.name}
            </h4>
            <span className="mt-1 block text-[0.78rem] font-body text-[#94A3B8] tracking-wide">
                {member.degree}
            </span>

            <div className="mt-4 inline-block px-4 py-1.5 rounded-lg bg-primary-700 text-[#F8FAFC]">
                <span className="text-[0.7rem] font-body font-semibold tracking-[0.06em] uppercase">
                    {member.position}
                </span>
            </div>
        </div>
    );
}

/* ══════════════════════════════════════════════════════════════════
   SUPPORT MEMBER CARD
   Compact inline layout — avatar beside text.
   ══════════════════════════════════════════════════════════════════ */

function SupportMemberCard({ member }: { member: TeamMember }) {
    return (
        <div className="support-card anim-el flex items-start gap-4 py-4 px-4 rounded-xl bg-[#F8FAFC]/3 border border-[#F8FAFC]/5 hover:bg-[#F8FAFC]/5 transition-colors duration-400">
            <div className="w-11 h-11 rounded-full bg-primary-800 flex items-center justify-center shrink-0 ring-1 ring-secondary-400/15">
                <span className="font-display font-semibold text-sm text-[#F8FAFC]/80">
                    {member.initials}
                </span>
            </div>

            <div className="min-w-0">
                <h4 className="font-display font-semibold text-[#F8FAFC]/90 text-[0.85rem] leading-snug truncate">
                    {member.name}
                </h4>
                <span className="text-[0.72rem] font-body text-[#94A3B8] block mt-0.5">
                    {member.degree}
                </span>
                <span className="text-[0.65rem] font-body font-semibold tracking-[0.06em] uppercase text-secondary-400/50 block mt-1.5">
                    {member.position}
                </span>
            </div>
        </div>
    );
}
