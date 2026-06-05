import { cn } from "@/Core/Utils/utils";

interface PillToggleOption {
    value: string;
    label: string;
}

interface PillToggleProps {
    options: [PillToggleOption, PillToggleOption];
    value: string;
    onChange: (value: string) => void;
    className?: string;
}

export default function PillToggle({ options, value, onChange, className }: PillToggleProps) {
    return (
        <div
            role="group"
            className={cn("inline-flex items-center p-1 bg-slate-100 rounded-full gap-0.5", className)}
        >
            {options.map((option) => (
                <button
                    key={option.value}
                    type="button"
                    onClick={() => onChange(option.value)}
                    aria-pressed={value === option.value}
                    className={cn(
                        "px-4 py-1.5 rounded-full text-xs font-semibold transition-all duration-200",
                        value === option.value
                            ? "bg-primary-700 text-white shadow-sm"
                            : "text-slate-600 hover:text-slate-800",
                    )}
                >
                    {option.label}
                </button>
            ))}
        </div>
    );
}
