export interface Capability {
    tag: string;
    title: string;
    description: string;
    accent: string;
    image: string;
    imageAlt: string;
    /**
     * Which side the image sits on at md+. Named for the image, unlike `align` in
     * servicesSection.type.ts which denotes the *text* side.
     */
    imageSide: "left" | "right";
}

/** A still in the Act 1 image stack. No copy of its own — it sits beside the vision text. */
export interface AboutMediaItem {
    image: string;
    alt: string;
    /** Ring colour; drawn from the brand palette, same convention as Capability.accent. */
    accent: string;
}

export interface AboutHeadlineWord {
    word: string;
    accent: boolean;
    lineBreakAfter: boolean;
}
