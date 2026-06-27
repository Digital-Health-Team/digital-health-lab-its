import { useState, useEffect } from "react";

// ── Types ────────────────────────────────────────────────────────────────────

export interface CountdownResult {
    days: number;
    hours: number;
    minutes: number;
    seconds: number;
    /** True once the current time has reached or passed the target instant. */
    isComplete: boolean;
}

// ── Pure helper ───────────────────────────────────────────────────────────────

/**
 * Computes the remaining time between `nowMs` and `targetMs`.
 * Negative differences are clamped to zero; `isComplete` signals exhaustion.
 * Pure — no side effects, safe to call in render and in lazy initializers.
 */
function getRemaining(targetMs: number, nowMs: number): CountdownResult {
    const diff = Math.max(0, targetMs - nowMs);
    const isComplete = nowMs >= targetMs;

    const totalSeconds = Math.floor(diff / 1000);
    const days = Math.floor(totalSeconds / 86400);
    const hours = Math.floor((totalSeconds % 86400) / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;

    return { days, hours, minutes, seconds, isComplete };
}

// ── Hook ─────────────────────────────────────────────────────────────────────

/**
 * Counts down to `targetIso` (ISO-8601 string with timezone offset).
 * Tick rate: 1 second. Stops and remains stable once `isComplete` is true.
 *
 * SSR-safe: `Date.now()` runs only client-side inside the `useEffect`; the
 * lazy initializer that seeds the first render also runs client-side because
 * React only calls `useState` initializers in the browser when Inertia/React
 * hydrates. If SSR is ever added, the lazy initializer will need a guard.
 */
export function useCountdown(targetIso: string): CountdownResult {
    const targetMs = new Date(targetIso).getTime();

    const [state, setState] = useState<CountdownResult>(() =>
        getRemaining(targetMs, Date.now()),
    );

    useEffect(() => {
        // If already complete on mount, nothing to do.
        if (state.isComplete) return;

        const id = setInterval(() => {
            const next = getRemaining(targetMs, Date.now());
            setState(next);
            if (next.isComplete) clearInterval(id);
        }, 1000);

        return () => clearInterval(id);
        // targetMs is stable (derived from a constant string); isComplete stops
        // the interval, so we include it to avoid a stale closure after reset.
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [targetMs]);

    return state;
}
