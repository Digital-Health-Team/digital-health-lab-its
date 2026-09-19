import { Box } from "@/Core/Components/Common/Box";
import { type PublishKind } from "@/Features/Publish/Types/publish.type";

/**
 * Inline SVG on purpose — self-contained, themeable with the brand tokens, and it
 * costs no extra request. Decorative, so it is hidden from assistive tech.
 */
export default function KindIllustration({ kind }: { kind: PublishKind }) {
    return (
        <Box
            aria-hidden="true"
            className={`flex items-center justify-center rounded-2xl bg-gradient-to-br p-6 ${
                kind === "project"
                    ? "from-secondary-500/15 to-primary-600/15"
                    : "from-primary-700/12 to-accent-400/20"
            }`}
        >
            {kind === "project" ? <ProjectArt /> : <PublicationArt />}
        </Box>
    );
}

/** An isometric block, stacked — a 3D model / device. */
function ProjectArt() {
    return (
        <svg viewBox="0 0 160 140" className="h-32 w-40" fill="none">
            <ellipse cx="80" cy="124" rx="52" ry="9" fill="#00426d" opacity="0.12" />
            <path d="M80 18 L128 45 L80 72 L32 45 Z" fill="#22d3ee" />
            <path d="M32 45 L80 72 L80 118 L32 91 Z" fill="#00a8b5" />
            <path d="M128 45 L80 72 L80 118 L128 91 Z" fill="#00426d" />
            <path d="M80 18 L128 45 L80 72 L32 45 Z" stroke="#ffffff" strokeWidth="2" strokeLinejoin="round" opacity="0.6" />
            <circle cx="122" cy="30" r="12" fill="#ffc72c" />
            <path d="M117 30 h10 M122 25 v10" stroke="#00426d" strokeWidth="2.5" strokeLinecap="round" />
        </svg>
    );
}

/** Stacked pages with a highlighted abstract block — a paper. */
function PublicationArt() {
    return (
        <svg viewBox="0 0 160 140" className="h-32 w-40" fill="none">
            <ellipse cx="80" cy="126" rx="50" ry="8" fill="#00426d" opacity="0.12" />
            <rect x="38" y="20" width="76" height="98" rx="8" fill="#0a3d7a" opacity="0.35" />
            <rect x="30" y="14" width="76" height="98" rx="8" fill="#ffffff" stroke="#00426d" strokeWidth="2.5" />
            <rect x="42" y="30" width="38" height="7" rx="3.5" fill="#00426d" />
            <rect x="42" y="45" width="52" height="5" rx="2.5" fill="#94a3b8" />
            <rect x="42" y="56" width="52" height="5" rx="2.5" fill="#94a3b8" />
            <rect x="42" y="67" width="34" height="5" rx="2.5" fill="#94a3b8" />
            <rect x="42" y="82" width="52" height="18" rx="4" fill="#00a8b5" opacity="0.25" />
            <circle cx="116" cy="98" r="20" fill="#ffc72c" />
            <path d="M108 98 l6 6 l12 -13" stroke="#00426d" strokeWidth="3.5" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
    );
}
