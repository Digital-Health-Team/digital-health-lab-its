import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { cn } from "@/Core/Utils/utils";
import { type FilamentOption } from "@/Features/Services/Types/serviceRequest.type";

interface FilamentTypeInputProps {
    name: string;
    filaments: FilamentOption[];
    value: string;
    onChange: (code: string) => void;
}

export default function FilamentTypeInput({
    name,
    filaments,
    value,
    onChange,
}: FilamentTypeInputProps) {
    return (
        <Box className="flex flex-col gap-2">
            {filaments.map((filament) => {
                const selected = value === filament.code;

                return (
                    <Box
                        key={filament.code}
                        as="button"
                        type="button"
                        onClick={() => onChange(filament.code)}
                        className={cn(
                            "w-full text-left rounded-xl border px-4 py-3 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-[#00426D]/30",
                            selected
                                ? "border-[#00426D] bg-[#00426D]/[0.04]"
                                : "border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50",
                        )}
                    >
                        {/* ── Row: radio + label + price badge ── */}
                        <Box className="flex items-center gap-3">
                            {/* Custom radio circle */}
                            <Box
                                className={cn(
                                    "w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0 transition-colors duration-150",
                                    selected
                                        ? "border-[#00426D]"
                                        : "border-slate-300",
                                )}
                            >
                                {selected && (
                                    <Box
                                        className="w-2 h-2 rounded-full bg-[#00426D]"
                                    />
                                )}
                            </Box>

                            {/* Name + scientific name */}
                            <Box className="flex-1 min-w-0">
                                <Text
                                    as="span"
                                    className={cn(
                                        "block text-sm font-semibold",
                                        selected ? "text-[#00426D]" : "text-slate-700",
                                    )}
                                >
                                    {filament.name}{" "}
                                    <Text
                                        as="span"
                                        className="font-normal text-slate-500"
                                    >
                                        ({filament.scientificName})
                                    </Text>
                                </Text>
                            </Box>

                            {/* Price badge */}
                            <Box
                                as="span"
                                className={cn(
                                    "shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full",
                                    selected
                                        ? "bg-amber-100 text-amber-700"
                                        : "bg-slate-100 text-slate-500",
                                )}
                            >
                                {filament.priceLabel} / gram
                            </Box>
                        </Box>

                        {/* ── Expanded description (selected only) ── */}
                        {selected && filament.description && (
                            <Text
                                as="p"
                                className="mt-2 ml-7 text-xs text-slate-500 leading-relaxed"
                            >
                                {filament.description}
                            </Text>
                        )}
                    </Box>
                );
            })}

            {/* Hidden input so the field name is included in the form data */}
            <Box
                as="input"
                type="hidden"
                name={name}
                value={value}
            />
        </Box>
    );
}
