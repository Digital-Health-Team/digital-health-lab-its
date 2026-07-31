import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Image } from "@/Core/Components/Common/Image";
import { Badge } from "@/Core/Components/Shared";
import { CalendarDays, Tag } from "lucide-react";
import { type ProjectDetail } from "@/Features/Projects/Types/projectDetail.type";
import { cn } from "@/Core/Utils/utils";

interface ProjectPreviewProps {
    project: Pick<
        ProjectDetail,
        "title" | "category" | "coverUrl" | "coverColor" | "specs"
    >;
}

export default function ProjectPreview({ project }: ProjectPreviewProps) {
    const updatedSpec = project.specs.find((s) => s.label === "Last Updated");
    const versionSpec = project.specs.find((s) => s.label === "Version");

    return (
        <Box className="rounded-2xl overflow-hidden border border-slate-100 bg-white shadow-sm">
            {/* Cover image / color */}
            <Box className="relative h-40 sm:h-60 md:h-100 lg:h-120 bg-slate-100">
                {project.coverUrl ? (
                    <Image
                        src={project.coverUrl}
                        alt={project.title}
                        className="w-full h-full object-cover"
                    />
                ) : (
                    <Box
                        className={cn(
                            "absolute inset-0",
                            project.coverColor ??
                                "bg-linear-to-br from-slate-200 to-slate-400",
                        )}
                    />
                )}

                {/* Category badge */}
                <Box className="absolute top-3 left-3">
                    <Badge className="bg-primary-700/90 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full">
                        {project.category}
                    </Badge>
                </Box>
            </Box>

            {/* Info block */}
            <Box className="p-5 space-y-3">
                <Heading level={2} className="text-xl font-bold text-slate-900 leading-snug">
                    {project.title}
                </Heading>

                {/* Meta row */}
                <Box className="flex flex-wrap gap-4 pt-1">
                    {updatedSpec && (
                        <Box className="flex items-center gap-1.5 text-xs text-slate-500">
                            <CalendarDays className="h-3.5 w-3.5 text-slate-400" />
                            <Text as="span">Updated {updatedSpec.value}</Text>
                        </Box>
                    )}
                    {versionSpec && (
                        <Box className="flex items-center gap-1.5 text-xs text-slate-500">
                            <Tag className="h-3.5 w-3.5 text-slate-400" />
                            <Text as="span">{versionSpec.value}</Text>
                        </Box>
                    )}
                </Box>
            </Box>
        </Box>
    );
}
