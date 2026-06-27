import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { CheckCircle2 } from "lucide-react";
import { type TrainingDetail } from "@/Features/Training/Types/trainingDetail.type";

interface TrainingOverviewProps {
    training: Pick<TrainingDetail, "whatYouWillLearn" | "description">;
}

export default function TrainingOverview({ training }: TrainingOverviewProps) {
    return (
        <Box className="space-y-6">
            {/* What you'll learn */}
            <Box className="rounded-2xl border border-slate-100 bg-white shadow-sm p-5 space-y-4">
                <Heading level={4} className="text-base font-bold text-slate-900">
                    What you&apos;ll learn
                </Heading>
                <Box className="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2.5">
                    {training.whatYouWillLearn.map((item, i) => (
                        <Box key={i} className="flex items-start gap-2.5">
                            <CheckCircle2 className="h-4 w-4 text-secondary-500 shrink-0 mt-0.5" />
                            <Text className="text-sm text-slate-700 leading-snug">{item}</Text>
                        </Box>
                    ))}
                </Box>
            </Box>

            {/* Description */}
            <Box className="rounded-2xl border border-slate-100 bg-white shadow-sm p-5 space-y-3">
                <Heading level={4} className="text-base font-bold text-slate-900">
                    About this course
                </Heading>
                {(training.description ?? '').split("\n\n").filter(Boolean).map((para, i) => (
                    <Text key={i} className="text-sm text-slate-600 leading-relaxed">
                        {para}
                    </Text>
                ))}
            </Box>
        </Box>
    );
}
