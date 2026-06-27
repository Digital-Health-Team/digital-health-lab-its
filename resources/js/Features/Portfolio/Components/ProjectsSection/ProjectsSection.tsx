import { useState } from "react";
import { FolderOpen, Upload } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { Heading } from "@/Core/Components/Common/Heading";
import { Card, CardBody } from "@/Core/Components/Shared";
import Button from "@/Core/Components/Shared/Button/Button";
import ProjectCard from "@/Features/Portfolio/Components/ProjectsSection/ProjectCard";
import ProjectFormModal from "@/Features/Portfolio/Components/ProjectsSection/ProjectFormModal";
import { type UserProject } from "@/Features/Portfolio/Types/portfolio.type";

interface ProjectsSectionProps {
    projects: UserProject[];
}

export default function ProjectsSection({ projects }: ProjectsSectionProps) {
    const [modalOpen, setModalOpen] = useState(false);
    const [editingProject, setEditingProject] = useState<UserProject | null>(null);

    function openCreate() {
        setEditingProject(null);
        setModalOpen(true);
    }

    function openEdit(project: UserProject) {
        setEditingProject(project);
        setModalOpen(true);
    }

    function closeModal() {
        setModalOpen(false);
        setEditingProject(null);
    }

    return (
        <>
            <Box className="flex items-center justify-between mb-4">
                <Box />
                <Button variant="primary" size="sm" className="gap-1.5" onClick={openCreate}>
                    <Upload className="h-3.5 w-3.5" />
                    Upload Project
                </Button>
            </Box>

            {projects.length === 0 ? (
                <Card>
                    <CardBody>
                        <Box className="flex flex-col items-center gap-3 py-10 text-center">
                            <FolderOpen className="h-10 w-10 text-slate-300" />
                            <Heading level={3} className="text-base font-semibold text-slate-600">
                                No projects submitted yet
                            </Heading>
                            <Text variant="small" className="text-slate-400 max-w-xs">
                                Upload your student project and wait for admin approval to have it listed publicly.
                            </Text>
                            <Button variant="primary" size="sm" className="mt-2 gap-1.5" onClick={openCreate}>
                                <Upload className="h-3.5 w-3.5" />
                                Upload Project
                            </Button>
                        </Box>
                    </CardBody>
                </Card>
            ) : (
                <Box className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    {projects.map((project) => (
                        <ProjectCard key={project.id} project={project} onEdit={openEdit} />
                    ))}
                </Box>
            )}

            <ProjectFormModal
                open={modalOpen}
                onClose={closeModal}
                project={editingProject}
            />
        </>
    );
}
