export interface TeamMember {
    /** Short name — what the roster row shows. */
    name: string;
    /** Full name, for the row's native title tooltip only. */
    fullName?: string;
    /** Org-chart code (IQB, RAY, …); doubles as the avatar fallback glyph. */
    initials: string;
    units?: string[];
    departments?: string[];
    /** Set only when it differs from the section's default role — leads, in practice. */
    role?: string | null;
    /** Second-line fallback for CMS sections with no units or departments. */
    desc: string;
    image?: string;
    href?: string;
}

export interface TeamLead {
    display: string[];
    full: string;
    roleId: string;
    roleEn: string;
    desc: string;
    initials: string;
    image: string;
    href?: string;
}

export interface HexItem {
    cx: number;
    cy: number;
    size: number;
    center: boolean;
    rot: number;
    label: string;
    image: string;
}

export interface CollageItem {
    image: string;
    width: string;
    aspectRatio: string;
    top: string;
    left: string;
    rot: number;
    z: number;
    shadow: string;
    center?: boolean;
}
