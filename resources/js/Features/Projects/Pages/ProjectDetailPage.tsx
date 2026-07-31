import { Head, usePage } from "@inertiajs/react";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import { Box } from "@/Core/Components/Common/Box";
import ProjectBreadcrumb from "@/Features/Projects/Components/ProjectDetail/ProjectBreadcrumb";
import ProjectPreview from "@/Features/Projects/Components/ProjectDetail/ProjectPreview";
import ProjectOverview from "@/Features/Projects/Components/ProjectDetail/ProjectOverview";
import ProjectDetailsCard from "@/Features/Projects/Components/ProjectDetail/ProjectDetailsCard";
import ProjectAuthorCard from "@/Features/Projects/Components/ProjectDetail/ProjectAuthorCard";
import ProjectDownloadCard from "@/Features/Projects/Components/ProjectDetail/ProjectDownloadCard";
import ProjectHighlightsCard from "@/Features/Projects/Components/ProjectDetail/ProjectHighlightsCard";
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
            <DashboardLayout>
                {/* Breadcrumb */}
                <ProjectBreadcrumb breadcrumb={project.breadcrumb} />

                {/* ── Two-column body ─────────────────────────────── */}
                <Box className="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
                    <Box className="order-1 lg:order-none lg:col-start-1 lg:col-span-8 lg:row-start-1">
                        <ProjectPreview project={project} />
                    </Box>

                    {/* Download card + Highlights — reordered ahead of the writeup on mobile so the primary action isn't buried below the fold */}
                    <Box className="order-2 lg:order-none lg:col-start-9 lg:col-span-4 lg:row-start-1 lg:row-span-2 lg:sticky lg:top-20 self-start flex flex-col gap-6">
                        <ProjectDownloadCard project={project} />
                        <ProjectHighlightsCard highlights={project.highlights} />
                    </Box>

                    <Box className="order-3 lg:order-none lg:col-start-1 lg:col-span-8 lg:row-start-2 flex flex-col gap-6">
                        <ProjectOverview project={project} />
                        <ProjectDetailsCard specs={project.specs} />
                        <ProjectAuthorCard author={project.author} />
                    </Box>
                </Box>

                {/* ── Related projects ──────────────────────────────── */}
                <RelatedProjects related={project.related} />
            </DashboardLayout>
        </>
    );
}
