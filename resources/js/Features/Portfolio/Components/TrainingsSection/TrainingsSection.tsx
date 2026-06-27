import { Link } from "@inertiajs/react";
import { ArrowRight, GraduationCap } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { Heading } from "@/Core/Components/Common/Heading";
import { Card, CardBody } from "@/Core/Components/Shared";
import { type UserEnrollment } from "@/Features/Portfolio/Types/portfolio.type";

interface TrainingsSectionProps {
    enrollments: UserEnrollment[];
}

const REGISTRATION_STATUS: Record<string, string> = {
    pending: "bg-amber-100 text-amber-700",
    confirmed: "bg-emerald-100 text-emerald-700",
    cancelled: "bg-slate-100 text-slate-500",
};

const PAYMENT_STATUS: Record<string, string> = {
    unpaid: "bg-rose-100 text-rose-700",
    awaiting_verification: "bg-blue-100 text-blue-700",
    paid: "bg-emerald-100 text-emerald-700",
    rejected: "bg-red-100 text-red-700",
};

const PAYMENT_LABELS: Record<string, string> = {
    unpaid: "Unpaid",
    awaiting_verification: "Verifying",
    paid: "Paid",
    rejected: "Rejected",
};

export default function TrainingsSection({ enrollments }: TrainingsSectionProps) {
    if (enrollments.length === 0) {
        return (
            <Card>
                <CardBody>
                    <Box className="flex flex-col items-center gap-3 py-10 text-center">
                        <GraduationCap className="h-10 w-10 text-slate-300" />
                        <Heading level={3} className="text-base font-semibold text-slate-600">
                            No training enrollments
                        </Heading>
                        <Text variant="small" className="text-slate-400 max-w-xs">
                            Explore available trainings and register for one.
                        </Text>
                        <Link
                            href="/training"
                            className="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-[#00426D] hover:underline"
                        >
                            Browse trainings <ArrowRight className="h-3.5 w-3.5" />
                        </Link>
                    </Box>
                </CardBody>
            </Card>
        );
    }

    return (
        <Box className="space-y-3">
            {enrollments.map((enrollment) => (
                <Card key={enrollment.id}>
                    <CardBody>
                        <Box className="flex items-start justify-between gap-4">
                            <Box className="min-w-0">
                                <Box className="flex items-center gap-2">
                                    <Text as="span" className="block font-semibold text-slate-800 truncate">
                                        {enrollment.trainingTitle ?? "Training"}
                                    </Text>
                                    <Box
                                        as="span"
                                        className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${REGISTRATION_STATUS[enrollment.status] ?? "bg-slate-100 text-slate-600"}`}
                                    >
                                        {enrollment.status.charAt(0).toUpperCase() + enrollment.status.slice(1)}
                                    </Box>
                                </Box>
                                <Text variant="small" className="text-slate-400 mt-1">
                                    {enrollment.trainingDate ?? "Date TBD"}
                                    {enrollment.trainingLocation ? ` · ${enrollment.trainingLocation}` : ""}
                                </Text>
                            </Box>
                            <Box className="flex items-center gap-2 shrink-0">
                                <Box
                                    as="span"
                                    className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${PAYMENT_STATUS[enrollment.paymentStatus] ?? "bg-slate-100 text-slate-600"}`}
                                >
                                    {PAYMENT_LABELS[enrollment.paymentStatus] ?? enrollment.paymentStatus}
                                </Box>
                                {enrollment.trainingSlug && (
                                    <Link
                                        href={`/training/${enrollment.trainingSlug}`}
                                        className="text-slate-300 hover:text-slate-500"
                                    >
                                        <ArrowRight className="h-4 w-4" />
                                    </Link>
                                )}
                            </Box>
                        </Box>
                    </CardBody>
                </Card>
            ))}
        </Box>
    );
}
