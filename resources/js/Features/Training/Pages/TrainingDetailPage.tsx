import { Head, usePage } from "@inertiajs/react";
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
            <DashboardLayout>
                <TrainingBreadcrumb breadcrumb={breadcrumb} />

                <Box className="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
                    <Box className="order-1 lg:order-none lg:col-start-1 lg:col-span-8 lg:row-start-1">
                        <TrainingPreview training={training} />
                    </Box>

                    <Box className="order-2 lg:order-none lg:col-start-9 lg:col-span-4 lg:row-start-1 lg:row-span-2">
                        <TrainingEnrollCard
                            training={training}
                            isRegistered={isRegistered}
                            userRegistration={userRegistration}
                            isAuthenticated={isAuthenticated}
                        />
                    </Box>

                    <Box className="order-3 lg:order-none lg:col-start-1 lg:col-span-8 lg:row-start-2 flex flex-col gap-6">
                        <TrainingOverview training={training} />
                        <TrainingCurriculum curriculum={training.curriculum} />
                        <TrainingInstructorCard instructor={training.instructor} />
                    </Box>
                </Box>

                <RelatedTrainings related={related} />
            </DashboardLayout>
        </>
    );
}
