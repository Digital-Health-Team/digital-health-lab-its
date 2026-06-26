import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { cn } from "@/Core/Utils/utils";
import { type ProjectsHero } from "@/Features/Projects/Types/project.type";

interface ProjectsHeroProps {
    data: ProjectsHero;
}

export default function ProjectsHero({ data }: ProjectsHeroProps) {
    const handImage = data.images[0];
    const whiteboardImage = data.images[1];

    return (
        <Box
            as="section"
            className="relative overflow-hidden rounded-3xl bg-[#eaeef9] min-h-[500px] p-8 lg:p-10"
        >
            {/* ── Background layers ─────────────────────────────────── */}

            {/* Full-height navy panel covering the right ~57% */}
            <Box className="absolute top-0 right-0 h-full w-[57%] bg-primary-900 pointer-events-none" />

            {/* Cyan accent tab — far-left edge */}
            <Box className="absolute left-0 top-[30%] h-20 w-[6px] bg-secondary-400 rounded-r-full pointer-events-none" />

            {/* ── Content grid ──────────────────────────────────────── */}
            <Box className="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 h-full">

                {/* LEFT ─ eyebrow + hand image + heading + body */}
                <Box className="lg:col-span-5 flex flex-row gap-3">
                    {/* Vertical eyebrow label */}
                    <Box className="flex items-start justify-center w-5 shrink-0 pt-1">
                        <Text
                            as="span"
                            className="[writing-mode:vertical-rl] rotate-180 text-[11px] italic font-medium text-slate-500 tracking-[0.2em] whitespace-nowrap select-none"
                        >
                            {data.eyebrow}
                        </Text>
                    </Box>

                    {/* Content column */}
                    <Box className="flex-1 min-w-0">
                        {/* Small featured image (e.g. 3D prosthetic hand) */}
                        <Box className="mb-5 h-48 w-[65%] rounded-2xl overflow-hidden shadow-card-soft">
                            {handImage?.src ? (
                                <Box
                                    className="w-full h-full"
                                    style={{
                                        backgroundImage: `url(${handImage.src})`,
                                        backgroundSize: "cover",
                                        backgroundPosition: "center",
                                    }}
                                />
                            ) : (
                                <Box
                                    className={cn(
                                        "w-full h-full",
                                        handImage?.colorClass ??
                                            "bg-gradient-to-br from-slate-300 to-slate-500",
                                    )}
                                />
                            )}
                        </Box>

                        {/* Title */}
                        <Heading
                            level={1}
                            className="font-display text-5xl font-bold text-slate-900 leading-[1.05] mb-3"
                        >
                            {data.title}
                        </Heading>

                        {/* Bold subtitle */}
                        <Text className="font-semibold text-slate-800 text-sm mb-4">
                            {data.subtitle}
                        </Text>

                        {/* Two-column body paragraphs */}
                        <Box className="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2">
                            {data.body.map((paragraph, i) => (
                                <Text
                                    key={i}
                                    className="text-xs text-slate-500 leading-relaxed"
                                >
                                    {paragraph}
                                </Text>
                            ))}
                        </Box>
                    </Box>
                </Box>

                {/* RIGHT ─ large image + navy accent block */}
                <Box className="lg:col-span-7 flex flex-col gap-4 pl-8 lg:pl-10">
                    {/* Main whiteboard / showcase image */}
                    <Box className="flex-1 min-h-80 rounded-2xl overflow-hidden shadow-card-elevated">
                        {whiteboardImage?.src ? (
                            <Box
                                className="w-full h-full"
                                style={{
                                    backgroundImage: `url(${whiteboardImage.src})`,
                                    backgroundSize: "cover",
                                    backgroundPosition: "center",
                                }}
                            />
                        ) : (
                            <Box
                                className={cn(
                                    "w-full h-full",
                                    whiteboardImage?.colorClass ??
                                        "bg-gradient-to-br from-slate-500 to-slate-700",
                                )}
                            />
                        )}
                    </Box>

                    {/* Navy accent block below the image */}
                    <Box className="h-12 w-full rounded-xl bg-primary-800 shrink-0" />
                </Box>
            </Box>
        </Box>
    );
}
