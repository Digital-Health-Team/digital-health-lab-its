import { Head } from "@inertiajs/react";
import React from "react";
import {
    PameranHero,
    PameranIntro,
    PameranPreloader,
} from "@/Features/Pameran/Components";
import MainLayout from "@/Features/Landing/Layouts/MainLayout";
import { pameranData } from "@/Features/Pameran/Data/pameran.data";

export default function PameranPage(): React.JSX.Element {
    return (
        <React.Fragment>
            <Head title={pameranData.meta.title} />

            <PameranPreloader />

            <MainLayout>
                <PameranHero />
                <PameranIntro />
            </MainLayout>
        </React.Fragment>
    );
}
