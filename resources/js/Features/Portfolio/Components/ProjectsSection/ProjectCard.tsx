import { Pencil, Trash2 } from "lucide-react";
import { router } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { Image } from "@/Core/Components/Common/Image";
import { Card, CardBody } from "@/Core/Components/Shared";
import Button from "@/Core/Components/Shared/Button/Button";
import { type UserProject } from "@/Features/Portfolio/Types/portfolio.type";

interface ProjectCardProps {
    project: UserProject;
    onEdit: (project: UserProject) => void;
}

const STATUS_STYLES: Record<string, string> = {
    pending: "bg-amber-100 text-amber-700",
    approved: "bg-emerald-100 text-emerald-700",
    rejected: "bg-rose-100 text-rose-700",
};

const STATUS_LABELS: Record<string, string> = {
    pending: "Pending Review",
    approved: "Approved",
    rejected: "Rejected",
};

const CATEGORY_LABELS: Record<string, string> = {
    "3d_model": "3D Model",
    iot_system: "IoT System",
    medical_device: "Medical Device",
    software: "Software",
};

const COVER_COLORS: Record<string, string> = {
    "3d_model": "from-cyan-400 to-blue-500",
    iot_system: "from-emerald-400 to-teal-500",
    medical_device: "from-rose-400 to-pink-500",
    software: "from-violet-400 to-purple-500",
};

function handleDelete(project: UserProject) {
    if (!confirm(`Delete "${project.title}"? This cannot be undone.`)) return;
    router.delete(`/my/projects/${project.id}`);
}

export default function ProjectCard({ project, onEdit }: ProjectCardProps) {
    const canEdit = project.status !== "approved";

    return (
        <Card>
            <CardBody className="p-0 overflow-hidden">
                {/* Cover */}
                <Box className="h-32 relative">
                    {project.coverUrl ? (
                        <Image
                            src={project.coverUrl}
                            alt={project.title}
                            width={400}
                            height={128}
                            className="w-full h-full"
                            objectFit="cover"
                        />
                    ) : (
                        <Box
                            className={`w-full h-full bg-gradient-to-br ${COVER_COLORS[project.category] ?? "from-slate-400 to-slate-500"}`}
                        />
                    )}
                    <Box className="absolute top-2 right-2">
                        <Box
                            as="span"
                            className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold shadow-sm ${STATUS_STYLES[project.status] ?? "bg-slate-100 text-slate-600"}`}
                        >
                            {STATUS_LABELS[project.status] ?? project.status}
                        </Box>
                    </Box>
                </Box>

                {/* Body */}
                <Box className="p-4 space-y-3">
                    <Box>
                        <Text as="span" className="block font-semibold text-slate-800 leading-snug line-clamp-2">
                            {project.title}
                        </Text>
                        <Text variant="small" className="text-slate-400 mt-0.5">
                            {CATEGORY_LABELS[project.category] ?? project.category}
                            {project.listingType ? ` · ${project.listingType}` : ""}
                        </Text>
                    </Box>

                    {project.caption && (
                        <Text variant="small" className="text-slate-500 line-clamp-2">
                            {project.caption}
                        </Text>
                    )}

                    {project.status === "rejected" && (
                        <Box className="rounded-lg bg-rose-50 border border-rose-100 px-3 py-2">
                            <Text variant="small" className="text-rose-700">
                                Rejected by admin. Edit and resubmit for review.
                            </Text>
                        </Box>
                    )}

                    {canEdit && (
                        <Box className="flex gap-2 pt-1">
                            <Button
                                variant="outline"
                                size="sm"
                                className="flex-1 gap-1.5"
                                onClick={() => onEdit(project)}
                            >
                                <Pencil className="h-3.5 w-3.5" />
                                Edit
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                className="text-rose-600 border-rose-200 hover:bg-rose-50"
                                onClick={() => handleDelete(project)}
                            >
                                <Trash2 className="h-3.5 w-3.5" />
                            </Button>
                        </Box>
                    )}
                </Box>
            </CardBody>
        </Card>
    );
}
