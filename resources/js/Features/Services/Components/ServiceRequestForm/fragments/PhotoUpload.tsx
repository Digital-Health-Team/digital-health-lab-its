import { useRef } from "react";
import { UploadCloud } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";

interface PhotoUploadProps {
    name: string;
    hint: string;
    accept?: string;
    uploadLabel?: string;
    value: File | null;
    onChange: (file: File | null) => void;
}

export default function PhotoUpload({
    name,
    hint,
    accept = "image/*",
    uploadLabel = "Click to upload your reference photo",
    value,
    onChange,
}: PhotoUploadProps) {
    const inputRef = useRef<HTMLInputElement>(null);

    function handleChange(e: React.ChangeEvent<HTMLInputElement>) {
        onChange(e.target.files?.[0] ?? null);
    }

    const isImage = value && value.type.startsWith("image/");
    const previewUrl = isImage ? URL.createObjectURL(value) : null;

    return (
        <Box
            className="relative w-full rounded-xl border-2 border-dashed border-slate-200 bg-white hover:border-[#00426D] hover:bg-[#00426D]/[0.02] transition-colors duration-150 cursor-pointer overflow-hidden"
            style={{ minHeight: "120px" }}
            onClick={() => inputRef.current?.click()}
        >
            {/* Hidden file input */}
            <Box
                as="input"
                type="file"
                name={name}
                accept={accept}
                className="hidden"
                ref={inputRef}
                onChange={handleChange}
            />

            {value ? (
                /* ── File selected state ── */
                <Box className="flex flex-col items-center justify-center gap-2 py-6 px-4 text-center">
                    {previewUrl ? (
                        <Box
                            as="img"
                            src={previewUrl}
                            alt="Preview"
                            className="h-28 w-auto rounded-lg object-cover shadow"
                        />
                    ) : (
                        <Box className="w-12 h-12 rounded-xl bg-secondary-500/10 flex items-center justify-center">
                            <UploadCloud className="h-6 w-6 text-secondary-500" />
                        </Box>
                    )}
                    <Text as="span" className="text-sm font-semibold text-[#00426D]">
                        {value.name}
                    </Text>
                    <Text as="span" className="text-xs text-slate-400">
                        Click to change file
                    </Text>
                </Box>
            ) : (
                /* ── Empty/idle state ── */
                <Box className="flex flex-col items-center justify-center gap-3 py-10 px-4 text-center">
                    <Box className="w-12 h-12 rounded-xl bg-secondary-500/10 flex items-center justify-center">
                        <UploadCloud className="h-6 w-6 text-secondary-500" />
                    </Box>
                    <Box>
                        <Text as="span" className="block text-sm font-semibold text-slate-700">
                            {uploadLabel}
                        </Text>
                        <Text as="span" className="block text-xs text-slate-400 mt-0.5">
                            {hint}
                        </Text>
                    </Box>
                </Box>
            )}
        </Box>
    );
}
