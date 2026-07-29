import { useRef } from "react";
import { Head, Link, usePage } from "@inertiajs/react";
import { ChevronRight, Mail } from "lucide-react";
import MainLayout from "@/Features/Landing/Layouts/MainLayout";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Image } from "@/Core/Components/Common/Image";
import { Text } from "@/Core/Components/Common/Text";
import Badge from "@/Core/Components/Shared/Badge/Badge";
import { useTeamMemberShowAnimation } from "@/Features/Team/Hooks/useTeamMemberShowAnimation";
import { type TeamMemberProfile, type TeammateLink } from "@/Features/Team/Types/teamMember.type";

interface TeamMemberShowProps {
    member: TeamMemberProfile;
    teammates: TeammateLink[];
    [key: string]: unknown;
}

const contactLinkClass =
    "w-10 h-10 rounded-full bg-white/10 border border-white/15 flex items-center justify-center text-white/70 hover:text-white hover:bg-white/20 transition-all duration-200";

// lucide-react doesn't ship brand icons (trademark policy) — same reason
// LandingFooter's SocialLinks hand-rolls these; paths mirrored from there
// for a consistent mark across the site.
function LinkedinIcon() {
    return (
        <svg viewBox="0 0 24 24" fill="currentColor" className="w-4 h-4" aria-hidden="true">
            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
        </svg>
    );
}

function InstagramIcon() {
    return (
        <svg viewBox="0 0 24 24" fill="currentColor" className="w-4 h-4" aria-hidden="true">
            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z" />
        </svg>
    );
}

