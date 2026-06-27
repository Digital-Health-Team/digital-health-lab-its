import { Head } from "@inertiajs/react";
import Preloader from "@/Core/Components/Shared/Preloader/Preloader";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import PublicationsHero from "@/Features/Publications/Components/PublicationsHero/PublicationsHero";
import PublicationCatalogue from "@/Features/Publications/Components/PublicationCatalogue/PublicationCatalogue";
import { publicationsHeroData } from "@/Features/Publications/Data/publicationsHero.data";
import { publicationsData } from "@/Features/Publications/Data/publications.data";

export default function PublicationsPage() {
    return (
        <>
            <Head title="Publications" />
            <Preloader />
            <DashboardLayout>
                {/* ── Featured hero ── */}
                <PublicationsHero data={publicationsHeroData} />

                {/* ── Publications table (panel + filter + row list) ── */}
                <PublicationCatalogue publications={publicationsData} />
            </DashboardLayout>
        </>
    );
}
