import { Head, usePage } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import ResearchHero from "@/Features/Research/Components/ResearchHero/ResearchHero";
import ResearchSectionHeader from "@/Features/Research/Components/ResearchSectionHeader/ResearchSectionHeader";
import ProjectCatalogue from "@/Features/Projects/Components/ProjectCatalogue/ProjectCatalogue";
import PublicationCatalogue from "@/Features/Publications/Components/PublicationCatalogue/PublicationCatalogue";
import { researchHeroData } from "@/Features/Research/Data/researchHero.data";
import { projectCatalogueData } from "@/Features/Projects/Data/projectCatalogue.data";
import { type PublicationListItem } from "@/Features/Publications/Types/publication.type";

interface ResearchPageProps {
    publications: PublicationListItem[];
    [key: string]: unknown;
}

export default function ResearchPage() {
    const { publications } = usePage<ResearchPageProps>().props;

    return (
        <>
            <Head title="Research" />
            <DashboardLayout>
                {/* 1. Hero banner — its two CTAs anchor to the sections below */}
                <ResearchHero data={researchHeroData} />

                {/* 2. Projects (static catalogue) — scroll-mt clears the sticky Topbar */}
                <Box as="section" id="projects" className="scroll-mt-20 lg:scroll-mt-24">
                    <ResearchSectionHeader eyebrow="Student innovation" title="Projects" />
                    <ProjectCatalogue categories={projectCatalogueData} />
                </Box>

                {/* 3. Publications — pt-8 lands the gap on the design system's 64px section-tight */}
                <Box
                    as="section"
                    id="publications"
                    className="scroll-mt-20 lg:scroll-mt-24 pt-8"
                >
                    <ResearchSectionHeader eyebrow="Academic research" title="Publications" />
                    <PublicationCatalogue publications={publications} />
                </Box>
            </DashboardLayout>
        </>
    );
}
