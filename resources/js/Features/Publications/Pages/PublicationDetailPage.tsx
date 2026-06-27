import { Head } from "@inertiajs/react";
import { usePage } from "@inertiajs/react";
import Preloader from "@/Core/Components/Shared/Preloader/Preloader";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import { Box } from "@/Core/Components/Common/Box";
import PublicationBreadcrumb from "@/Features/Publications/Components/PublicationDetail/PublicationBreadcrumb";
import PublicationPdfPreview from "@/Features/Publications/Components/PublicationDetail/PublicationPdfPreview";
import PublicationInfo from "@/Features/Publications/Components/PublicationDetail/PublicationInfo";
import RelatedPublications from "@/Features/Publications/Components/PublicationDetail/RelatedPublications";
import { publicationDetailData } from "@/Features/Publications/Data/publicationDetail.data";

interface PublicationDetailPageProps {
    publicationSlug: string;
    [key: string]: unknown;
}

export default function PublicationDetailPage() {
    // publicationSlug from Inertia prop — available for future dynamic binding
    const { props } = usePage<PublicationDetailPageProps>();
    const _publicationSlug = props.publicationSlug;

    // Static mock — all content comes from publicationDetailData
    const publication = publicationDetailData;

    return (
        <>
            <Head title={publication.title} />
            <Preloader />
            <DashboardLayout>
                {/* Breadcrumb */}
                <PublicationBreadcrumb title={publication.title} />

                {/* ── Two-column body ── */}
                <Box className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    {/* LEFT — PDF preview */}
                    <Box className="lg:col-span-7">
                        <PublicationPdfPreview
                            thumbnailUrl={publication.thumbnailUrl}
                            pdfUrl={publication.pdfUrl}
                            title={publication.title}
                            fileSize={publication.fileSize}
                        />
                    </Box>

                    {/* RIGHT — Publication info */}
                    <Box className="lg:col-span-5">
                        <PublicationInfo publication={publication} />
                    </Box>
                </Box>

                {/* ── Related publications ── */}
                <RelatedPublications related={publication.related} />
            </DashboardLayout>
        </>
    );
}
