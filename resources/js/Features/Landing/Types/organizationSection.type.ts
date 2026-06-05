export interface TeamMember {
    name: string;
    desc: string;
    initials: string;
    image?: string;
    bio?: string;
}

export interface TeamLead {
    display: string[];
    full: string;
    roleId: string;
    roleEn: string;
    desc: string;
    initials: string;
    image: string;
}

export interface TeamSection {
    lead: TeamLead;
    members: TeamMember[];
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
