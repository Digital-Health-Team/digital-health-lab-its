import { useState } from "react";
import { Heart } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { cn } from "@/Core/Utils/utils";
import { type ProductGalleryImage } from "@/Features/Products/Types/productDetail.type";

interface ProductGalleryProps {
    images: ProductGalleryImage[];
    badges: string[];
    caption: {
        brand: string;
        name: string;
    };
}

const BADGE_STYLES: Record<string, string> = {
    NEW: "bg-secondary-400 text-white",
    "GRADE A": "bg-accent-400 text-primary-900",
};

function GradientImage({
    image,
    className,
}: {
    image: ProductGalleryImage;
    className?: string;
}) {
    if (image.src) {
        return (
            <Box
                className={className}
                style={{
                    backgroundImage: `url(${image.src})`,
                    backgroundSize: "cover",
                    backgroundPosition: "center",
                }}
            />
        );
    }
    return (
        <Box
            className={cn(
                className,
                image.colorClass ?? "bg-gradient-to-br from-slate-300 to-slate-600",
            )}
        />
    );
}

export default function ProductGallery({ images, badges, caption }: ProductGalleryProps) {
    const [activeIndex, setActiveIndex] = useState(0);

    const mainImage = images[activeIndex] ?? images[0];

    return (
        <Box className="flex flex-col gap-3">
            {/* ── Main image ─────────────────────────────────── */}
            <Box className="relative w-full aspect-square rounded-2xl overflow-hidden">
                <GradientImage image={mainImage} className="absolute inset-0 w-full h-full" />

                {/* Badges — top left */}
                <Box className="absolute top-3 left-3 flex items-center gap-1.5 z-10">
                    {badges.map((badge) => (
                        <Box
                            key={badge}
                            className={cn(
                                "text-[10px] font-bold px-2 py-0.5 rounded",
                                BADGE_STYLES[badge] ??
                                    "bg-slate-700 text-white",
                            )}
                        >
                            {badge}
                        </Box>
                    ))}
                </Box>

                {/* Wishlist button — top right */}
                <Box className="absolute top-3 right-3 z-10">
                    <Box className="h-7 w-7 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center shadow-sm cursor-pointer hover:bg-white transition-colors">
                        <Heart className="h-3.5 w-3.5 text-slate-500" />
                    </Box>
                </Box>

                {/* Caption overlay — bottom left */}
                <Box className="absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-black/60 to-transparent z-10">
                    <Text className="text-[10px] text-white/70 leading-tight">
                        {caption.brand}
                    </Text>
                    <Text className="text-xs font-semibold text-white leading-tight">
                        {caption.name}
                    </Text>
                </Box>
            </Box>

            {/* ── Thumbnail strip ─────────────────────────────── */}
            <Box className="flex gap-2">
                {images.map((img, i) => (
                    <button
                        key={i}
                        type="button"
                        onClick={() => setActiveIndex(i)}
                        className={cn(
                            "relative flex-1 aspect-square rounded-lg overflow-hidden transition-all",
                            activeIndex === i
                                ? "ring-2 ring-primary-700 ring-offset-1"
                                : "opacity-60 hover:opacity-90",
                        )}
                        aria-label={img.alt}
                    >
                        <GradientImage image={img} className="absolute inset-0 w-full h-full" />
                    </button>
                ))}
            </Box>
        </Box>
    );
}
