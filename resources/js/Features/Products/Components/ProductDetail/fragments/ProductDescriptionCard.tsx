import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";

interface ProductDescriptionCardProps {
    lead: string;
    body: string;
    tags: string[];
}

export default function ProductDescriptionCard({
    lead,
    body,
    tags,
}: ProductDescriptionCardProps) {
    return (
        <Box className="rounded-2xl border border-slate-200 bg-white p-4">
            {/* Section label */}
            <Text className="text-[10px] font-semibold tracking-widest uppercase text-slate-400 mb-3">
                Description
            </Text>

            {/* Content */}
            <Text className="text-sm text-slate-700 leading-relaxed">
                <Text as="span" className="font-bold text-slate-900">
                    {lead}
                </Text>{" "}
                {body}
            </Text>

            {/* Tag pills */}
            <Box className="flex flex-wrap gap-2 mt-4">
                {tags.map((tag) => (
                    <Box
                        key={tag}
                        className="px-3 py-1 rounded-full border border-slate-200 text-xs text-slate-600 bg-white"
                    >
                        {tag}
                    </Box>
                ))}
            </Box>
        </Box>
    );
}
