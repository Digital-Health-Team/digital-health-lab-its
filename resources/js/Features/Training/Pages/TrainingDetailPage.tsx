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
import { type TrainingDetail } from "@/Features/Training/Types/trainingDetail.type";
import { type Course } from "@/Features/Training/Types/course.type";

interface TrainingDetailPageProps {
    training: TrainingDetail;
    isRegistered: boolean;
    userRegistration: { id: number; status: string; paymentStatus: string } | null;
    isAuthenticated: boolean;
    related: Course[];
    paymentInfo: {
        qrisImageUrl: string;
        bankName: string;
        bankAccountName: string;
        bankAccountNumber: string;
    } | null;
    [key: string]: unknown;
}

export default function TrainingDetailPage() {
    const { props } = usePage<TrainingDetailPageProps>();
    const { training, isRegistered, userRegistration, isAuthenticated, related } = props;

    const breadcrumb = ["Home", "Training", training.title];

    return (
        <>
            <Head title={training.title} />
            <Preloader />
            <DashboardLayout>
                <TrainingBreadcrumb breadcrumb={breadcrumb} />

                <Box className="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <Box className="lg:col-span-8 flex flex-col gap-6">
                        <TrainingPreview training={training} />
                        <TrainingOverview training={training} />
                        <TrainingCurriculum curriculum={training.curriculum} />
                        <TrainingInstructorCard instructor={training.instructor} />
                    </Box>

                    <Box className="lg:col-span-4">
                        <TrainingEnrollCard
                            training={training}
                            isRegistered={isRegistered}
                            userRegistration={userRegistration}
                            isAuthenticated={isAuthenticated}
                        />
                    </Box>
                </Box>

                <RelatedTrainings related={related} />
            </DashboardLayout>
        </>
    );
}
