import { Head } from "@inertiajs/react";
import React from "react";
import {
    AboutSection,
    ArticlesSection,
    CollaborationSection,
    ContactSection,
    CtaSection,
    HeroSection,
    OrganizationSection,
    Preloader,
    ServicesSection,
} from "@/Features/Landing/Components";
import MainLayout from "@/Features/Landing/Layouts/MainLayout";
import { useTranslation } from "@/Core/Hooks/useTranslation";

export default function LandingPage(): React.JSX.Element {
    const { t } = useTranslation();

    return (
        <React.Fragment>
            <Head title={t("IDIG Laboratory — Medical Engineering Technology ITS")} />

            <Preloader />

            <MainLayout>
                <HeroSection />
                <AboutSection />
                <ServicesSection />
                <CollaborationSection />
                <OrganizationSection />
                <CtaSection />
                <ArticlesSection />
                <ContactSection />
            </MainLayout>
        </React.Fragment>
    );
}
