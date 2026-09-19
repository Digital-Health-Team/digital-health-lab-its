import { useRef, useState } from "react";
import { FileText, FileUp, ImageUp, X } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { Image } from "@/Core/Components/Common/Image";
import { useTranslation } from "@/Core/Hooks/useTranslation";

export interface ExistingFile {
    name: string;
    size: string | null;
    url: string | null;
}

interface FileDropzoneProps {
    label: string;
    /** Passed straight to the input; also gates what a drop accepts. */
    accept?: string;
    multiple?: boolean;
    hint: string;
    caption?: string;
    files: File[];
    onChange: (files: File[]) => void;
    /** Already stored on the server — shown in the preview list on edit. */
    existing?: ExistingFile[];
    error?: string;
    /**
     * Server ceiling in kilobytes, capped by PHP's upload_max_filesize/post_max_size.
     * Enforced here so an oversized file is refused instantly instead of being uploaded
     * and then bounced by the SAPI with a 413 that Laravel cannot turn into a form error.
     */
    maxKb: number;
}

function isImage(file: File): boolean {
    return file.type.startsWith("image/");
}

function humanSize(bytes: number): string {
    if (bytes < 1024) return `${bytes} B`;
    if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(0)} KB`;
    return `${(bytes / 1024 / 1024).toFixed(1)} MB`;
}

export default function FileDropzone({
    label,
    accept,
    multiple = false,
    hint,
    caption,
    files,
    onChange,
    existing = [],
    error,
    maxKb,
}: FileDropzoneProps) {
    const { t } = useTranslation();
    const inputRef = useRef<HTMLInputElement>(null);
    const [dragging, setDragging] = useState(false);
    const [rejected, setRejected] = useState<string | null>(null);

    /** Drops the too-big ones and reports them, rather than failing the whole selection. */
    function accepted(list: File[]): File[] {
        const tooBig = list.filter((f) => f.size > maxKb * 1024);
        const ok = list.filter((f) => f.size <= maxKb * 1024);

        setRejected(
            tooBig.length
                ? t("Too large, skipped: :names — the limit is :limit per file.")
                      .replace(":names", tooBig.map((f) => f.name).join(", "))
                      .replace(":limit", humanSize(maxKb * 1024))
                : null,
        );

        return multiple ? [...files, ...ok] : ok.slice(0, 1);
    }

    function handleDrop(e: React.DragEvent) {
        e.preventDefault();
        setDragging(false);
        const dropped = Array.from(e.dataTransfer.files ?? []);
        if (dropped.length) onChange(accepted(dropped));
    }

    return (
        <Box className="space-y-2">
            <Box as="label" className="block text-sm font-medium text-slate-700">
                {label}
            </Box>

            <Box
                onDragOver={(e: React.DragEvent) => {
                    e.preventDefault();
                    setDragging(true);
                }}
                onDragLeave={() => setDragging(false)}
                onDrop={handleDrop}
                className={`flex flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed px-6 py-10 text-center transition-colors ${
                    dragging
                        ? "border-secondary-500 bg-secondary-500/5"
                        : "border-slate-200 bg-slate-50/60 hover:border-slate-300"
                }`}
            >
                <Box className="rounded-xl bg-slate-200/70 p-2.5 text-slate-600">
                    {accept?.startsWith("image/") ? (
                        <ImageUp className="h-5 w-5" />
                    ) : (
                        <FileUp className="h-5 w-5" />
                    )}
                </Box>
                <Text as="span" className="text-base font-semibold text-slate-700">
                    {t("Drag & drop files here")}
                </Text>
                <Text variant="small" className="text-slate-400">
                    {hint}
                </Text>
                <Box
                    as="button"
                    type="button"
                    onClick={() => inputRef.current?.click()}
                    className="mt-1 rounded-lg bg-slate-200/80 px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-secondary-500"
                >
                    {t("Browse files")}
                </Box>
                <Box
                    as="input"
                    ref={inputRef}
                    type="file"
                    accept={accept}
                    multiple={multiple}
                    className="hidden"
                    onChange={(e: React.ChangeEvent<HTMLInputElement>) => {
                        const picked = Array.from(e.target.files ?? []);
                        if (picked.length) onChange(accepted(picked));
                        // Lets the same file be re-picked after a removal.
                        e.target.value = "";
                    }}
                />
            </Box>

            {caption && (
                <Text variant="small" className="text-slate-400">
                    {caption}
                </Text>
            )}
            {(error || rejected) && (
                <Text variant="small" className="text-rose-600">
                    {error ?? rejected}
                </Text>
            )}

            {/* ── Preview ── */}
            {(files.length > 0 || existing.length > 0) && (
                <Box className="space-y-2 pt-1">
                    <Text as="span" className="block text-xs font-bold uppercase tracking-wider text-slate-400">
                        {t("Preview")}
                    </Text>

                    {existing.map((file) => (
                        <Box
                            key={`existing-${file.name}`}
                            className="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2"
                        >
                            <Box className="rounded-lg bg-slate-100 p-2 text-slate-500">
                                <FileText className="h-4 w-4" />
                            </Box>
                            <Box className="min-w-0 flex-1">
                                <Text as="span" className="block truncate text-sm font-medium text-slate-700">
                                    {file.name}
                                </Text>
                                <Text variant="small" className="text-slate-400">
                                    {file.size ?? t("Already uploaded")}
                                </Text>
                            </Box>
                        </Box>
                    ))}

                    {files.map((file, i) => (
                        <Box
                            key={`new-${file.name}-${i}`}
                            className="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2"
                        >
                            {isImage(file) ? (
                                <Image
                                    src={URL.createObjectURL(file)}
                                    alt={file.name}
                                    className="h-12 w-12 shrink-0 rounded-lg"
                                    objectFit="cover"
                                />
                            ) : (
                                <Box className="rounded-lg bg-slate-100 p-2 text-slate-500">
                                    <FileText className="h-4 w-4" />
                                </Box>
                            )}
                            <Box className="min-w-0 flex-1">
                                <Text as="span" className="block truncate text-sm font-medium text-slate-700">
                                    {file.name}
                                </Text>
                                <Text variant="small" className="text-slate-400">
                                    {humanSize(file.size)}
                                </Text>
                            </Box>
                            <Box
                                as="button"
                                type="button"
                                aria-label={t("Remove")}
                                onClick={() => onChange(files.filter((_, j) => j !== i))}
                                className="rounded-lg p-1.5 text-slate-500 transition-colors hover:bg-rose-50 hover:text-rose-600"
                            >
                                <X className="h-4 w-4" />
                            </Box>
                        </Box>
                    ))}
                </Box>
            )}
        </Box>
    );
}
