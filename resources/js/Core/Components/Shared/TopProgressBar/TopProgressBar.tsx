import { useRef } from "react";
import { Box } from "@/Core/Components/Common/Box";
import { useNavigationProgress } from "@/Core/Hooks/useNavigationProgress";

/**
 * Brand-themed top progress bar that plays during Inertia router navigations.
 *
 * Mount once globally (in app.tsx) — it persists across page transitions and
 * is invisible at rest (opacity: 0). Only becomes visible when a navigation
 * starts, then fades out once it completes.
 *
 * Brand colours match the Preloader fill bar: #FFC72C (yellow) with a matching glow.
 */
export default function TopProgressBar() {
    const { barRef, wrapperRef } = useNavigationProgress();

    return (
        /* Fixed wrapper — always in the DOM, opacity driven by GSAP */
        <Box
            ref={wrapperRef as React.RefObject<HTMLDivElement>}
            className="fixed top-0 inset-x-0 z-[10000] h-[3px] pointer-events-none"
            style={{ opacity: 0 }}
            aria-hidden
        >
            {/* The bar itself — scaleX driven from 0 → 1 via GSAP, origin: left */}
            <Box
                ref={barRef as React.RefObject<HTMLDivElement>}
                className="h-full w-full bg-[#FFC72C]"
                style={{
                    scaleX: 0,
                    transformOrigin: "left center",
                    boxShadow: "0 0 10px rgba(255, 199, 44, 0.8), 0 0 4px rgba(255, 199, 44, 0.5)",
                    willChange: "transform",
                }}
            />
        </Box>
    );
}
