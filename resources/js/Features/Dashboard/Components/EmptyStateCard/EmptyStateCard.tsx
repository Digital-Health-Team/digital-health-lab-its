import { CalendarOff } from "lucide-react";
import { Link } from "@inertiajs/react";
import { Card, CardBody, Button } from "@/Core/Components/Shared";
import { type EmptyStateConfig } from "@/Features/Dashboard/Types/emptyState.type";

interface EmptyStateCardProps {
    config: EmptyStateConfig;
    className?: string;
}

export default function EmptyStateCard({ config, className }: EmptyStateCardProps) {
    return (
        <Card className={className}>
            <CardBody className="flex flex-col items-center justify-center min-h-[280px] text-center py-8">
                {/* Illustration */}
                <div className="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-5">
                    <CalendarOff className="h-9 w-9 text-slate-400" />
                </div>
                <h3 className="font-display text-xl font-bold text-slate-800 mb-2">
                    {config.title}
                </h3>
                <p className="text-sm text-slate-500 max-w-[200px] leading-relaxed">
                    {config.body}
                </p>
                {config.ctaLabel && config.ctaHref && (
                    <Link href={config.ctaHref} className="mt-4">
                        <Button variant="ghost" size="sm">
                            {config.ctaLabel}
                        </Button>
                    </Link>
                )}
            </CardBody>
        </Card>
    );
}
