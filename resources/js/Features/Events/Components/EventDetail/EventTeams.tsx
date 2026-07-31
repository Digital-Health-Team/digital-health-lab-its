import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import Avatar from "@/Core/Components/Shared/Avatar/Avatar";
import Badge from "@/Core/Components/Shared/Badge/Badge";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { type EventTeam } from "@/Features/Events/Types/event.type";

interface EventTeamsProps {
    teams: EventTeam[];
}

/**
 * The work is the point of the page, so teams get the full-width treatment rather
 * than a card grid: one row per team, its people, and what they built. Projects
 * are chips rather than links — there is no public project detail route yet.
 */
export default function EventTeams({ teams }: EventTeamsProps) {
    const { t } = useTranslation();

    return (
        <Box
            as="section"
            aria-labelledby="event-teams-heading"
            className="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm sm:p-6"
        >
            <Box className="mb-5 flex flex-wrap items-baseline gap-x-3 gap-y-1">
                <Heading
                    level={2}
                    id="event-teams-heading"
                    className="font-display text-lg font-bold text-slate-800"
                >
                    {t("Participating teams")}
                </Heading>
                {teams.length > 0 && (
                    <Text as="span" className="text-sm text-slate-500 tabular-nums">
                        {teams.length}
                    </Text>
                )}
            </Box>

            {teams.length === 0 ? (
                <Text className="text-sm leading-relaxed text-slate-500">
                    {t("Teams for this edition have not been announced yet.")}
                </Text>
            ) : (
                <Box className="flex flex-col divide-y divide-slate-100">
                    {teams.map((team) => (
                        <Box key={team.id} className="flex flex-col gap-3 py-5 first:pt-0 last:pb-0">
                            {/* Team identity */}
                            <Box className="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                                <Heading
                                    level={3}
                                    className="font-display text-base font-bold text-slate-800"
                                >
                                    {team.name}
                                </Heading>
                                <Text as="span" className="text-xs text-slate-500">
                                    {team.courseName}
                                </Text>
                            </Box>

                            {/* People */}
                            {team.members.length > 0 && (
                                <Box className="flex flex-wrap items-center gap-x-5 gap-y-2">
                                    {team.members.map((member) => (
                                        <Box key={member.id} className="flex items-center gap-2">
                                            <Avatar name={member.name} size="sm" />
                                            <Box className="min-w-0">
                                                <Text
                                                    as="span"
                                                    className="block text-xs leading-tight font-semibold text-slate-700"
                                                >
                                                    {member.name}
                                                </Text>
                                                <Text
                                                    as="span"
                                                    className="block text-[11px] leading-tight text-slate-500"
                                                >
                                                    {member.roleInTeam}
                                                </Text>
                                            </Box>
                                        </Box>
                                    ))}
                                </Box>
                            )}

                            {/* Their work */}
                            {team.projects.length > 0 && (
                                <Box className="flex flex-wrap items-center gap-2">
                                    {team.projects.map((project) => (
                                        <Badge
                                            key={project.id}
                                            variant="tag"
                                            className="max-w-full text-[11px] tracking-normal normal-case"
                                        >
                                            {project.title}
                                        </Badge>
                                    ))}
                                </Box>
                            )}
                        </Box>
                    ))}
                </Box>
            )}
        </Box>
    );
}
