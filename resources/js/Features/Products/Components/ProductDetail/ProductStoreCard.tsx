import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { type ProductStore } from "@/Features/Products/Types/productDetail.type";

interface ProductStoreCardProps {
    store: ProductStore;
}

export default function ProductStoreCard({ store }: ProductStoreCardProps) {
    return (
        <Box className="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-4">
            {/* Avatar initial */}
            <Box className="h-10 w-10 rounded-full bg-primary-800 flex items-center justify-center shrink-0">
                <Text as="span" className="text-white font-bold text-sm select-none">
                    {store.initial}
                </Text>
            </Box>

            {/* Store info */}
            <Box className="flex-1 min-w-0">
                <Text className="text-sm font-semibold text-slate-800 truncate">
                    {store.name}
                </Text>
                <Text className="text-xs text-slate-400">
                    {store.type} · {store.location}
                </Text>
            </Box>

            {/* Follow button */}
            <button
                type="button"
                className="shrink-0 px-4 py-1.5 rounded-full border border-primary-700 text-primary-700 text-xs font-semibold hover:bg-primary-700 hover:text-white transition-colors cursor-pointer"
            >
                Follow
            </button>
        </Box>
    );
}
