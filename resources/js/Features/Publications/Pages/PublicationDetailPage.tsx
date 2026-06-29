import { Head, usePage } from "@inertiajs/react";
import Preloader from "@/Core/Components/Shared/Preloader/Preloader";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import PublicationBreadcrumb from "@/Features/Publications/Components/PublicationDetail/PublicationBreadcrumb";
import PublicationPdfPreview from "@/Features/Publications/Components/PublicationDetail/PublicationPdfPreview";
import PublicationInfo from "@/Features/Publications/Components/PublicationDetail/PublicationInfo";
import RelatedPublications from "@/Features/Publications/Components/PublicationDetail/RelatedPublications";
import { type PublicationDetail } from "@/Features/Publications/Types/publication.type";

interface PublicationDetailPageProps {
    publication: PublicationDetail;
    [key: string]: unknown;
}

export default function PublicationDetailPage() {
    const { publication } = usePage<PublicationDetailPageProps>().props;

    return (
        <>
            <Head title={publication.title} />
            <Preloader />
            <DashboardLayout>
                {/* Breadcrumb */}
                <PublicationBreadcrumb title={publication.title} />

                {/* ── Two-column body ── */}
                <Box className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    {/* LEFT — PDF preview + Full Description */}
                    <Box className="lg:col-span-7 flex flex-col gap-6">
                        <PublicationPdfPreview
                            thumbnailUrl={publication.thumbnailUrl}
                            pdfUrl={publication.pdfUrl}
                            title={publication.title}
                            fileSize={publication.fileSize}
                        />
                        {publication.description.length > 0 && (
                            <Box className="rounded-2xl border border-slate-200 p-5 bg-white flex flex-col gap-3">
                                <Heading
                                    level={4}
                                    className="font-display text-sm font-bold text-slate-700 uppercase tracking-wide"
                                >
                                    Full Description
                                </Heading>
                                {publication.description.map((para, i) => (
                                    <Text key={i} className="text-sm text-slate-600 leading-relaxed">
                                        {para}
                                    </Text>
                                ))}
                            </Box>
                        )}
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
