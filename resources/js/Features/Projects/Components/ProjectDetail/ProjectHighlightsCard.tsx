import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { CheckCircle2 } from "lucide-react";

interface ProjectHighlightsCardProps {
    highlights: string[];
}

export default function ProjectHighlightsCard({ highlights }: ProjectHighlightsCardProps) {
    return (
        <Box className="rounded-2xl border border-slate-100 bg-white shadow-sm p-5 space-y-4">
            <Heading level={4} className="text-base font-bold text-slate-900">
                Project highlights
            </Heading>
            <Box className="space-y-2.5">
                {highlights.map((item, i) => (
                    <Box key={i} className="flex items-start gap-2.5">
                        <CheckCircle2 className="h-4 w-4 text-secondary-500 shrink-0 mt-0.5" />
                        <Text className="text-sm text-slate-700 leading-snug">{item}</Text>
                    </Box>
                ))}
            </Box>
        </Box>
    );
}
