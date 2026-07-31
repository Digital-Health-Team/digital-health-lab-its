import { create } from "zustand";
import { persist, createJSONStorage } from "zustand/middleware";

// Locale is NOT stored here — it lives server-side (session + users.locale) and arrives
// as an Inertia shared prop, so React, Blade, <html lang> and validation errors agree.
interface UiState {
    sidebarCollapsed: boolean;
    mobileSidebarOpen: boolean;
    toggleSidebar: () => void;
    setMobileSidebar: (open: boolean) => void;
}

export const useUiStore = create<UiState>()(
    persist(
        (set) => ({
            sidebarCollapsed: false,
            mobileSidebarOpen: false,
            toggleSidebar: () =>
                set((state) => ({ sidebarCollapsed: !state.sidebarCollapsed })),
            setMobileSidebar: (open) => set({ mobileSidebarOpen: open }),
        }),
        {
            name: "idig-ui-preferences",
            storage: createJSONStorage(() => localStorage),
            partialize: (state) => ({
                sidebarCollapsed: state.sidebarCollapsed,
            }),
        },
    ),
);
