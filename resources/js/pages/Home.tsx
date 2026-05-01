import { Head } from "@inertiajs/react";
import LandingNavbar from "@/components/landing/Navbar";
import HeroSection from "@/components/landing/HeroSection";
import AboutSection from "@/components/landing/AboutSection";
import ServiceCards from "@/components/landing/ServiceCards";
import SharingWisdom from "@/components/landing/SharingWisdom";
import InnovationCTA from "@/components/landing/InnovationCTA";
import OrgChart from "@/components/landing/OrgChart";
import SiteFooter from "@/components/landing/SiteFooter";

export default function Home() {
    return (
        <>
            <Head title="IDIG Laboratory — Medical Engineering Technology ITS" />

            <main className="font-body antialiased overflow-x-hidden">
                <LandingNavbar />
                <HeroSection />
                <AboutSection />
                <ServiceCards />
                <SharingWisdom />
                <OrgChart />
                <SiteFooter />
            </main>
        </>
    );
}
