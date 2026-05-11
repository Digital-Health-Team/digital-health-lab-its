import React from "react";
import { Box } from "@/Core/Components/Common/Box";
import { LandingNavbar, LandingFooter } from "@/Features/Landing/Components";

interface MainLayoutProps {
    children: React.ReactNode;
}

/**
 * Main layout for the landing page.
 * Wraps page content with the sticky Navbar at the top
 * and the SiteFooter at the bottom.
 */
export default function MainLayout({ children }: MainLayoutProps): React.JSX.Element {
    return (
        <Box as="main" className="font-body antialiased overflow-x-hidden">
            <LandingNavbar />
            {children}
            <LandingFooter />
        </Box>
    );
}
