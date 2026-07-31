import { Link } from "@inertiajs/react";
import { Package } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Card } from "@/Core/Components/Shared/Card/Card";
import Button from "@/Core/Components/Shared/Button/Button";
import { cn } from "@/Core/Utils/utils";
import { type Service } from "@/Features/Dashboard/Types/product.type";

const DEFAULT_GRADIENT =
    "radial-gradient(ellipse at 50% 40%, rgba(0,130,150,0.75) 0%, rgba(10,61,122,0.92) 55%, #0d1b3e 100%)";

interface ServiceCardProps {
    service: Service;
}

export default function ServiceCard({ service }: ServiceCardProps) {
    const isOutline = service.variant === "outline";

    return (
        <Card className="overflow-hidden flex flex-col h-full card-hover-lift">
            <Box
                className="relative flex items-center justify-center h-44 shrink-0"
                style={{ background: service.imageGradient ?? DEFAULT_GRADIENT }}
            >
                <Box
                    className="w-20 h-20 rounded-2xl border border-white/25 flex items-center justify-center"
                    style={{
                        background:
                            "linear-gradient(145deg, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0.04) 100%)",
                        backdropFilter: "blur(10px)",
                        WebkitBackdropFilter: "blur(10px)",
                    }}
                >
                    {service.iconPath ? (
                        <img
                            src={service.iconPath}
                            alt={service.title}
                            className="h-12 w-12 object-contain"
                        />
                    ) : (
                        <Package className="h-10 w-10 text-white/70" />
                    )}
                </Box>
            </Box>

            <Box className="flex flex-col flex-1 p-4">
                <Heading
                    level={4}
                    className="font-display text-base font-bold text-slate-800 mb-2"
                >
                    {service.title}
                </Heading>

                <Text className="text-sm text-slate-500 leading-relaxed mb-4 flex-1">
                    {service.description ?? service.priceLabel}
                </Text>

                <Link href={service.href} className="block">
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
                        {service.ctaLabel ?? "View Service"}
                    </Button>
                </Link>
            </Box>
        </Card>
    );
}
