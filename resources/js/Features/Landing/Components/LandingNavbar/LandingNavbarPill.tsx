import { forwardRef } from "react";

interface LandingNavbarPillProps {
    onToggle: () => void;
    isOpen: boolean;
}

const LandingNavbarPill = forwardRef<HTMLButtonElement, LandingNavbarPillProps>(
    ({ onToggle, isOpen }, ref) => (
        <button
            ref={ref}
            onClick={onToggle}
            aria-expanded={isOpen}
            aria-haspopup="dialog"
            aria-label={isOpen ? "Close navigation menu" : "Open navigation menu"}
            className="pointer-events-auto flex items-center gap-3 px-5 py-3 text-white/90 font-body font-medium text-sm cursor-pointer focus:outline-none"
        >
            {/* Three-line hamburger — middle line shorter for visual weight */}
            <span className="flex flex-col gap-[5px] w-[18px] shrink-0" aria-hidden="true">
                <span className="block h-px w-full bg-current rounded-full transition-transform duration-300" />
                <span className="block h-px w-3 bg-current rounded-full transition-transform duration-300" />
                <span className="block h-px w-full bg-current rounded-full transition-transform duration-300" />
            </span>
            <span className="tracking-wide text-sm">Menu</span>
        </button>
    ),
);

LandingNavbarPill.displayName = "LandingNavbarPill";
export default LandingNavbarPill;
