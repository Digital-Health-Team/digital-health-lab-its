import React from "react";

/**
 * 1. 3D Designs Icon
 * Description: Isometric 3D cube with perspective projection lines and vertex nodes.
 */
export const ThreeDDesignsIcon = (props: React.SVGProps<SVGSVGElement>) => (
    <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
        {...props}
    >
        {/* Isometric Cube faces */}
        <path d="M12 3L3 7.5L12 12L21 7.5L12 3Z" />
        <path d="M3 7.5V16.5L12 21V12" />
        <path d="M21 7.5V16.5L12 21" />
        {/* Helper layout grid lines (faint projection) */}
        <path d="M12 12L12 3" strokeDasharray="2 2" strokeWidth="1" opacity="0.6" />
        <path d="M12 12L3 7.5" strokeDasharray="2 2" strokeWidth="1" opacity="0.6" />
        <path d="M12 12L21 7.5" strokeDasharray="2 2" strokeWidth="1" opacity="0.6" />
        {/* Vertex dots */}
        <circle cx="12" cy="3" r="1.2" fill="currentColor" />
        <circle cx="3" cy="7.5" r="1.2" fill="currentColor" />
        <circle cx="21" cy="7.5" r="1.2" fill="currentColor" />
        <circle cx="12" cy="12" r="1.2" fill="currentColor" />
        <circle cx="12" cy="21" r="1.2" fill="currentColor" />
        <circle cx="3" cy="16.5" r="1.2" fill="currentColor" />
        <circle cx="21" cy="16.5" r="1.2" fill="currentColor" />
    </svg>
);

/**
 * 2. Prosthetics Icon
 * Description: Futuristic robotic hand profile showing segmented fingers and joints.
 */
export const ProstheticsIcon = (props: React.SVGProps<SVGSVGElement>) => (
    <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
        {...props}
    >
        {/* Wrist base */}
        <path d="M7 21h10M9 21v-4h6v4" />
        {/* Palm */}
        <path d="M8 17l1.5-6.5h5L16 17H8z" />
        {/* Cybernetic Fingers */}
        <path d="M7.5 13.5l-2.5-1.5v-1.5l1.5.8" /> {/* Thumb */}
        <path d="M10 10.5V5.5c0-.5.5-.5.5 0v5" />   {/* Index */}
        <path d="M12 10.5V4c0-.5.5-.5.5 0v6.5" />    {/* Middle */}
        <path d="M14 10.5V5c0-.5.5-.5.5 0v5.5" />    {/* Ring */}
        <path d="M16 10.5v3.5" />                     {/* Pinky */}
        {/* Joint pins */}
        <circle cx="10.25" cy="5.5" r="0.75" fill="currentColor" />
        <circle cx="12.25" cy="4" r="0.75" fill="currentColor" />
        <circle cx="14.25" cy="5" r="0.75" fill="currentColor" />
    </svg>
);

/**
 * 3. Aid Bands Icon
 * Description: Plaster bandage with a central medical sensor and diagnostic signals.
 */
export const AidBandsIcon = (props: React.SVGProps<SVGSVGElement>) => (
    <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
        {...props}
    >
        {/* Plaster band (diagonal) */}
        <path d="M5.5 18.5a3.5 3.5 0 0 1 0-5l8.5-8.5a3.5 3.5 0 0 1 5 5l-8.5 8.5a3.5 3.5 0 0 1-5 0z" />
        {/* Center pad */}
        <path d="M10.5 13.5l3-3" strokeWidth="3" opacity="0.4" />
        {/* Medical Cross sensor */}
        <path d="M12 10v4M10 12h4" strokeWidth="1.5" />
        {/* Wireless diagnostic arcs */}
        <path d="M3.5 14c-.6-.7-1-1.6-1-2.5s.4-1.8 1-2.5" />
        <path d="M20.5 10c.6.7 1 1.6 1 2.5s-.4 1.8-1 2.5" />
    </svg>
);

/**
 * 4. Educational Mannequin Icon
 * Description: Anatomical bust mannequin with tech lines showing organ/brain areas.
 */
export const EducationalMannequinIcon = (props: React.SVGProps<SVGSVGElement>) => (
    <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
        {...props}
    >
        {/* Base stand */}
        <path d="M9 22h6M12 18v4" />
        {/* Torso */}
        <path d="M6 18c0-3.5 2-4.5 6-4.5s6 1 6 4.5v-1.5H6v1.5z" />
        {/* Neck */}
        <path d="M10.5 11v2.5h3V11" />
        {/* Head */}
        <path d="M12 3a4 4 0 0 1 4 4c0 1.8-1 3-2.5 4l-1.5 1h-2l-1.5-1C7 10 6 8.8 6 7a4 4 0 0 1 4-4z" />
        {/* Heart/Core node */}
        <circle cx="12" cy="15.5" r="1" fill="currentColor" />
        {/* Brain node paths */}
        <path d="M12 5v2.5M10.5 6.5h3" strokeWidth="1.2" />
        <circle cx="12" cy="4.5" r="0.75" fill="currentColor" />
        <circle cx="10.5" cy="6.5" r="0.75" fill="currentColor" />
        <circle cx="13.5" cy="6.5" r="0.75" fill="currentColor" />
    </svg>
);

