import { Head } from "@inertiajs/react";
import React from "react";
import {
    PameranCountdown,
    PameranPreloader,
} from "@/Features/Pameran/Components";
import { pameranData } from "@/Features/Pameran/Data/pameran.data";

/**
 * Standalone full-screen countdown page for InnovaTech 2026.
 * No MainLayout — no navbar or footer. The preloader plays once per hard load,
 * then the countdown fills the viewport until the event opens on 2 July 2026.
 */
export default function PameranPage(): React.JSX.Element {
    return (
        <React.Fragment>
            <Head title={pameranData.meta.title}>
                <meta
                    name="description"
                    content={pameranData.meta.description}
                />
            </Head>

            <PameranPreloader />

            {/*
             * Dark ground state ensures no white flash behind the preloader
             * or during hydration. bg-[#031026] mirrors Institute Midnight.
             */}
            <React.Fragment>
                <style>{`body { background: #031026; }`}</style>
                <PameranCountdown />
            </React.Fragment>
        </React.Fragment>
    );
}
