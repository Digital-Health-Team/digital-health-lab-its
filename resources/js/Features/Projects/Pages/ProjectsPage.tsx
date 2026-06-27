import { Head } from "@inertiajs/react";
import Preloader from "@/Core/Components/Shared/Preloader/Preloader";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import ProjectsHero from "@/Features/Projects/Components/ProjectsHero/ProjectsHero";
import ProjectCatalogue from "@/Features/Projects/Components/ProjectCatalogue/ProjectCatalogue";
import { projectsHeroData } from "@/Features/Projects/Data/projectsHero.data";
import { projectCatalogueData } from "@/Features/Projects/Data/projectCatalogue.data";

export default function ProjectsPage() {
    return (
        <>
            <Head title="Projects" />
            <Preloader />
            <DashboardLayout>
                {/* 1. Hero banner */}
                <ProjectsHero data={projectsHeroData} />

                {/* 2. Project Catalogue with tab filter */}
                <ProjectCatalogue categories={projectCatalogueData} />
            </DashboardLayout>
        </>
    );
}
