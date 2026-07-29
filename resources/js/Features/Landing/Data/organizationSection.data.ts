import {
    CollageItem,
    HexItem,
    TeamLead,
    TeamMember,
} from "../Types/organizationSection.type";

export const spineRowH = 100;

/**
 * How many roster rows the pinned desktop window shows at once, and how tall each is.
 *
 * Exported rather than duplicated because both the CSS (viewport height) and the
 * animation hook (step count, and the yPercent per step) need the same number — the
 * track advances by exactly `100 / rowCount` percent of its own height, so a row whose
 * height disagrees with the window drifts out of alignment a little further every step.
 * For the same reason every row is locked to --roster-row at md+.
 */
export const ROSTER_VISIBLE_ROWS = 6;
export const ROSTER_ROW_HEIGHT = "clamp(68px, 8.5vh, 92px)";

export const head: TeamLead = {
    display: ["Djoko", "Kuswanto."],
    full: "Djoko Kuswanto, S.T., M.Biotech.",
    roleId: "Kepala Laboratorium IDIG",
    roleEn: "Head of IDIG Laboratory",
    desc: "Leading the strategic vision and research initiatives at IDIG Health Tech, bridging engineering and medical innovation.",
    initials: "JK",
    image: "https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&q=80&w=400&h=400",
};

/**
 * Bundled fallback for the research roster, used only when the DB ships no team data.
 *
 * `desc` mirrors what LandingPageController now sends — expertise[0], not the role —
 * because every person in this section carries the same role by design and the ledger
 * needs a differentiator. Order matches LabTeamSectionSeeder.
 */
export const researchMembers: TeamMember[] = [
    {
        name: "Muhammad Iqbal Putra Subekti.",
        desc: "Hardware Prototyping",
        initials: "MI",
        image: "https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        name: "Ray Louie D'Angelito.",
        desc: "Clinical Data Science",
        initials: "RA",
        image: "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        name: "Jordan Jonathan Susanto",
        desc: "Biosignal Acquisition",
        initials: "JJ",
        image: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        name: "Varrel Septian Bawole",
        desc: "Embedded Firmware",
        initials: "VS",
        image: "https://images.unsplash.com/photo-1463453091185-61582044d556?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        name: "Agnes Pramesti Veronica",
        desc: "Biomechanical Materials",
        initials: "AV",
        image: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        name: "Talita Dian Anggraini",
        desc: "EMI Testing",
        initials: "TA",
        image: "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        name: "Valinka Nooraisha",
        desc: "Medical Image Segmentation",
        initials: "VN",
        image: "https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        name: "Sheila Rahma Azizah",
        desc: "Clinical NLP",
        initials: "SR",
        image: "https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        name: "Qonita Alifa Fiddars",
        desc: "Interpretable ML",
        initials: "QF",
        image: "https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        name: "Alfa Na'ilah Ciwandan",
        desc: "Bioinformatics",
        initials: "AN",
        image: "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=400&h=400",
    },
    {
        name: "Intan Fitri Hardyanti",
        desc: "Federated Learning",
        initials: "IF",
        image: "https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&q=80&w=400&h=400",
    },
];

export const hexItems: HexItem[] = [
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
];

/**
 * The research roster's collage. One array now serves the merged act and every dynamic
 * act, drawn from the six distinct stills the two old collages held between them.
 *
 * EXACTLY ONE item may set `center: true` — PhotoCollage maps it to `centerClass`, and
 * the animation hook resolves that with a singular querySelector. A second hero would
 * never receive its opening `opacity: 0` and would sit statically visible while
 * everything around it animated in.
 *
 * Slots are tuned for the narrower column the merged act uses
 * (clamp(260px,26vw,400px) wide) — wider percentages here read as clutter, not layers.
 */
export const researchCollage: CollageItem[] = [
    {
        image: "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&q=80&w=600",
        width: "55%",
        aspectRatio: "3/4",
        top: "2%",
        left: "0%",
        rot: -3,
        z: 1,
        shadow: "0 12px 40px rgba(0,66,109,0.15)",
    },
    {
        image: "https://images.unsplash.com/photo-1559757148-5c350d0d3c56?auto=format&fit=crop&q=80&w=600",
        width: "42%",
        aspectRatio: "4/5",
        top: "0%",
        left: "58%",
        rot: 5,
        z: 5,
        shadow: "0 10px 30px rgba(0,66,109,0.1)",
    },
    {
        image: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=800",
        width: "62%",
        aspectRatio: "4/3",
        top: "32%",
        left: "16%",
        rot: 2,
        z: 10,
        shadow: "0 24px 60px rgba(0,66,109,0.3)",
        center: true,
    },
    {
        image: "https://images.unsplash.com/photo-1576086213369-97a306d36557?auto=format&fit=crop&q=80&w=600",
        width: "34%",
        aspectRatio: "1/1",
        top: "26%",
        left: "66%",
        rot: -4,
        z: 6,
        shadow: "0 14px 36px rgba(0,66,109,0.2)",
    },
    {
        image: "https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&q=80&w=600",
        width: "40%",
        aspectRatio: "1/1",
        top: "70%",
        left: "2%",
        rot: -5,
        z: 15,
        shadow: "0 16px 40px rgba(0,66,109,0.25)",
    },
    {
        image: "https://images.unsplash.com/photo-1530497610245-94d3c16cda28?auto=format&fit=crop&q=80&w=600",
        width: "46%",
        aspectRatio: "4/5",
        top: "66%",
        left: "46%",
        rot: 7,
        z: 12,
        shadow: "0 18px 45px rgba(0,66,109,0.25)",
    },
];
