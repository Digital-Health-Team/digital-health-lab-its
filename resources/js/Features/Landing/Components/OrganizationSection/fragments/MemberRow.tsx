interface MemberRowProps {
    member: {
        name: string;
        desc: string;
        initials: string;
    };
    /** "right" for Act 2 (HTECH), "left" for Act 3 (RCMED) */
    align: "left" | "right";
    /** CSS class name for the GSAP animation target, e.g. "act-2-member" */
    memberClass: string;
}

/** A single team member row in the OrganizationSection member lists. */
export default function MemberRow({ member, align, memberClass }: MemberRowProps) {
    const isRight = align === "right";

    return (
        <div
            className={`overflow-hidden py-[clamp(10px,1.5vh,16px)] flex items-center gap-4 ${isRight ? "justify-end" : ""}`}
        >
            {isRight && (
                <div className={`${memberClass} text-right`}>
                    <span
                        className="font-display font-semibold italic text-primary-900/80 block"
                        style={{ fontSize: "clamp(1.1rem, 2.2vw, 1.6rem)" }}
                    >
                        {member.name}
                    </span>
                    <span className="font-body text-slate-500 text-[0.78rem] block mt-0.5">
                        {member.desc}
                    </span>
                </div>
            )}

            <div className="w-10 h-10 rounded-full bg-primary-700/[0.05] border border-primary-700/[0.08] flex items-center justify-center shrink-0">
                <span className="font-display font-semibold text-primary-700/30 text-[0.7rem]">
                    {member.initials}
                </span>
            </div>

            {!isRight && (
                <div className={memberClass}>
                    <span
                        className="font-display font-semibold italic text-primary-900/80 block"
                        style={{ fontSize: "clamp(1.1rem, 2.2vw, 1.6rem)" }}
                    >
                        {member.name}
                    </span>
                    <span className="font-body text-slate-500 text-[0.78rem] block mt-0.5">
                        {member.desc}
                    </span>
                </div>
            )}
        </div>
    );
}
