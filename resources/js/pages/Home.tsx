import { Head } from "@inertiajs/react";
import Preloader from "@/components/landing/Preloader";
import LandingNavbar from "@/components/landing/Navbar";
import HeroSection from "@/components/landing/HeroSection";
import AboutSection from "@/components/landing/AboutSection";
import ServiceCards from "@/components/landing/ServiceCards";
import SharingWisdom from "@/components/landing/SharingWisdom";
import OrgChart from "@/components/landing/OrgChart";
import SiteFooter from "@/components/landing/SiteFooter";
import React from "react";
import { Box } from "@/Core/Components/Common/Box";

export default function HomePage(): React.JSX.Element {
    return (
        <React.Fragment>
            <Head title="IDIG Laboratory — Medical Engineering Technology ITS" />

            <Preloader />

            <Box as="main" className="font-body antialiased overflow-x-hidden">
                <LandingNavbar />
                <HeroSection />
                <AboutSection />
                <ServiceCards />
                <SharingWisdom />
                <OrgChart />
                <SiteFooter />
            </Box>
        </React.Fragment>
    );
}
