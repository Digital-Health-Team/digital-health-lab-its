import { Link } from "@inertiajs/react";
import { Pencil, ArrowDownToLine } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { cn } from "@/Core/Utils/utils";
import { type ProjectCard } from "@/Features/Projects/Types/project.type";

interface ProjectCardProps {
    card: ProjectCard;
}

export default function ProjectCard({ card }: ProjectCardProps) {
    return (
        <Link href={card.href} className="block group">
            {/* ── Fluid image (bento card — no white border box) ── */}
            <Box
                className="relative w-full h-56 rounded-2xl overflow-hidden shadow-card-soft card-hover-lift"
            >
                {/* Thumbnail */}
                {card.thumbnailUrl ? (
                    <Box
                        className="absolute inset-0"
                        style={{
                            backgroundImage: `url(${card.thumbnailUrl})`,
                            backgroundSize: "cover",
                            backgroundPosition: "center",
                        }}
                    />
                ) : (
                    <Box
                        className={cn(
                            "absolute inset-0",
                            card.thumbnailColor ??
                                "bg-gradient-to-br from-slate-200 to-slate-400",
                        )}
                    />
                )}

                {/* Two stacked action icon buttons — top-left */}
                <Box
                    className="absolute top-2.5 left-2.5 z-10 flex flex-col gap-1"
                    onClick={(e: React.MouseEvent) => e.preventDefault()}
                >
                    <Box className="h-6 w-6 rounded-md bg-primary-700 flex items-center justify-center shadow-sm">
                        <Pencil className="h-3 w-3 text-white" />
                    </Box>
                    <Box className="h-6 w-6 rounded-md bg-primary-700 flex items-center justify-center shadow-sm">
                        <ArrowDownToLine className="h-3 w-3 text-white" />
                    </Box>
                </Box>
            </Box>

            {/* ── Caption below the image ── */}
            <Text className="text-xs text-slate-500 leading-snug line-clamp-2 mt-2 pl-0.5">
                {card.caption}
            </Text>
        </Link>
    );
}
