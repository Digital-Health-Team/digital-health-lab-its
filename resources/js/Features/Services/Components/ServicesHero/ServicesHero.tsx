import { Gem } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { type ServicesHero } from "@/Features/Services/Types/service.type";

interface ServicesHeroProps {
    data: ServicesHero;
}

// Pointy-top honeycomb hexagon tile (62×108 px) — tessellates seamlessly.
const HEX_SVG = encodeURIComponent(
    `<svg xmlns="http://www.w3.org/2000/svg" width="62" height="108">` +
    `<polygon points="31,0 62,18 62,54 31,72 0,54 0,18" fill="none" stroke="rgba(255,255,255,0.07)" stroke-width="1.5"/>` +
    `<polygon points="0,54 31,72 31,108 0,126 -31,108 -31,72" fill="none" stroke="rgba(255,255,255,0.07)" stroke-width="1.5"/>` +
    `<polygon points="62,54 93,72 93,108 62,126 31,108 31,72" fill="none" stroke="rgba(255,255,255,0.07)" stroke-width="1.5"/>` +
    `</svg>`,
);

export default function ServicesHero({ data }: ServicesHeroProps) {
    return (
        <Box
            as="section"
            className="relative overflow-hidden rounded-3xl min-h-[280px] p-8 lg:p-12 flex items-center"
            style={{
                background:
                    "linear-gradient(108deg, #031026 0%, #062e5c 28%, #00426d 52%, #006e80 72%, #00a8b5 100%)",
            }}
        >
            {/* ── Hexagonal pattern overlay ──────────────────────────── */}
            <Box
                className="absolute inset-0 pointer-events-none"
                style={{
                    backgroundImage: `url("data:image/svg+xml,${HEX_SVG}")`,
                    backgroundSize: "62px 108px",
                    backgroundRepeat: "repeat",
                }}
            />

            {/* ── Content grid (3 columns) ───────────────────────────── */}
            <Box className="relative z-10 w-full grid grid-cols-12 gap-6 lg:gap-10 items-center">

                {/* LEFT — glassy icon tile + pagination dots */}
                <Box className="col-span-12 lg:col-span-2 flex flex-col items-center lg:items-start gap-5">
                    <Box
                        className="w-24 h-24 rounded-2xl flex items-center justify-center border border-white/20"
                        style={{
                            background:
                                "linear-gradient(145deg, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0.04) 100%)",
                            backdropFilter: "blur(10px)",
                            WebkitBackdropFilter: "blur(10px)",
                        }}
                    >
                        <Gem className="h-10 w-10 text-secondary-300" strokeWidth={1.25} />
                    </Box>

                    {/* Pagination dots */}
                    <Box className="flex items-center gap-2">
                        <Box className="w-5 h-1.5 rounded-full bg-secondary-400" />
                        <Box className="w-1.5 h-1.5 rounded-full bg-white/30" />
                        <Box className="w-1.5 h-1.5 rounded-full bg-white/30" />
                    </Box>
                </Box>

                {/* CENTER — eyebrow + headline + subtitle */}
                <Box className="col-span-12 lg:col-span-7">
                    <Text
                        as="span"
                        className="text-[11px] font-semibold text-secondary-300 tracking-[0.3em] uppercase mb-4 block"
                    >
                        {data.eyebrow}
                    </Text>

                    <Heading
                        level={1}
                        className="font-display text-4xl lg:text-5xl font-bold text-white leading-[1.08] mb-4"
                    >
                        {data.title}
                    </Heading>

                    <Text className="text-sm text-white/65 leading-relaxed max-w-md">
                        {data.subtitle}
                    </Text>
                </Box>

                {/* RIGHT — stacked glass stat cards */}
                <Box className="col-span-12 lg:col-span-3 flex flex-row lg:flex-col gap-4">
                    {data.stats.map((stat, i) => (
                        <Box
                            key={i}
                            className="flex-1 lg:flex-none rounded-xl px-5 py-4 border border-white/15"
                            style={{
                                background: "rgba(255,255,255,0.07)",
                                backdropFilter: "blur(10px)",
                                WebkitBackdropFilter: "blur(10px)",
                            }}
                        >
                            <Heading
                                level={3}
                                className={`font-display text-3xl font-bold leading-none mb-1.5 ${
                                    i === 0
                                        ? "text-secondary-400"
                                        : "text-accent-400"
                                }`}
                            >
                                {stat.value}
                            </Heading>
                            <Text
                                as="span"
                                className="text-xs text-white/60 font-medium"
                            >
                                {stat.label}
                            </Text>
                        </Box>
                    ))}
                </Box>
            </Box>
        </Box>
    );
}