/**
 * 5. Papers Icon
 * Description: Technical/Scientific paper document with a data chart and a medical symbol.
 */
export const PapersIcon = (props: React.SVGProps<SVGSVGElement>) => (
    <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
        {...props}
    >
        {/* Document sheet */}
        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7l-5-5z" />
        {/* Folded edge */}
        <path d="M14 2v5h5" />
        {/* Content lines */}
        <path d="M7 11h10M7 15h5" />
        {/* Miniature science chart */}
        <circle cx="15.5" cy="15.5" r="1.5" />
        <path d="M17 15.5h2" strokeWidth="1" />
        {/* Medical header sign */}
        <path d="M8 6h2M9 5v2" strokeWidth="1.5" />
    </svg>
);

/**
 * 6. Journals Icon
 * Description: Open academic journal book featuring a DNA helix across pages.
 */
export const JournalsIcon = (props: React.SVGProps<SVGSVGElement>) => (
    <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
        {...props}
    >
        {/* Open book */}
        <path d="M12 20c-3-2-6-2-10-2V6c4 0 7 0 10 2" />
        <path d="M12 20c3-2 6-2 10-2V6c-4 0-7 0-10 2" />
        <path d="M12 8v12" />
        {/* DNA helix overlay */}
        <path d="M5 11c2-2.5 4-2.5 7 0s5 2.5 7 0" opacity="0.85" />
        <path d="M5 14c2 2.5 4 2.5 7 0s5-2.5 7 0" opacity="0.85" />
        {/* Rungs */}
        <path d="M7 11.5v1M12 12v0M17 11.5v1" strokeWidth="1.2" />
    </svg>
);

/**
 * 7. Projects Icon
 * Description: Tabbed project folder containing a mechanical gear or parts assembly.
 */
export const ProjectsIcon = (props: React.SVGProps<SVGSVGElement>) => (
    <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
        {...props}
    >
        {/* Folder frame */}
        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 2h9a2 2 0 0 1 2 2v12z" />
        {/* Rising mechanical element */}
        <path d="M12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" strokeDasharray="1 1" />
        {/* Target brackets */}
        <path d="M9 10h1v1M15 10h-1v1M9 14h1v-1M15 14h-1v-1" />
        {/* Gear center */}
        <circle cx="12" cy="12" r="1" fill="currentColor" />
    </svg>
);

/**
 * 8. Services Icon
 * Description: High-tech combination of a mechanical gear and a 3D printing nozzle tip.
 */
export const ServicesIcon = (props: React.SVGProps<SVGSVGElement>) => (
    <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
        {...props}
    >
        {/* Gear shape */}
        <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.38a2 2 0 0 0-.73-2.73l-.15-.1a2 2 0 0 1-1-1.72v-.51a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
        {/* Wrench crossing */}
        <path d="M15 9l-6 6" />
        <path d="M7.5 16.5L6 18l-1.5-1.5 1.5-1.5 1.5 1.5z" strokeWidth="1" fill="currentColor" />
        {/* 3D Print Nozzle point */}
        <path d="M10 4.5h4L13 7h-2L10 4.5z" fill="currentColor" />
    </svg>
);

/**
 * 9. Training Icon
 * Description: Certification shield enclosing a graduation cap.
 */
export const TrainingIcon = (props: React.SVGProps<SVGSVGElement>) => (
    <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
        {...props}
    >
        {/* Shield */}
        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
        {/* Graduation cap */}
        <path d="M12 7l5 2.5-5 2.5-5-2.5L12 7z" />
        <path d="M8.5 11v2c0 1 1.5 1.5 3.5 1.5s3.5-.5 3.5-1.5v-2" />
        <path d="M15.5 9.5v3" />
        {/* Small sparkle dots */}
        <circle cx="12" cy="18" r="0.75" fill="currentColor" />
    </svg>
);

/**
 * 10. Events Icon
 * Description: Calendar framework with a heartbeat ECG signal indicating active events.
 */
export const EventsIcon = (props: React.SVGProps<SVGSVGElement>) => (
    <svg
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        strokeWidth="2"
        strokeLinecap="round"
        strokeLinejoin="round"
        {...props}
    >
        {/* Calendar border */}
        <path d="M19 4H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2z" />
        {/* Loops */}
        <path d="M16 2v4M8 2v4" />
        {/* Binder bar */}
        <path d="M3 10h18" />
        {/* ECG pulse signal */}
        <path d="M6 15.5h2.5l1.25-3 1.25 4.5 1.25-4 1.25 2.5H18" strokeWidth="1.8" />
    </svg>
);
