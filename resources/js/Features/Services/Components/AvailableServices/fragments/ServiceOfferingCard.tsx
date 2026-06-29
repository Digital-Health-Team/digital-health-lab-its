import { Link } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Card } from "@/Core/Components/Shared/Card/Card";
import Button from "@/Core/Components/Shared/Button/Button";
import { cn } from "@/Core/Utils/utils";
import { type ServiceOffering } from "@/Features/Services/Types/service.type";

interface ServiceOfferingCardProps {
    offering: ServiceOffering;
}

export default function ServiceOfferingCard({ offering }: ServiceOfferingCardProps) {
    const isOutline = offering.variant === "outline";

    return (
        <Card className="overflow-hidden flex flex-col h-full card-hover-lift">
            {/* ── Image area — per-card gradient with centered icon ── */}
            <Box
                className="relative flex items-center justify-center h-48 shrink-0"
                style={{ background: offering.imageGradient }}
            >
                <Box
                    className="w-24 h-24 rounded-2xl border border-white/25 flex items-center justify-center"
                    style={{
                        background:
                            "linear-gradient(145deg, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0.04) 100%)",
                        backdropFilter: "blur(10px)",
                        WebkitBackdropFilter: "blur(10px)",
                    }}
                >
                    <img
                        src={offering.iconPath}
                        alt={offering.title}
                        className="h-16 w-16 object-contain"
                    />
                </Box>
            </Box>

            {/* ── Text content area ──────────────────────────────────── */}
            <Box className="flex flex-col flex-1 p-5">
                <Heading
                    level={4}
                    className="font-display text-base font-bold text-slate-800 mb-2"
                >
                    {offering.title}
                </Heading>

                <Text className="text-sm text-slate-500 leading-relaxed mb-5 flex-1">
                    {offering.description}
                </Text>

                {/* CTA — Inertia Link wraps the button for SPA navigation */}
                <Link href={offering.href} className="block">
                    <Button
                        type="button"
                        variant={isOutline ? "outline" : "primary"}
                        size="md"
                        className={cn(
                            "w-full justify-center",
                            isOutline
                                ? "border-slate-300 text-slate-700 bg-white hover:bg-slate-50"
                                : "",
                        )}
                    >
                        {offering.ctaLabel}
                    </Button>
                </Link>
            </Box>
        </Card>
    );
}
