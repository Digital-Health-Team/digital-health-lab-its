import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";

export type ScanningLocationValue = "visit_lab" | "home_visit";

interface ScanningLocationInputProps {
    name: string;
    value: ScanningLocationValue;
    onChange: (value: ScanningLocationValue) => void;
}

const options: {
    value: ScanningLocationValue;
    title: string;
    badge: string;
    badgeVariant: "green" | "amber";
    description: string;
}[] = [
    {
        value: "visit_lab",
        title: "Visit Our Laboratory",
        badge: "No extra charge",
        badgeVariant: "green",
        description:
            "Bring your object to our lab at ITS Surabaya. Our team will handle the scanning on-site with our high-resolution equipment.",
    },
    {
        value: "home_visit",
        title: "Lab Assistant Comes to You",
        badge: "+ Rp 100.000 – 200.000",
        badgeVariant: "amber",
        description:
            "A lab assistant will visit your location with portable scanning equipment. Extra charge varies based on distance (Rp 100.000 – Rp 200.000).",
    },
];

export default function ScanningLocationInput({ name, value, onChange }: ScanningLocationInputProps) {
    return (
        <Box className="space-y-2.5">
            {options.map((opt) => {
                const isSelected = value === opt.value;
                return (
                    <Box
                        key={opt.value}
                        as="label"
                        className={[
                            "flex items-start gap-3 px-4 py-3.5 rounded-xl border cursor-pointer transition-all duration-150",
                            isSelected
                                ? "border-[#00426D] bg-[#00426D]/[0.03] ring-1 ring-[#00426D]"
                                : "border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50/60",
                        ].join(" ")}
                    >
                        {/* Hidden radio input */}
                        <Box
                            as="input"
                            type="radio"
                            name={name}
                            value={opt.value}
                            checked={isSelected}
                            onChange={() => onChange(opt.value)}
                            className="hidden"
                        />

                        {/* Custom radio circle */}
                        <Box className="mt-0.5 shrink-0 w-4 h-4 rounded-full border-2 flex items-center justify-center transition-colors"
                            style={{
                                borderColor: isSelected ? "#00426D" : "#cbd5e1",
                                backgroundColor: "white",
                            }}
                        >
                            {isSelected && (
                                <Box
                                    className="w-2 h-2 rounded-full"
                                    style={{ backgroundColor: "#00426D" }}
                                />
                            )}
                        </Box>

                        {/* Text content */}
                        <Box className="flex-1 min-w-0">
                            <Box className="flex flex-wrap items-center gap-2 mb-1">
                                <Text as="span" className="text-sm font-semibold text-slate-800">
                                    {opt.title}
                                </Text>
                                <Box
                                    className={[
                                        "inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold",
                                        opt.badgeVariant === "green"
                                            ? "bg-emerald-50 text-emerald-700"
                                            : "bg-amber-50 text-amber-700",
                                    ].join(" ")}
                                >
                                    {opt.badge}
                                </Box>
                            </Box>
                            <Text as="span" className="block text-xs text-slate-500 leading-relaxed">
                                {opt.description}
                            </Text>
                        </Box>
                    </Box>
                );
            })}
        </Box>
    );
}
