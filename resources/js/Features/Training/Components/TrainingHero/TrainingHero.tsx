import { Link } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import Button from "@/Core/Components/Shared/Button/Button";
import { type TrainingHero } from "@/Features/Training/Types/course.type";

const glowCta = {
    boxShadow: "0 0 24px rgba(34,211,238,0.4), 0 0 40px rgba(34,211,238,0.3), inset 0 1px 0 rgba(255,255,255,0.3)",
};

interface TrainingHeroProps {
    data: TrainingHero;
}

export default function TrainingHero({ data }: TrainingHeroProps) {
    return (
        <Box
            as="section"
            className="relative overflow-hidden rounded-3xl min-h-60 sm:min-h-70 flex items-center px-6 sm:px-10 py-8 sm:py-12"
            style={{
                backgroundImage: `url(${data.backgroundUrl})`,
                backgroundSize: "cover",
                backgroundPosition: "center",
            }}
        >
            {/* Dark gradient overlay */}
            <Box className="absolute inset-0 bg-linear-to-r from-black/75 via-black/55 to-black/25 z-0 pointer-events-none" />

            {/* Content */}
            <Box className="relative z-10 max-w-lg">
                <Heading
                    level={1}
                    className="font-display text-4xl md:text-5xl font-bold text-white leading-tight mb-4"
                >
                    {data.title}
                </Heading>
                <Text className="text-slate-200 text-base leading-relaxed mb-8 max-w-md">
                    {data.subtitle}
                </Text>
                <Link href={data.ctaHref}>
                    <Button variant="glow" size="md" className="cursor-pointer" style={glowCta}>
                        {data.ctaLabel}
                    </Button>
                </Link>
            </Box>
        </Box>
    );
}
