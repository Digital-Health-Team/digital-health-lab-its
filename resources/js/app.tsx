import { createInertiaApp } from "@inertiajs/react";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createRoot, hydrateRoot } from "react-dom/client";
import TopProgressBar from "@/Core/Components/Shared/TopProgressBar/TopProgressBar";
import "./echo";

const appName = import.meta.env.VITE_APP_NAME || "ITS Medical Technology";

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./${name}.tsx`,
            import.meta.glob("./**/**/*.tsx"),
        ) as any,
    // Disable Inertia's built-in progress bar — we use our custom TopProgressBar instead.
    progress: false,
    setup({ el, App, props }) {
        if (import.meta.env.SSR) {
            hydrateRoot(
                el!,
                <>
                    <TopProgressBar />
                    <App {...props} />
                </>,
            );

            return;
        }

        createRoot(el!).render(
            <>
                <TopProgressBar />
                <App {...props} />
            </>,
        );
    },
});
