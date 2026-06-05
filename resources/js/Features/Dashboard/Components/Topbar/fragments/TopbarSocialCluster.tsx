const socialLinks = [
    { key: "fb", label: "f", title: "Facebook", href: "#" },
    { key: "ig", label: "in", title: "Instagram", href: "#" },
    { key: "yt", label: "▶", title: "YouTube", href: "#" },
];

export default function TopbarSocialCluster() {
    return (
        <div className="hidden sm:flex items-center gap-2">
            <span className="text-xs font-medium text-slate-500 whitespace-nowrap">Follow us</span>
            {socialLinks.map((s) => (
                <a
                    key={s.key}
                    href={s.href}
                    aria-label={s.title}
                    title={s.title}
                    className="w-8 h-8 rounded-full bg-slate-100 hover:bg-secondary-500 hover:text-white text-slate-600 grid place-items-center text-xs font-bold transition-colors duration-150"
                >
                    {s.label}
                </a>
            ))}
        </div>
    );
}
