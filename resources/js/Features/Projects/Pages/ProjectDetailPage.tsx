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
                <Box className="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    {/* LEFT — Preview + Overview + Details + Author */}
                    <Box className="lg:col-span-8 flex flex-col gap-6">
                        <ProjectPreview project={project} />
                        <ProjectOverview project={project} />
                        <ProjectDetailsCard specs={project.specs} />
                        <ProjectAuthorCard author={project.author} />
                    </Box>

                    {/* RIGHT — Download card + Highlights */}
                    <Box className="lg:col-span-4 flex flex-col gap-6 lg:sticky lg:top-20 self-start">
                        <ProjectDownloadCard project={project} />
                        <ProjectHighlightsCard highlights={project.highlights} />
                    </Box>
                </Box>

                {/* ── Related projects ──────────────────────────────── */}
                <RelatedProjects related={project.related} />
            </DashboardLayout>
        </>
    );
}
