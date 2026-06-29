import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";

interface FormFieldProps {
    label: string;
    description?: string;
    hint?: string;
    error?: string;
    children: React.ReactNode;
}

export default function FormField({ label, description, hint, error, children }: FormFieldProps) {
    return (
        <Box className="space-y-1.5">
            <Box
                as="label"
                className="block text-sm font-semibold text-slate-700"
            >
                {label}
            </Box>

            {description && (
                <Text as="span" className="block text-xs text-slate-400 -mt-0.5">
                    {description}
                </Text>
            )}

            {children}

            {error && (
                <Text as="span" className="block text-xs text-red-500 pl-0.5">
                    {error}
                </Text>
            )}
            {hint && !error && (
                <Text as="span" className="block text-xs text-slate-400 pl-0.5">
                    {hint}
                </Text>
            )}
        </Box>
    );
}