export default function TeamMemberShow() {
    const { member, teammates } = usePage<TeamMemberShowProps>().props;
    const containerRef = useRef<HTMLElement>(null);
    useTeamMemberShowAnimation(containerRef);

    const hasContact = Boolean(member.email || member.linkedin || member.instagram);

    return (
        <>
            <Head title={`${member.name} — IDIG Health Tech`} />
            <MainLayout>
                <Box as="article" ref={containerRef} className="relative">
                    {/* Masthead — navy, doubles as the fixed navbar's native dark backdrop */}
                    <Box
                        as="header"
                        className="relative bg-primary-950 pt-28 md:pt-36 pb-20 md:pb-28 px-6 md:px-12 overflow-hidden"
                    >
                        <Box
                            aria-hidden="true"
                            className="absolute inset-0 honeycomb-dark opacity-[0.05] pointer-events-none"
                        />

                        <Box className="relative z-10 max-w-3xl mx-auto">
                            <Box className="team-show-masthead flex flex-col items-start">
                                <Box className="flex items-center gap-1.5 flex-wrap mb-8">
                                    <Link
                                        href="/"
                                        className="text-xs font-body text-white/60 hover:text-secondary-400 transition-colors"
                                    >
                                        Beranda
                                    </Link>
                                    <ChevronRight className="h-3 w-3 text-white/30 shrink-0" aria-hidden="true" />
                                    <Link
                                        href="/#org"
                                        className="text-xs font-body text-white/60 hover:text-secondary-400 transition-colors"
                                    >
                                        Struktur Organisasi
                                    </Link>
                                    <ChevronRight className="h-3 w-3 text-white/30 shrink-0" aria-hidden="true" />
                                    <Text as="span" className="text-xs font-body text-white/40 line-clamp-1 max-w-[220px]">
                                        {member.name}
                                    </Text>
                                </Box>

                                <Badge
                                    variant="tag"
                                    className="bg-secondary-400/10 text-secondary-300 border-secondary-400/25 mb-5"
                                >
                                    {member.sectionLabelId}
                                </Badge>

                                <Box className="team-show-identity flex flex-row items-center gap-4 md:gap-5">
                                    <Box className="team-show-avatar relative w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-full bg-primary-700/[0.15] border border-secondary-400/30 flex items-center justify-center overflow-hidden shrink-0 shadow-glow-cyan">
                                        {member.photo ? (
                                            <Image
                                                src={member.photo}
                                                alt={member.name}
                                                className="absolute inset-0 w-full h-full"
                                                objectFit="cover"
                                                priority="eager"
                                            />
                                        ) : (
                                            <Text
                                                as="span"
                                                className="font-display font-extrabold italic text-xl sm:text-2xl text-secondary-200"
                                            >
                                                {member.initials}
                                            </Text>
                                        )}
                                    </Box>

                                    <Heading
                                        level={1}
                                        className="min-w-0 font-display font-extrabold italic text-3xl sm:text-4xl md:text-5xl leading-[1.05] tracking-tight text-white text-balance"
                                    >
                                        {member.name}
                                    </Heading>
                                </Box>

                                <Text
                                    as="span"
                                    className="font-body font-semibold tracking-[0.04em] text-secondary-400 mt-4"
                                    style={{ fontSize: "clamp(1rem, 1.35vw, 1.15rem)" }}
                                >
                                    {member.roleId} · {member.roleEn}
                                </Text>
                                <Box className="team-show-hairline h-px w-16 bg-secondary-400/50 mt-3 origin-left" />

                                {hasContact && (
                                    <Box className="team-show-contact flex items-center gap-3 mt-6">
                                        {member.email && (
                                            <a
                                                href={`mailto:${member.email}`}
                                                className={contactLinkClass}
                                                aria-label={`Email ${member.name}`}
                                            >
                                                <Mail className="w-4 h-4" aria-hidden="true" />
                                            </a>
                                        )}
                                        {member.linkedin && (
                                            <a
                                                href={member.linkedin}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className={contactLinkClass}
                                                aria-label={`LinkedIn ${member.name}`}
                                            >
                                                <LinkedinIcon />
                                            </a>
                                        )}
                                        {member.instagram && (
                                            <a
                                                href={member.instagram}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className={contactLinkClass}
                                                aria-label={`Instagram ${member.name}`}
                                            >
                                                <InstagramIcon />
                                            </a>
                                        )}
                                    </Box>
                                )}
                            </Box>
                        </Box>
                    </Box>

                    {/* Light reading body */}
                    <Box className="relative bg-surface-base pt-16 md:pt-20 pb-24 md:pb-32 px-6 md:px-12">
                        <Box className="team-show-body max-w-[68ch] mx-auto flex flex-col gap-5">
                            {member.bio ? (
                                member.bio.split("\n\n").map((paragraph, i) => (
                                    <Text
                                        key={i}
                                        className={
                                            i === 0
                                                ? "text-lg md:text-xl font-body font-medium text-primary-950 leading-relaxed text-pretty"
                                                : "text-base font-body text-primary-900/80 leading-relaxed text-pretty"
                                        }
                                    >
                                        {paragraph}
                                    </Text>
                                ))
                            ) : (
                                <Text className="text-base font-body text-primary-900/60 italic">
                                    Belum ada deskripsi untuk anggota ini.
                                </Text>
                            )}

                            {member.expertise.length > 0 && (
                                <Box className="team-show-expertise mt-4">
                                    <Box className="flex items-center gap-3 mb-4">
                                        <Box className="w-8 h-px bg-secondary-500/60" />
                                        <Text
                                            as="span"
                                            className="text-[0.68rem] font-body font-semibold tracking-[0.3em] uppercase text-primary-700/80"
                                        >
                                            Keahlian
                                        </Text>
                                    </Box>
                                    <Box className="flex flex-wrap gap-2">
                                        {member.expertise.map((tag) => (
                                            <Text
                                                key={tag}
                                                as="span"
                                                className="team-show-expertise-pill inline-flex items-center px-3 py-1.5 rounded-full text-xs font-body font-medium text-primary-800 bg-primary-700/[0.06] border border-primary-700/15"
                                            >
                                                {tag}
                                            </Text>
                                        ))}
                                    </Box>
                                </Box>
                            )}

                            {member.education.length > 0 && (
                                <Box className="team-show-education mt-4">
                                    <Box className="flex items-center gap-3 mb-4">
                                        <Box className="w-8 h-px bg-secondary-500/60" />
                                        <Text
                                            as="span"
                                            className="text-[0.68rem] font-body font-semibold tracking-[0.3em] uppercase text-primary-700/80"
                                        >
                                            Pendidikan
                                        </Text>
                                    </Box>
                                    <Box className="flex flex-col gap-2">
                                        {member.education.map((entry, i) => (
                                            <Text
                                                key={i}
                                                className="team-show-education-item text-sm font-body text-primary-900/80 leading-relaxed"
                                            >
                                                <Text as="span" aria-hidden="true" className="text-secondary-500 mr-2">
                                                    &mdash;
                                                </Text>
                                                {entry}
                                            </Text>
                                        ))}
                                    </Box>
                                </Box>
                            )}

                            {/* Org-chart assignments — identity, then org, then work.
                                A distinct pill class from team-show-expertise-pill on
                                purpose: that one's ScrollTrigger is anchored to
                                .team-show-expertise, which doesn't exist for members who
                                have no expertise, leaving the pills stuck invisible. */}
                            {(member.units.length > 0 ||
                                member.departments.length > 0) && (
                                <Box className="team-show-org mt-4 flex flex-col gap-4">
                                    {[
                                        { label: "Unit", items: member.units },
                                        { label: "Departemen", items: member.departments },
                                    ]
                                        .filter((group) => group.items.length > 0)
                                        .map((group) => (
                                            <Box key={group.label}>
                                                <Box className="flex items-center gap-3 mb-3">
                                                    <Box className="w-8 h-px bg-secondary-500/60" />
                                                    <Text
                                                        as="span"
                                                        className="text-[0.68rem] font-body font-semibold tracking-[0.3em] uppercase text-primary-700/80"
                                                    >
                                                        {group.label}
                                                    </Text>
                                                </Box>
                                                <Box className="flex flex-wrap gap-2">
                                                    {group.items.map((item) => (
                                                        <Text
                                                            key={item}
                                                            as="span"
                                                            className="team-show-org-pill inline-flex items-center px-3 py-1.5 rounded-full text-xs font-body font-medium text-primary-800 bg-primary-700/[0.06] border border-primary-700/15"
                                                        >
                                                            {item}
                                                        </Text>
                                                    ))}
                                                </Box>
                                            </Box>
                                        ))}
                                </Box>
                            )}

                            {member.pic.length > 0 && (
                                <Box className="team-show-pic mt-4">
                                    <Box className="flex items-center gap-3 mb-4">
                                        <Box className="w-8 h-px bg-secondary-500/60" />
                                        <Text
                                            as="span"
                                            className="text-[0.68rem] font-body font-semibold tracking-[0.3em] uppercase text-primary-700/80"
                                        >
                                            Penanggung Jawab
                                        </Text>
                                    </Box>
                                    <Box className="flex flex-col gap-2">
                                        {member.pic.map((entry) => (
                                            <Text
                                                key={entry}
                                                className="team-show-pic-item text-sm font-body text-primary-900/80 leading-relaxed"
                                            >
                                                <Text
                                                    as="span"
                                                    aria-hidden="true"
                                                    className="text-secondary-500 mr-2"
                                                >
                                                    &mdash;
                                                </Text>
                                                {entry}
                                            </Text>
                                        ))}
                                    </Box>
                                </Box>
                            )}

                            {member.completedProjects.length > 0 && (
                                <Box className="team-show-projects mt-8">
                                    <Box className="flex items-center gap-3 mb-2">
                                        <Box className="w-8 h-px bg-secondary-500/60" />
                                        <Text
                                            as="span"
                                            className="text-[0.68rem] font-body font-semibold tracking-[0.3em] uppercase text-primary-700/80"
                                        >
                                            Proyek Selesai
                                        </Text>
                                    </Box>
                                    <Box className="flex flex-col">
                                        {member.completedProjects.map((project, i) => (
                                            <Box
                                                key={i}
                                                className={`team-show-project-item py-4 ${i > 0 ? "border-t border-primary-900/10" : ""}`}
                                            >
                                                <Text className="font-display font-semibold text-primary-950 text-base">
                                                    {project.title}
                                                </Text>
                                                <Text className="text-sm font-body text-primary-900/70 leading-relaxed mt-1 line-clamp-2">
                                                    {project.description}
                                                </Text>
                                                {project.url && (
                                                    <a
                                                        href={project.url}
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        className="inline-flex items-center gap-1 mt-2 text-xs font-body font-semibold text-primary-700 hover:underline"
                                                    >
                                                        Lihat proyek
                                                        <span aria-hidden="true">&#8599;</span>
                                                    </a>
                                                )}
                                            </Box>
                                        ))}
                                    </Box>
                                </Box>
                            )}

                            <Link
                                href="/#org"
                                className="group inline-flex items-center gap-2 mt-8 font-body font-semibold text-sm text-primary-700 underline-offset-4 hover:underline w-fit focus-visible:outline-2 focus-visible:outline-secondary-500 focus-visible:outline-offset-4 rounded-sm"
                            >
                                <Text
                                    as="span"
                                    aria-hidden="true"
                                    className="transition-transform duration-300 group-hover:-translate-x-1"
                                >
                                    &larr;
                                </Text>
                                Kembali ke Struktur Organisasi
                            </Link>
                        </Box>

                        {teammates.length > 0 && (
                            <Box className="team-show-related max-w-6xl mx-auto mt-20 md:mt-28 pt-16 border-t border-primary-900/10">
                                <Box className="flex items-center gap-3 mb-8">
                                    <Box className="w-8 h-px bg-secondary-500/60" />
                                    <Text
                                        as="span"
                                        className="text-[0.68rem] font-body font-semibold tracking-[0.3em] uppercase text-primary-700/80"
                                    >
                                        Rekan Satu Tim
                                    </Text>
                                </Box>
                                <Box
                                    className="grid gap-x-5 gap-y-6"
                                    style={{ gridTemplateColumns: "repeat(auto-fit, minmax(240px, 1fr))" }}
                                >
                                    {teammates.map((teammate) => (
                                        <Link
                                            key={teammate.href}
                                            href={teammate.href}
                                            className="team-show-teammate-card group flex items-center gap-4 p-4 rounded-2xl border border-primary-900/10 hover:border-secondary-400/40 hover:bg-primary-700/[0.03] transition-colors duration-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary-500/50"
                                        >
                                            <Box className="w-12 h-12 rounded-full bg-primary-700/[0.07] border border-primary-700/10 flex items-center justify-center overflow-hidden shrink-0 relative">
                                                {teammate.photo ? (
                                                    <Image
                                                        src={teammate.photo}
                                                        alt={teammate.name}
                                                        className="absolute inset-0 w-full h-full"
                                                        objectFit="cover"
                                                    />
                                                ) : (
                                                    <Text
                                                        as="span"
                                                        className="font-display font-extrabold italic text-sm text-primary-900/70"
                                                    >
                                                        {teammate.initials}
                                                    </Text>
                                                )}
                                            </Box>
                                            <Box className="min-w-0">
                                                <Text className="font-body font-semibold text-sm text-primary-900 truncate">
                                                    {teammate.name}
                                                </Text>
                                                <Text
                                                    as="span"
                                                    className="text-xs font-body text-primary-700/60 uppercase tracking-wide truncate block"
                                                >
                                                    {teammate.roleId}
                                                </Text>
                                            </Box>
                                        </Link>
                                    ))}
                                </Box>
                            </Box>
                        )}
                    </Box>
                </Box>
            </MainLayout>
        </>
    );
}
