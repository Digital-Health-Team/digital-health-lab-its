import { useState } from "react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { ChevronDown, PlayCircle } from "lucide-react";
import { cn } from "@/Core/Utils/utils";
import { type TrainingCurriculumModule } from "@/Features/Training/Types/trainingDetail.type";

interface TrainingCurriculumProps {
    curriculum: TrainingCurriculumModule[];
}

function ModuleRow({ item }: { item: TrainingCurriculumModule }) {
    const [open, setOpen] = useState(false);

    return (
        <Box className="border border-slate-100 rounded-xl overflow-hidden">
            <button
                type="button"
                onClick={() => setOpen((v) => !v)}
                className="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-slate-50 transition-colors"
            >
                <Text className="text-sm font-semibold text-slate-800">{item.module}</Text>
                <ChevronDown
                    className={cn(
                        "h-4 w-4 text-slate-400 shrink-0 transition-transform duration-200",
                        open && "rotate-180",
                    )}
                />
            </button>
            {open && (
                <Box className="border-t border-slate-100 px-4 py-3 space-y-2 bg-slate-50/50">
                    {item.lessons.map((lesson, i) => (
                        <Box key={i} className="flex items-center gap-2.5">
                            <PlayCircle className="h-3.5 w-3.5 text-secondary-400 shrink-0" />
                            <Text className="text-xs text-slate-600">{lesson}</Text>
                        </Box>
                    ))}
                </Box>
            )}
        </Box>
    );
}

export default function TrainingCurriculum({ curriculum }: TrainingCurriculumProps) {
    return (
        <Box className="rounded-2xl border border-slate-100 bg-white shadow-sm p-5 space-y-4">
            <Heading level={4} className="text-base font-bold text-slate-900">
                Course curriculum
            </Heading>
            <Box className="space-y-2">
                {curriculum.map((item, i) => (
                    <ModuleRow key={i} item={item} />
                ))}
            </Box>
        </Box>
    );
}
