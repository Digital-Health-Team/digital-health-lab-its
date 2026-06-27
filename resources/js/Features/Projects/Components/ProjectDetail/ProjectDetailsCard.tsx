import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { type ProjectDetail } from "@/Features/Projects/Types/projectDetail.type";

interface ProjectDetailsCardProps {
    specs: ProjectDetail["specs"];
}

function SpecRow({ label, value }: { label: string; value: string }) {
    return (
        <Box className="flex items-center justify-between py-2.5 border-b border-slate-50 last:border-0">
            <Text className="text-xs font-medium text-slate-500">{label}</Text>
            <Text className="text-xs font-semibold text-slate-800">{value}</Text>
        </Box>
    );
}

export default function ProjectDetailsCard({ specs }: ProjectDetailsCardProps) {
    return (
        <Box className="rounded-2xl border border-slate-100 bg-white shadow-sm p-5 space-y-1">
            <Heading level={4} className="text-base font-bold text-slate-900 mb-3">
                Project details
            </Heading>
            {specs.map((spec, i) => (
                <SpecRow key={i} label={spec.label} value={spec.value} />
            ))}
        </Box>
    );
}
