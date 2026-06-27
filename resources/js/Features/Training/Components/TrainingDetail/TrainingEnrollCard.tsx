import { useState } from "react";
import { Link } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Button } from "@/Core/Components/Shared";
import { CheckCircle2 } from "lucide-react";
import TrainingRegistrationModal from "./TrainingRegistrationModal";
import { type TrainingDetail } from "@/Features/Training/Types/trainingDetail.type";

interface TrainingEnrollCardProps {
    training: Pick<TrainingDetail, "id" | "slug" | "title" | "price" | "isPaid" | "isFull" | "includes">;
    isRegistered: boolean;
    userRegistration: { status: string } | null;
    isAuthenticated: boolean;
}

export default function TrainingEnrollCard({
    training,
    isRegistered,
    userRegistration,
    isAuthenticated,
}: TrainingEnrollCardProps) {
    const [modalOpen, setModalOpen] = useState(false);

    const priceFormatted = training.isPaid
        ? new Intl.NumberFormat("id-ID", {
              style: "currency",
              currency: "IDR",
              minimumFractionDigits: 0,
          }).format(training.price)
        : "Free";

    const renderCTA = () => {
        if (!isAuthenticated) {
            return (
                <Link href="/login" className="block w-full">
                    <Button variant="primary" size="lg" className="w-full">
                        Login to Register
                    </Button>
                </Link>
            );
        }

        if (isRegistered && userRegistration) {
            const statusStyles: Record<string, string> = {
                confirmed: "bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20",
                pending: "bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20",
                cancelled: "bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700",
            };
            const style = statusStyles[userRegistration.status] ?? statusStyles.pending;

            return (
                <Box className={`rounded-xl border px-4 py-3 text-center ${style}`}>
                    <Text className="text-sm font-semibold capitalize">
                        You&apos;re enrolled &mdash; {userRegistration.status}
                    </Text>
                </Box>
            );
        }

        if (training.isFull) {
            return (
                <Button variant="primary" size="lg" className="w-full opacity-50 cursor-not-allowed" disabled>
                    Class Full
                </Button>
            );
        }

        return (
            <Button variant="primary" size="lg" className="w-full" onClick={() => setModalOpen(true)}>
                Register now
            </Button>
        );
    };

    return (
        <>
            <Box className="rounded-2xl border border-slate-100 bg-white shadow-sm p-5 space-y-5 lg:sticky lg:top-20">
                <Box className="space-y-1">
                    <Heading level={2} className="text-2xl font-extrabold text-slate-900">
                        {priceFormatted}
                    </Heading>
                    {training.isPaid && (
                        <Text className="text-xs text-slate-400">One-time enrollment fee</Text>
                    )}
                </Box>

                {renderCTA()}

                <Box className="space-y-2.5 pt-1 border-t border-slate-100">
                    <Text className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        This course includes
                    </Text>
                    {training.includes.map((item, i) => (
                        <Box key={i} className="flex items-start gap-2.5">
                            <CheckCircle2 className="h-3.5 w-3.5 text-secondary-400 shrink-0 mt-0.5" />
                            <Text className="text-xs text-slate-600 leading-snug">{item}</Text>
                        </Box>
                    ))}
                </Box>
            </Box>

            <TrainingRegistrationModal
                open={modalOpen}
                onClose={() => setModalOpen(false)}
                courseTitle={training.title}
                price={training.price}
                trainingSlug={training.slug}
            />
        </>
    );
}
