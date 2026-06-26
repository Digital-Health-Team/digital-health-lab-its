import { Box } from "@/Core/Components/Common/Box";
import { cn } from "@/Core/Utils/utils";

interface PresetInputProps {
    name: string;
    placeholder?: string;
    unit?: string;
    presets: string[];
    value: string;
    onChange: (value: string) => void;
}

export default function PresetInput({
    name,
    placeholder,
    unit,
    presets,
    value,
    onChange,
}: PresetInputProps) {
    return (
        <Box className="flex flex-wrap items-center gap-2">
            {/* Free-text input (with optional unit badge) */}
            <Box className="relative flex-1 min-w-[120px] max-w-xs">
                <Box
                    as="input"
                    type="text"
                    name={name}
                    value={value}
                    placeholder={placeholder}
                    onChange={(e: React.ChangeEvent<HTMLInputElement>) =>
                        onChange(e.target.value)
                    }
                    className="h-11 w-full rounded-xl border border-slate-200 pl-3.5 pr-12 text-sm text-slate-700 placeholder-slate-400 bg-white focus:outline-none focus:border-[#00426D] focus:ring-1 focus:ring-[#00426D] transition-colors duration-150"
                />
                {unit && (
                    <Box className="absolute right-0 inset-y-0 flex items-center px-3 border-l border-slate-200 pointer-events-none">
                        <Box
                            as="span"
                            className="text-xs font-medium text-slate-400"
                        >
                            {unit}
                        </Box>
                    </Box>
                )}
            </Box>

            {/* Preset pill buttons */}
            {presets.map((preset) => {
                const active = value === preset;
                return (
                    <Box
                        key={preset}
                        as="button"
                        type="button"
                        onClick={() => onChange(preset)}
                        className={cn(
                            "h-11 px-4 rounded-xl border text-sm font-semibold transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-[#00426D]/30",
                            active
                                ? "border-[#00426D] bg-[#00426D]/8 text-[#00426D]"
                                : "border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:bg-slate-50",
                        )}
                    >
                        {preset}
                    </Box>
                );
            })}
        </Box>
    );
}
