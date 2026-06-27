import { useState } from "react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Button } from "@/Core/Components/Shared";
import { CheckCircle2 } from "lucide-react";
import TrainingRegistrationModal from "./TrainingRegistrationModal";
import { type TrainingDetail } from "@/Features/Training/Types/trainingDetail.type";

interface TrainingEnrollCardProps {
    training: Pick<TrainingDetail, "title" | "price" | "includes">;
}

export default function TrainingEnrollCard({ training }: TrainingEnrollCardProps) {
    const [modalOpen, setModalOpen] = useState(false);

    const priceFormatted = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(training.price);

    return (
        <>
            <Box className="rounded-2xl border border-slate-100 bg-white shadow-sm p-5 space-y-5 lg:sticky lg:top-20">
                {/* Price */}
                <Box className="space-y-1">
                    <Heading level={2} className="text-2xl font-extrabold text-slate-900">
                        {priceFormatted}
                    </Heading>
                    <Text className="text-xs text-slate-400">One-time enrollment fee</Text>
                </Box>

                {/* CTA */}
                <Button
                    variant="primary"
                    size="lg"
                    className="w-full"
                    onClick={() => setModalOpen(true)}
                >
                    Register now
                </Button>

                {/* Includes list */}
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
            />
        </>
    );
}
