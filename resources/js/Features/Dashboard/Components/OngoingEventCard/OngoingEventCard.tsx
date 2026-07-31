import { CalendarDays, Users } from "lucide-react";
import { Card, CardBody, Badge } from "@/Core/Components/Shared";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { Heading } from "@/Core/Components/Common/Heading";
import EmptyStateCard from "@/Features/Dashboard/Components/EmptyStateCard/EmptyStateCard";
import { type ActiveEvent } from "@/Features/Dashboard/Types/event.type";
import { type EmptyStateConfig } from "@/Features/Dashboard/Types/emptyState.type";
import { useTranslation } from "@/Core/Hooks/useTranslation";

interface OngoingEventCardProps {
    event: ActiveEvent | null;
    emptyConfig: EmptyStateConfig;
}

/** Faint hexagonal honeycomb pattern — molecular texture, on-system (see DESIGN.md §5 Org Chart). */
function HoneycombTexture() {
    return (
        <svg
            aria-hidden="true"
            className="absolute inset-0 w-full h-full pointer-events-none"
            style={{ opacity: 0.07 }}
            xmlns="http://www.w3.org/2000/svg"
        >
            <defs>
                <pattern
                    id="hex-event"
                    x="0"
                    y="0"
                    width="40"
                    height="46"
                    patternUnits="userSpaceOnUse"
                >
                    {/* Two rows of hexagons to tile seamlessly */}
                    <polygon
                        points="20,2 38,12 38,34 20,44 2,34 2,12"
                        fill="none"
                        stroke="#22D3EE"
                        strokeWidth="1"
                    />
                    <polygon
                        points="40,25 58,35 58,57 40,67 22,57 22,35"
                        fill="none"
                        stroke="#22D3EE"
                        strokeWidth="1"
                    />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#hex-event)" />
        </svg>
    );
}

export default function OngoingEventCard({ event, emptyConfig }: OngoingEventCardProps) {
    const { t } = useTranslation();

    return (
        <Box
            as="section"
            aria-labelledby="ongoing-event-heading"
            className="flex flex-col h-full gap-3"
        >
            {/* ── Section header (on light dashboard bg) ── */}
            <Box className="shrink-0 flex items-center justify-between">
                <Box>
                    <Heading
                        level={2}
                        id="ongoing-event-heading"
                        className="font-display text-xl font-bold text-slate-800 leading-tight"
                    >
                        {t("On Going Event")}
                    </Heading>
                    <Box className="flex items-center gap-1.5 mt-0.5">
                        <Box
                            className="w-1.5 h-1.5 rounded-full bg-[#00A8B5]"
                            aria-hidden="true"
                        />
                        <Text
                            as="span"
                            className="text-xs font-medium text-slate-500 tracking-wide"
                        >
                            {t("Happening this month")}
                        </Text>
                    </Box>
                </Box>
            </Box>

            {/* ── Card body ── */}
            {event ? (
                <Card
                    className="flex-1 overflow-hidden group"
                    style={{
                        background:
                            "linear-gradient(145deg, #031026 0%, #062E5C 55%, #0A3D7A 100%)",
                        border: "1px solid rgba(34,211,238,0.12)",
                        transition:
                            "transform 220ms cubic-bezier(0.25,1,0.5,1), box-shadow 220ms cubic-bezier(0.25,1,0.5,1)",
                    }}
                >
                    {/* CSS-only hover lift — no JS needed */}
                    <style>{`
                        @media (prefers-reduced-motion: no-preference) {
                            .event-card-hover:hover {
                                transform: translateY(-2px);
                                box-shadow: 0 20px 60px -15px rgba(3,16,38,0.55);
                            }
                        }
                        @keyframes live-ping {
                            0%, 100% { opacity: 1; transform: scale(1); }
                            50%       { opacity: 0.4; transform: scale(1.4); }
                        }
                        @media (prefers-reduced-motion: no-preference) {
                            .live-ping { animation: live-ping 2s ease-in-out infinite; }
                        }
                    `}</style>

                    <Box className="event-card-hover h-full relative overflow-hidden">
                        {/* Honeycomb texture */}
                        <HoneycombTexture />

                        <CardBody className="relative z-10 flex flex-col justify-between h-full py-6 px-6 gap-6">
                            {/* ── Top: LIVE signal + year badge ── */}
                            <Box className="flex items-center justify-between">
                                <Box className="flex items-center gap-2">
                                    {/* LIVE dot */}
                                    <Box className="relative flex items-center justify-center w-4 h-4">
                                        <Box
                                            className="live-ping absolute w-3 h-3 rounded-full bg-[#00A8B5]/40"
                                            aria-hidden="true"
                                        />
                                        <Box
                                            className="w-2 h-2 rounded-full bg-[#22D3EE]"
                                            aria-hidden="true"
                                        />
                                    </Box>
                                    <Text
                                        as="span"
                                        className="text-[10px] font-bold text-[#22D3EE] uppercase tracking-[0.12em]"
                                    >
                                        {t("LIVE")}
                                    </Text>
                                </Box>

                                {/* Year badge */}
                                <Badge
                                    className="text-[11px] font-semibold text-white/90 border-white/20"
                                    style={{
                                        background: "rgba(255,255,255,0.10)",
                                        backdropFilter: "blur(6px)",
                                    }}
                                >
                                    {event.year}
                                </Badge>
                            </Box>

                            {/* ── Middle: icon tile + event name + theme ── */}
                            <Box className="flex items-start gap-4">
                                {/* Icon tile */}
                                <Box
                                    className="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center"
                                    style={{ background: "rgba(255,255,255,0.10)" }}
                                >
                                    <CalendarDays
                                        className="h-5 w-5 text-[#22D3EE]"
                                        aria-hidden="true"
                                    />
                                </Box>

                                {/* Name + theme */}
                                <Box className="min-w-0">
                                    <Heading
                                        level={3}
                                        className="font-display text-lg font-bold text-white leading-snug mb-1.5"
                                    >
                                        {event.name}
                                    </Heading>
                                    <Text
                                        as="p"
                                        className="text-sm leading-relaxed line-clamp-2"
                                        style={{ color: "rgba(167,243,252,0.80)" }}
                                    >
                                        {event.themeTitle}
                                    </Text>
                                </Box>
                            </Box>

                            {/* ── Footer: team count ── */}
                            <Box
                                className="flex items-center gap-2 pt-4"
                                style={{ borderTop: "1px solid rgba(255,255,255,0.10)" }}
                            >
                                <Users
                                    className="h-4 w-4 shrink-0 text-[#00A8B5]"
                                    aria-hidden="true"
                                />
                                <Text as="span" className="text-sm text-white/70">
                                    <Text
                                        as="span"
                                        className="font-bold text-white"
                                    >
                                        {event.teamsCount}
                                    </Text>{" "}
                                    {t("participating teams")}
                                </Text>
                            </Box>
                        </CardBody>
                    </Box>
                </Card>
            ) : (
                <EmptyStateCard config={emptyConfig} className="flex-1" />
            )}
        </Box>
    );
}
