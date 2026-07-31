import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { type ProjectDetail } from "@/Features/Projects/Types/projectDetail.type";

interface ProjectOverviewProps {
    project: Pick<ProjectDetail, "description">;
}

export default function ProjectOverview({ project }: ProjectOverviewProps) {
    return (
        <Box className="rounded-2xl border border-slate-100 bg-white shadow-sm p-5 space-y-3">
            <Heading level={4} className="text-base font-bold text-slate-900">
                About this project
            </Heading>
            {project.description.map((para, i) => (
                <Text key={i} className="text-sm text-slate-600 leading-relaxed">
                    {para}
                </Text>
            ))}
        </Box>
    );
}
