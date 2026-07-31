import { Head, usePage } from "@inertiajs/react";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import PublicationsHero from "@/Features/Publications/Components/PublicationsHero/PublicationsHero";
import PublicationCatalogue from "@/Features/Publications/Components/PublicationCatalogue/PublicationCatalogue";
import { publicationsHeroData } from "@/Features/Publications/Data/publicationsHero.data";
import { type PublicationListItem } from "@/Features/Publications/Types/publication.type";

interface PublicationsPageProps {
    publications: PublicationListItem[];
    [key: string]: unknown;
}

export default function PublicationsPage() {
    const { publications } = usePage<PublicationsPageProps>().props;

    return (
        <>
            <Head title="Publications" />
            <DashboardLayout>
                {/* ── Featured hero ── */}
                <PublicationsHero data={publicationsHeroData} />

                {/* ── Publications table (panel + filter + row list) ── */}
                <PublicationCatalogue publications={publications} />
            </DashboardLayout>
        </>
    );
}
