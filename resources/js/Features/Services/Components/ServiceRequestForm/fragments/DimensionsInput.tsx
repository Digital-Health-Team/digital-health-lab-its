import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { type DimensionsAxis } from "@/Features/Services/Types/serviceRequest.type";

interface DimensionsInputProps {
    name: string;
    unit: string;
    axes: DimensionsAxis[];
    value: Record<string, string>;
    onChange: (axisName: string, axisValue: string) => void;
}

export default function DimensionsInput({
    name,
    unit,
    axes,
    value,
    onChange,
}: DimensionsInputProps) {
    return (
        <Box className="grid grid-cols-1 sm:grid-cols-3 gap-3">
            {axes.map((axis) => (
                <Box key={axis.name} className="space-y-1">
                    {/* Axis label */}
                    <Text
                        as="span"
                        className="block text-[11px] font-semibold text-slate-400 tracking-wide uppercase"
                    >
                        {axis.label}
                    </Text>

                    {/* Input with unit suffix */}
                    <Box className="relative">
                        <Box
                            as="input"
                            type="number"
                            name={`${name}_${axis.name}`}
                            value={value[axis.name] ?? ""}
                            placeholder="0.0"
                            min="0"
                            step="0.1"
                            onChange={(e: React.ChangeEvent<HTMLInputElement>) =>
                                onChange(axis.name, e.target.value)
                            }
                            className="h-11 w-full rounded-xl border border-slate-200 pl-3.5 pr-12 text-sm text-slate-700 placeholder-slate-400 bg-white focus:outline-none focus:border-[#00426D] focus:ring-1 focus:ring-[#00426D] transition-colors duration-150"
                        />
                        <Box className="absolute right-0 inset-y-0 flex items-center px-3 border-l border-slate-200 pointer-events-none rounded-r-xl">
                            <Text
                                as="span"
                                className="text-xs font-medium text-slate-400"
                            >
                                {unit}
                            </Text>
                        </Box>
                    </Box>
                </Box>
            ))}
        </Box>
    );
}
