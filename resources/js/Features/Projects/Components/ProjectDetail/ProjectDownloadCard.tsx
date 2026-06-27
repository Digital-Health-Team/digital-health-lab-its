import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Download, CheckCircle2, FileArchive } from "lucide-react";
import { type ProjectDetail } from "@/Features/Projects/Types/projectDetail.type";

interface ProjectDownloadCardProps {
    project: Pick<ProjectDetail, "title" | "downloadFile" | "includes">;
}

export default function ProjectDownloadCard({ project }: ProjectDownloadCardProps) {
    const { downloadFile } = project;

    return (
        <Box className="rounded-2xl border border-slate-100 bg-white shadow-sm p-5 space-y-5 lg:sticky lg:top-20">
            {/* File info */}
            <Box className="flex items-start gap-3">
                <Box className="h-10 w-10 rounded-xl bg-primary-50 flex items-center justify-center shrink-0">
                    <FileArchive className="h-5 w-5 text-primary-700" />
                </Box>
                <Box className="min-w-0 space-y-0.5">
                    <Text className="text-xs font-semibold text-slate-800 leading-snug truncate">
                        {downloadFile.fileName}
                    </Text>
                    <Text className="text-xs text-slate-400">
                        {downloadFile.fileType} · {downloadFile.fileSize}
                    </Text>
                </Box>
            </Box>

            {/* Download CTA */}
            <a
                href={downloadFile.fileUrl}
                download={downloadFile.fileName}
                className="inline-flex w-full items-center justify-center gap-2 bg-secondary-500 hover:bg-secondary-600 text-white shadow-md shadow-secondary-500/30 hover:shadow-secondary-500/50 px-8 py-3 text-sm font-semibold rounded-full transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary-500/50 focus-visible:ring-offset-2 font-body"
            >
                <Download className="h-4 w-4" />
                Download Project
            </a>

            {/* Includes list */}
            <Box className="space-y-2.5 pt-1 border-t border-slate-100">
                <Text className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    What&apos;s included
                </Text>
                {project.includes.map((item, i) => (
                    <Box key={i} className="flex items-start gap-2.5">
                        <CheckCircle2 className="h-3.5 w-3.5 text-secondary-400 shrink-0 mt-0.5" />
                        <Text className="text-xs text-slate-600 leading-snug">{item}</Text>
                    </Box>
                ))}
            </Box>
        </Box>
    );
}
