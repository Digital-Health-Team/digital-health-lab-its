export interface TeamMemberProfile {
    name: string;
    display: string[];
    roleId: string;
    roleEn: string;
    bio: string | null;
    initials: string;
    photo: string | null;
    email: string | null;
    linkedin: string | null;
    instagram: string | null;
    expertise: string[];
    completedProjects: CompletedProject[];
    education: string[];
    /** Org-chart assignments. Always arrays — TeamMemberController coalesces nulls. */
    units: string[];
    departments: string[];
    /** Hospitals and clinicians this person is the point of contact for. */
    pic: string[];
    isLeader: boolean;
    sectionLabelId: string;
    sectionLabelEn: string;
}

export interface CompletedProject {
    title: string;
    description: string;
    url: string | null;
}

export interface TeammateLink {
    name: string;
    roleId: string;
    initials: string;
    photo: string | null;
    href: string;
}
