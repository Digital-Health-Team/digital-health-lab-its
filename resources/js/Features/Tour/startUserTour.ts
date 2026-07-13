import { driver, type DriveStep } from "driver.js";
import "driver.js/dist/driver.css";
import { userTours, type TourLang } from "./tourSteps";

const seenKey = (role: string) => `idig-tour-seen:${role}`;

/**
 * Launch the guided tour for a React-dashboard role (`mahasiswa` / `user_publik`).
 *
 * Without `force`, the tour only runs the first time for that role (tracked in
 * localStorage) — used for the auto-start on first dashboard visit. The navbar
 * "Tutorial" button passes `force: true` to always replay.
 */
export function startUserTour(
    role: string,
    lang: TourLang,
    { force = false }: { force?: boolean } = {},
): void {
    if (!role) return;

    if (!force) {
        if (localStorage.getItem(seenKey(role))) return;
        localStorage.setItem(seenKey(role), "1");
    }

    const raw = userTours[role];
    if (!raw) return;

    const isVisible = (selector: string): boolean => {
        const el = document.querySelector(selector);
        return !!el && el.getClientRects().length > 0;
    };

    const steps: DriveStep[] = raw
        .filter((s) => !s.element || isVisible(s.element))
        .map((s) => {
            const copy = s[lang] ?? s.id;
            const popover = {
                title: copy.title,
                description: copy.description,
                side: s.side ?? "right",
                align: s.align ?? "start",
            } as DriveStep["popover"];
            return s.element ? { element: s.element, popover } : { popover };
        });

    if (!steps.length) return;

    driver({
        showProgress: true,
        allowClose: true,
        overlayColor: "rgba(2, 6, 23, 0.6)",
        popoverClass: "idig-tour",
        stagePadding: 6,
        stageRadius: 10,
        nextBtnText: lang === "en" ? "Next" : "Lanjut",
        prevBtnText: lang === "en" ? "Back" : "Kembali",
        doneBtnText: lang === "en" ? "Done" : "Selesai",
        progressText: lang === "en" ? "{{current}} of {{total}}" : "{{current}} dari {{total}}",
        steps,
    }).drive();
}
