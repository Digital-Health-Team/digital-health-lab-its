import { DESKTOP_MIN_WIDTH } from "./breakpoints";

export function prefersReducedMotion(): boolean {
    return typeof window !== "undefined" &&
        window.matchMedia("(prefers-reduced-motion: reduce)").matches;
}

export function getCurtainCount(): number {
    return typeof window !== "undefined" && window.innerWidth < DESKTOP_MIN_WIDTH ? 6 : 9;
}
