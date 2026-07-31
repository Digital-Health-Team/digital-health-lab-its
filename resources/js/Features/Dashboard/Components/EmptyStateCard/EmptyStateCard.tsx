import { CalendarOff, Database } from "lucide-react";
import { Link } from "@inertiajs/react";
import { Card, CardBody, Button } from "@/Core/Components/Shared";
import { type EmptyStateConfig } from "@/Features/Dashboard/Types/emptyState.type";

interface EmptyStateCardProps {
    config: EmptyStateConfig;
    className?: string;
}

const illustrationMap: Record<EmptyStateConfig["illustrationKey"], React.ReactNode> = {
    "no-events": (
        <div className="relative w-24 h-24 flex items-center justify-center">
            {/* Outer ring */}
            <div className="absolute inset-0 rounded-full bg-gradient-to-br from-primary-800/20 to-secondary-500/15 border border-primary-700/20" />
            {/* Inner circle */}
            <div className="w-16 h-16 rounded-full bg-gradient-to-br from-primary-900/10 via-secondary-500/10 to-primary-800/10 flex items-center justify-center">
                <CalendarOff className="h-8 w-8 text-secondary-500/70" />
            </div>
        </div>
    ),
    "no-data": (
        <div className="relative w-24 h-24 flex items-center justify-center">
            <div className="absolute inset-0 rounded-full bg-gradient-to-br from-primary-800/20 to-secondary-500/15 border border-primary-700/20" />
            <div className="w-16 h-16 rounded-full bg-gradient-to-br from-primary-900/10 via-secondary-500/10 to-primary-800/10 flex items-center justify-center">
                <Database className="h-8 w-8 text-secondary-500/70" />
            </div>
        </div>
    ),
};

export default function EmptyStateCard({ config, className }: EmptyStateCardProps) {
    return (
        <Card className={className}>
            <CardBody className="flex flex-col items-center justify-center min-h-[280px] text-center py-10">
                {/* Illustration */}
                <div className="mb-5">
                    {illustrationMap[config.illustrationKey]}
                </div>
                <h3 className="font-display text-xl font-bold text-slate-800 mb-2">
                    {config.title}
                </h3>
                <p className="text-sm text-slate-500 max-w-[220px] leading-relaxed">
                    {config.body}
                </p>
                {config.ctaLabel && config.ctaHref && (
                    <Link href={config.ctaHref} className="mt-5">
                        <Button variant="ghost" size="sm">
                            {config.ctaLabel}
                        </Button>
                    </Link>
                )}
            </CardBody>
        </Card>
    );
}
