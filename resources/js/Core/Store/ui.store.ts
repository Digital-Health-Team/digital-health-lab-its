import { create } from "zustand";
import { persist, createJSONStorage } from "zustand/middleware";

interface UiState {
    sidebarCollapsed: boolean;
    mobileSidebarOpen: boolean;
    language: "en" | "id";
    toggleSidebar: () => void;
    setMobileSidebar: (open: boolean) => void;
    setLanguage: (lang: "en" | "id") => void;
}

export const useUiStore = create<UiState>()(
    persist(
        (set) => ({
            sidebarCollapsed: false,
            mobileSidebarOpen: false,
            language: "en",
            toggleSidebar: () =>
                set((state) => ({ sidebarCollapsed: !state.sidebarCollapsed })),
            setMobileSidebar: (open) => set({ mobileSidebarOpen: open }),
            setLanguage: (lang) => set({ language: lang }),
        }),
        {
            name: "idig-ui-preferences",
            storage: createJSONStorage(() => localStorage),
            partialize: (state) => ({
                sidebarCollapsed: state.sidebarCollapsed,
                language: state.language,
            }),
        },
    ),
);
