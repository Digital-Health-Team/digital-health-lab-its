import { CalendarDays, Users } from "lucide-react";
import { Card, CardBody, Badge } from "@/Core/Components/Shared";
import { type ActiveEvent } from "@/Features/Dashboard/Types/event.type";
import { useTranslation } from "@/Core/Hooks/useTranslation";

interface OngoingEventCardProps {
    event: ActiveEvent;
}

export default function OngoingEventCard({ event }: OngoingEventCardProps) {
    const { t } = useTranslation();

    return (
        <Card className="overflow-hidden h-full">
            {/* Header band */}
            <div
                className="relative px-6 py-6 overflow-hidden"
                style={{
                    background: "linear-gradient(135deg, #031026 0%, #062E5C 60%, #0A3D7A 100%)",
                }}
            >
                {/* ECG decoration */}
                <svg
                    aria-hidden="true"
                    className="absolute inset-0 w-full h-full pointer-events-none opacity-10"
                    preserveAspectRatio="none"
                >
                    <polyline
                        points="0,40 30,40 40,15 50,65 60,30 70,40 100,40 110,18 120,40 150,40"
                        fill="none"
                        stroke="#22D3EE"
                        strokeWidth="1.5"
                    />
                </svg>

                <p className="relative text-secondary-400 text-[11px] font-semibold uppercase tracking-widest mb-2">
                    {t("Ongoing Event")}
                </p>
                <div className="relative flex items-start gap-2 mb-1">
                    <CalendarDays className="h-4 w-4 text-secondary-400 mt-1 shrink-0" />
                    <h3 className="font-display text-lg font-bold text-white leading-snug">
                        {event.name}
                    </h3>
                </div>
                <Badge variant="tag" className="relative mt-1">
                    {event.year}
                </Badge>
            </div>

            {/* Body */}
            <CardBody className="flex flex-col gap-4 py-5">
                <div>
                    <p className="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">
                        {t("Theme")}
                    </p>
                    <p className="text-sm text-slate-700 leading-relaxed font-medium">
                        {event.themeTitle}
                    </p>
                </div>

                <div className="flex items-center gap-2 pt-1 border-t border-slate-100">
                    <Users className="h-4 w-4 text-slate-400" />
                    <span className="text-sm text-slate-600">
                        <span className="font-bold text-slate-800">{event.teamsCount}</span>{" "}
                        {t("participating teams")}
                    </span>
                </div>
            </CardBody>
        </Card>
    );
}
