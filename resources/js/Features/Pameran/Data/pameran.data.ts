/**
 * Every copy string here is an English source string, which is also the t() key
 * the components resolve it through — Indonesian lives in lang/id.json.
 */
export const pameranData = {
    meta: {
        title: "InnovaTech 2026 Countdown — IDIG Laboratory",
        description:
            "Counting down to the InnovaTech 2026 exhibition — medical technology innovation by ITS students, 2 July 2026.",
    },
    hero: {
        preTitle: "Institut Teknologi Sepuluh Nopember",
        title: "InnovaTech 2026",
        subtitle: "Medical Technology Innovation Exhibition",
        description:
            "Discover innovative student work in medical technology and biomedical engineering — real solutions for the health challenges ahead.",
        ctaText: "Explore the Work",
        ctaHref: "#pameran-intro",
        date: "IDIG Medical Technology Laboratory · ITS · 2026",
    },

    /**
     * Event date/time for the countdown timer.
     * Target: 2 July 2026, 09:00 WIB (Asia/Jakarta, UTC+7).
     * The ISO string carries the +07:00 offset so the countdown is
     * timezone-correct for all viewers regardless of their local clock.
     */
    event: {
        dateISO: "2026-07-02T09:00:00+07:00",
        /**
         * Zone the date/time is stamped in. The countdown formats dateISO against
         * this rather than carrying pre-formatted labels, which could only ever be
         * one language — see PameranCountdown.
         */
        timeZone: "Asia/Jakarta",
        timeZoneLabel: "WIB",
    },

    /** Copy strings for the countdown page and its live state. */
    countdown: {
        units: {
            days: "Days",
            hours: "Hours",
            minutes: "Minutes",
            seconds: "Seconds",
        },
        live: {
            badge: "The Exhibition Is Open",
            ctaText: "Explore the Work",
        },
        backLabel: "Back to Home",
    },

    /**
     * Cinematic spotlight reel — photos shown one at a time during the preloader.
     * PLACEHOLDER: swap every entry for real InnovaTech 2026 photos + captions before launch.
     * Local images (first 4) are used for the spotlight; keep the full list for future use.
     * Mix: local hero photos (always available offline) + Unsplash IDs validated in this repo.
     */
    reelImages: [
        // ── Local (spotlight uses these first — always offline-safe) ──
        {
            src: "/assets/images/hero.png",
            alt: "IDIG laboratory space",
            title: "Medical Technology Laboratory",
        },
        {
            src: "/assets/images/hero_2.jpg",
            alt: "Medical technology prototype",
            title: "Biomedical Device Prototypes",
        },
        {
            src: "/assets/images/hero_3.jpg",
            alt: "ITS research laboratory",
            title: "Instrumentation Engineering Research",
        },
        {
            src: "/assets/images/hero_4.jpg",
            alt: "Engineering innovation",
            title: "Applied Technology Innovation",
        },
        // ── Unsplash — reuse photo IDs already valid in this repo (lab/medical/3D-print/research) ──
        {
            src: "https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?auto=format&fit=crop&q=80&w=600&h=400",
            alt: "3D printing precision fabrication",
            title: "Precision 3D-Printing Fabrication",
        },
        {
            src: "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&q=80&w=600&h=400",
            alt: "Engineer at workstation",
            title: "Medical Device Engineering",
        },
        {
            src: "https://images.unsplash.com/photo-1481627834876-b7833e8f5570?auto=format&fit=crop&q=80&w=600&h=400",
            alt: "Research documentation and publication",
            title: "Scientific Research Documentation",
        },
        {
            src: "https://images.unsplash.com/photo-1576086213369-97a306d36557?auto=format&fit=crop&q=80&w=600&h=400",
            alt: "Medical laboratory work",
            title: "Clinical Analysis Laboratory",
        },
        {
            src: "https://images.unsplash.com/photo-1559757148-5c350d0d3c56?auto=format&fit=crop&q=80&w=600&h=400",
            alt: "Biomedical research",
            title: "Biomedical Diagnostic Systems",
        },
        {
            src: "https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&q=80&w=600&h=400",
            alt: "Laboratory equipment and instruments",
            title: "Laboratory Instrumentation",
        },
        {
            src: "https://images.unsplash.com/photo-1530497610245-94d3c16cda28?auto=format&fit=crop&q=80&w=600&h=400",
            alt: "Innovation workshop",
            title: "Integrated Innovation Workshop",
        },
        {
            src: "https://images.unsplash.com/photo-1584982751601-97dcc096659c?auto=format&fit=crop&q=80&w=600&h=400",
            alt: "Medical device development",
            title: "Health Device Development",
        },
        {
            src: "https://images.unsplash.com/photo-1518152006812-edab29b069ac?auto=format&fit=crop&q=80&w=600&h=400",
            alt: "Engineering design process",
            title: "Engineering Design Process",
        },
    ],
} as const;
