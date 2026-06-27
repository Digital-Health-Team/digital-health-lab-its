import { cn } from "@/Core/Utils/utils";

interface SidebarBrandProps {
    collapsed: boolean;
}

export default function SidebarBrand({ collapsed }: SidebarBrandProps) {
    return (
        <div
            className={cn(
                "flex items-center justify-center px-4 py-5 border-b shrink-0",
                collapsed ? "px-2" : "px-4",
            )}
            style={{ borderColor: "rgba(255,255,255,0.12)" }}
        >
            <div className="relative flex items-center justify-center">
                {/* Radial bloom glow — same technique as hero section */}
                <div
                    aria-hidden
                    className="absolute pointer-events-none"
                    style={{
                        inset: "-20px -28px",
                        background:
                            "radial-gradient(ellipse 65% 55% at 50% 54%, rgba(0,168,181,0.28) 0%, rgba(0,168,181,0.08) 55%, transparent 80%)",
                        filter: "blur(12px)",
                    }}
                />
                <img
                    src="/assets/images/logo_idig_htech_white.png"
                    alt="IDIG Lab"
                    draggable={false}
                    className={cn(
                        "relative object-contain transition-all duration-220",
                        collapsed ? "h-8 w-auto" : "h-10 w-auto",
                    )}
                    style={{
                        filter:
                            "drop-shadow(0 0 8px rgba(0,168,181,0.70)) drop-shadow(0 0 24px rgba(0,168,181,0.30))",
                    }}
                />
            </div>
        </div>
    );
}
