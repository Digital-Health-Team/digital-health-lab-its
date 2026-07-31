import { useState } from "react";
import { FileText, ExternalLink, Download, Image as ImageIcon } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { cn } from "@/Core/Utils/utils";

interface PublicationPdfPreviewProps {
    thumbnailUrl: string;
    pdfUrl: string;
    title: string;
    fileSize?: string;
}

type PreviewMode = "thumbnail" | "pdf";

export default function PublicationPdfPreview({
    thumbnailUrl,
    pdfUrl,
    title,
    fileSize,
}: PublicationPdfPreviewProps) {
    const [mode, setMode] = useState<PreviewMode>("thumbnail");

    return (
        <Box className="flex flex-col gap-3">
            {/* ── Toggle bar ── */}
            <Box className="flex items-center gap-2 p-1 bg-slate-100 rounded-xl w-fit">
                <button
                    type="button"
                    onClick={() => setMode("thumbnail")}
                    className={cn(
                        "flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all",
                        mode === "thumbnail"
                            ? "bg-white text-slate-800 shadow-sm"
                            : "text-slate-500 hover:text-slate-700",
                    )}
                >
                    <ImageIcon className="h-3.5 w-3.5" />
                    Cover
                </button>
                <button
                    type="button"
                    onClick={() => setMode("pdf")}
                    className={cn(
                        "flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all",
                        mode === "pdf"
                            ? "bg-white text-slate-800 shadow-sm"
                            : "text-slate-500 hover:text-slate-700",
                    )}
                >
                    <FileText className="h-3.5 w-3.5" />
                    PDF Preview
                </button>
            </Box>

            {/* ── Main view panel ── */}
            <Box className="relative w-full rounded-2xl overflow-hidden border border-slate-200 bg-slate-50">
                {mode === "thumbnail" ? (
                    /* Cover / thumbnail */
                    <Box
                        className="w-full aspect-[3/2]"
                        style={{
                            backgroundImage: `url(${thumbnailUrl})`,
                            backgroundSize: "cover",
                            backgroundPosition: "center",
                        }}
                    />
                ) : (
                    /* Native PDF embed — A4 aspect ratio (1 : √2 ≈ 1 : 1.414) */
                    <Box className="w-full" style={{ aspectRatio: "1 / 1.414" }}>
                        <object
                            data={pdfUrl}
                            type="application/pdf"
                            className="w-full h-full"
                            aria-label={`PDF preview of ${title}`}
                        >
                            {/* Fallback for browsers that cannot embed PDFs */}
                            <Box className="flex flex-col items-center justify-center h-full gap-4 py-16 px-6 text-center">
                                <FileText className="h-12 w-12 text-slate-300" />
                                <Text className="text-slate-500 text-sm font-medium">
                                    Your browser cannot display the PDF inline.
                                </Text>
                                <a
                                    href={pdfUrl}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-700 text-white text-xs font-semibold hover:bg-primary-800 transition-colors"
                                >
                                    <ExternalLink className="h-3.5 w-3.5" />
                                    Open PDF in new tab
                                </a>
                            </Box>
                        </object>
                    </Box>
                )}
            </Box>

            {/* ── Action links ── */}
            <Box className="flex items-center gap-3">
                <a
                    href={pdfUrl}
                    download
                    className="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-primary-700 text-white text-xs font-semibold hover:bg-primary-800 transition-colors"
                >
                    <Download className="h-3.5 w-3.5" />
                    Download PDF
                    {fileSize && (
                        <Text as="span" className="text-white/70 font-normal ml-0.5">
                            ({fileSize})
                        </Text>
                    )}
                </a>

                <a
                    href={pdfUrl}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-slate-300 text-slate-600 text-xs font-semibold hover:border-primary-700 hover:text-primary-700 transition-colors"
                >
                    <ExternalLink className="h-3.5 w-3.5" />
                    Open in new tab
                </a>
            </Box>
        </Box>
    );
}
