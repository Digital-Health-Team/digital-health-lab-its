import { Head, usePage } from "@inertiajs/react";
import Preloader from "@/Core/Components/Shared/Preloader/Preloader";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import { Box } from "@/Core/Components/Common/Box";
import ProjectBreadcrumb from "@/Features/Projects/Components/ProjectDetail/ProjectBreadcrumb";
import ProjectPreview from "@/Features/Projects/Components/ProjectDetail/ProjectPreview";
import ProjectOverview from "@/Features/Projects/Components/ProjectDetail/ProjectOverview";
import ProjectDetailsCard from "@/Features/Projects/Components/ProjectDetail/ProjectDetailsCard";
import ProjectAuthorCard from "@/Features/Projects/Components/ProjectDetail/ProjectAuthorCard";
import ProjectDownloadCard from "@/Features/Projects/Components/ProjectDetail/ProjectDownloadCard";
import RelatedProjects from "@/Features/Projects/Components/ProjectDetail/RelatedProjects";
import { projectDetailData } from "@/Features/Projects/Data/projectDetail.data";

interface ProjectDetailPageProps {
    projectSlug: string;
    [key: string]: unknown;
}

export default function ProjectDetailPage() {
    const { props } = usePage<ProjectDetailPageProps>();
    const projectSlug = props.projectSlug;

    // Look up by slug; fall back to first entry for unknown slugs
    const project =
        projectDetailData[projectSlug] ?? Object.values(projectDetailData)[0];

    return (
        <>
            <Head title={project.title} />
            <Preloader />
            <DashboardLayout>
                {/* Breadcrumb */}
                <ProjectBreadcrumb breadcrumb={project.breadcrumb} />

                {/* ── Two-column body ─────────────────────────────── */}
                <Box className="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    {/* LEFT — Preview + Overview + Details + Author */}
                    <Box className="lg:col-span-8 flex flex-col gap-6">
                        <ProjectPreview project={project} />
                        <ProjectOverview project={project} />
                        <ProjectDetailsCard specs={project.specs} />
                        <ProjectAuthorCard author={project.author} />
                    </Box>

                    {/* RIGHT — Sticky download card */}
                    <Box className="lg:col-span-4">
                        <ProjectDownloadCard project={project} />
                    </Box>
                </Box>

                {/* ── Related projects ──────────────────────────────── */}
                <RelatedProjects related={project.related} />
            </DashboardLayout>
        </>
    );
}
