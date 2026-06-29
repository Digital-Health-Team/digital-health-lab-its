import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { cn } from "@/Core/Utils/utils";
import { type ColorOption } from "@/Features/Services/Types/serviceRequest.type";

interface ColorSwatchInputProps {
    name: string;
    colors: ColorOption[];
    filamentName?: string;
    value: number | null;
    onChange: (colorId: number) => void;
}

/** Returns true for very light colors that need a border to be visible against a white background. */
function isLightColor(hex: string | null): boolean {
    if (!hex) return false;
    const clean = hex.replace("#", "");
    const r = parseInt(clean.slice(0, 2), 16);
    const g = parseInt(clean.slice(2, 4), 16);
    const b = parseInt(clean.slice(4, 6), 16);
    // Perceived luminance — treat as "light" when > 220
    return (0.299 * r + 0.587 * g + 0.114 * b) > 220;
}

export default function ColorSwatchInput({
    name,
    colors,
    filamentName,
    value,
    onChange,
}: ColorSwatchInputProps) {
    return (
        <Box className="space-y-2">
            {/* Sub-hint: dynamic based on selected filament */}
            {filamentName && (
                <Text as="span" className="block text-xs text-slate-400 pl-0.5">
                    Available colors for {filamentName} (based on current stock)
                </Text>
            )}

            {/* Swatch row */}
            <Box className="flex flex-wrap gap-4">
                {colors.map((color) => {
                    const selected = value === color.id;
                    const light    = isLightColor(color.hex);

                    return (
                        <Box
                            key={color.id}
                            className="flex flex-col items-center gap-1.5"
                        >
                            {/* Swatch button */}
                            <Box
                                as="button"
                                type="button"
                                onClick={() => onChange(color.id)}
                                className={cn(
                                    "w-10 h-10 rounded-full transition-all duration-150 focus:outline-none",
                                    selected
                                        ? "ring-2 ring-offset-2 ring-[#00426D]"
                                        : "hover:ring-2 hover:ring-offset-2 hover:ring-slate-300",
                                    light ? "border border-slate-200" : "",
                                )}
                                style={{
                                    backgroundColor: color.hex ?? "#e5e7eb",
                                }}
                                aria-label={color.name}
                                aria-pressed={selected}
                            />

                            {/* Label */}
                            <Text
                                as="span"
                                className={cn(
                                    "text-[11px] font-medium",
                                    selected ? "text-[#00426D]" : "text-slate-500",
                                )}
                            >
                                {color.name}
                            </Text>
                        </Box>
                    );
                })}
            </Box>

            {/* Hidden input */}
            <Box
                as="input"
                type="hidden"
                name={name}
                value={value ?? ""}
            />
        </Box>
    );
}
