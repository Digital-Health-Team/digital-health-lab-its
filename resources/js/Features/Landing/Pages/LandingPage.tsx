import { Head } from "@inertiajs/react";
import React from "react";
import {
    AboutSection,
    HeroSection,
    OrganizationSection,
    Preloader,
    ServicesSection,
    WisdomSection,
} from "@/Features/Landing/Components";
import MainLayout from "@/Features/Landing/Layouts/MainLayout";

export default function LandingPage(): React.JSX.Element {
    return (
        <React.Fragment>
            <Head title="IDIG Laboratory — Medical Engineering Technology ITS" />

            <Preloader />

            <MainLayout>
                <HeroSection />
                <AboutSection />
                <ServicesSection />
                <WisdomSection />
                <OrganizationSection />
            </MainLayout>
        </React.Fragment>
    );
}
