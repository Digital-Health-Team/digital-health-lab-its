import { Head, usePage } from "@inertiajs/react";
import Preloader from "@/Core/Components/Shared/Preloader/Preloader";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import { Box } from "@/Core/Components/Common/Box";
import TrainingBreadcrumb from "@/Features/Training/Components/TrainingDetail/TrainingBreadcrumb";
import TrainingPreview from "@/Features/Training/Components/TrainingDetail/TrainingPreview";
import TrainingOverview from "@/Features/Training/Components/TrainingDetail/TrainingOverview";
import TrainingCurriculum from "@/Features/Training/Components/TrainingDetail/TrainingCurriculum";
import TrainingInstructorCard from "@/Features/Training/Components/TrainingDetail/TrainingInstructorCard";
import TrainingEnrollCard from "@/Features/Training/Components/TrainingDetail/TrainingEnrollCard";
import RelatedTrainings from "@/Features/Training/Components/TrainingDetail/RelatedTrainings";
import { trainingDetailData } from "@/Features/Training/Data/trainingDetail.data";

interface TrainingDetailPageProps {
    trainingSlug: string;
    [key: string]: unknown;
}

export default function TrainingDetailPage() {
    const { props } = usePage<TrainingDetailPageProps>();
    const trainingSlug = props.trainingSlug;

    // Look up by slug; fall back to first entry for unknown slugs
    const training =
        trainingDetailData[trainingSlug] ?? Object.values(trainingDetailData)[0];

    return (
        <>
            <Head title={training.title} />
            <Preloader />
            <DashboardLayout>
                {/* Breadcrumb */}
                <TrainingBreadcrumb breadcrumb={training.breadcrumb} />

                {/* ── Two-column body ─────────────────────────────── */}
                <Box className="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    {/* LEFT — Preview + Overview + Curriculum + Instructor */}
                    <Box className="lg:col-span-8 flex flex-col gap-6">
                        <TrainingPreview training={training} />
                        <TrainingOverview training={training} />
                        <TrainingCurriculum curriculum={training.curriculum} />
                        <TrainingInstructorCard instructor={training.instructor} />
                    </Box>

                    {/* RIGHT — Sticky enroll card */}
                    <Box className="lg:col-span-4">
                        <TrainingEnrollCard training={training} />
                    </Box>
                </Box>

                {/* ── Related courses ──────────────────────────────── */}
                <RelatedTrainings related={training.related} />
            </DashboardLayout>
        </>
    );
}
