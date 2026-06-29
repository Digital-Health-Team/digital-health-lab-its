import type { Auth } from "@/Core/Models/auth.model";

export interface SharedFlash {
    success?: string | null;
    error?: string | null;
    showWelcome?: boolean;
}

declare module "@inertiajs/core" {
    export interface InertiaConfig {
        sharedPageProps: {
            name: string;
            auth: Auth;
            flash?: SharedFlash;
            sidebarOpen: boolean;
            [key: string]: unknown;
        };
    }
}
