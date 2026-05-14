import { useRef } from "react";
import { Mail } from "lucide-react";
import useContactSection from "../../Hooks/useContactSection";
import ContactForm from "./fragments/ContactForm";

function WhatsAppIcon() {
    return (
        <svg
            viewBox="0 0 24 24"
            fill="currentColor"
            className="w-5 h-5"
            aria-hidden="true"
        >
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347zm-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884zm8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
        </svg>
    );
}

function InstagramIcon() {
    return (
        <svg
            viewBox="0 0 24 24"
            fill="currentColor"
            className="w-5 h-5"
            aria-hidden="true"
        >
            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z" />
        </svg>
    );
}

const CHANNELS = [
    {
        label: "Email",
        value: "idig@its.ac.id",
        href: "mailto:idig@its.ac.id",
        icon: <Mail className="w-5 h-5" />,
        external: false,
    },
    {
        label: "WhatsApp",
        value: "+62 31 5994251",
        href: "https://wa.me/6231599425",
        icon: <WhatsAppIcon />,
        external: true,
    },
    {
        label: "Instagram",
        value: "@idig.htech",
        href: "#",
        icon: <InstagramIcon />,
        external: false,
    },
] as const;

export default function ContactSection() {
    const sectionRef = useRef<HTMLElement>(null);
    useContactSection(sectionRef);

    return (
        <section
            ref={sectionRef}
            id="contact"
            className="relative flex flex-col justify-center overflow-hidden"
            style={{ minHeight: "100svh" }}
            aria-labelledby="contact-heading"
        >
            {/* ── Background: photo + layered overlay ── */}
            <div
                className="absolute inset-0 pointer-events-none"
                aria-hidden="true"
            >
                <img
                    src="/assets/images/hero_3.jpg"
                    alt=""
                    className="w-full h-full object-cover"
                    loading="lazy"
                    decoding="async"
                />
                {/* Primary overlay: deep navy, heavier on edges */}
                <div
                    className="absolute inset-0"
                    style={{
                        background:
                            "linear-gradient(160deg, rgba(3,16,38,0.94) 0%, rgba(6,46,92,0.84) 55%, rgba(10,61,122,0.78) 100%)",
                    }}
                />
                {/* Bottom fade into footer */}
                <div
                    className="absolute inset-x-0 bottom-0 h-32"
                    style={{
                        background:
                            "linear-gradient(to bottom, transparent, rgba(3,16,38,0.6))",
                    }}
                />
                {/* Honeycomb domain texture */}
                <div className="absolute inset-0 honeycomb-dark opacity-[0.035]" />
            </div>

            {/* ── Content ── */}
            <div className="relative z-10 py-[clamp(80px,14vh,128px)] px-[clamp(24px,7vw,96px)]">
                {/* Chapter eyebrow */}
                <div className="cs-eyebrow flex items-center gap-4 mb-16">
                    <span
                        className="font-display font-extrabold text-accent-400 tabular-nums leading-none"
                        style={{ fontSize: "clamp(0.68rem, 0.9vw, 0.78rem)" }}
                    >
                        04
                    </span>
                    <div className="w-px h-4 bg-accent-400/50 shrink-0" />
                    <span
                        className="font-body font-semibold uppercase tracking-[0.25em] text-accent-400/75"
                        style={{ fontSize: "0.65rem" }}
                    >
                        Hubungi Kami
                    </span>
                </div>

                {/* Two-column grid */}
                <div className="grid grid-cols-1 lg:grid-cols-[5fr_7fr] gap-[clamp(48px,8vw,96px)] max-w-340 mx-auto">
                    {/* ── Left: Headline + contact channels ── */}
                    <div>
                        <h2
                            id="contact-heading"
                            className="font-display font-extrabold italic leading-[0.88] tracking-[-0.02em]"
                            style={{
                                fontSize: "clamp(3.5rem, 7.5vw, 6rem)",
                                color: "#F8FAFC",
                            }}
                            aria-label="Hubungi Kami — Reach Us"
                        >
                            {["Hubungi", "Kami"].map((word, i) => (
                                <span
                                    key={i}
                                    className="block overflow-hidden pb-[0.18em] -mb-[0.18em] pt-[0.18em] -mt-[0.18em]"
                                >
                                    <span className="cs-head-word block">
                                        {word}
                                    </span>
                                </span>
                            ))}
                        </h2>

                        <p
                            className="cs-subline mt-6 font-body font-medium uppercase tracking-[0.22em] text-secondary-400"
                            style={{
                                fontSize: "clamp(0.72rem, 1vw, 0.83rem)",
                            }}
                        >
                            Reach Us
                        </p>

                        <div
                            className="cs-divider mt-5 h-px bg-secondary-400/18 origin-left"
                            style={{ maxWidth: "44ch" }}
                        />

                        <p
                            className="cs-copy mt-8 font-body leading-[1.8] text-white/62"
                            style={{
                                fontSize: "clamp(0.9rem, 1.15vw, 0.98rem)",
                                maxWidth: "40ch",
                            }}
                        >
                            Kami terbuka untuk kolaborasi, pertanyaan, dan
                            pemesanan layanan fabrikasi. Tuliskan pesan Anda.
                        </p>

                        {/* Contact channels */}
                        <ul
                            className="mt-12 space-y-7"
                            aria-label="Saluran kontak"
                        >
                            {CHANNELS.map((channel) => (
                                <li key={channel.label}>
                                    <a
                                        href={channel.href}
                                        target={
                                            channel.external
                                                ? "_blank"
                                                : undefined
                                        }
                                        rel={
                                            channel.external
                                                ? "noopener noreferrer"
                                                : undefined
                                        }
                                        className="cs-channel group flex items-center gap-5 w-fit"
                                    >
                                        <span
                                            className="flex items-center justify-center w-10 h-10 rounded-full
                                                bg-white/6 border border-white/9 text-secondary-400
                                                group-hover:bg-secondary-500/18 group-hover:border-secondary-400/28
                                                transition-all duration-300 shrink-0"
                                        >
                                            {channel.icon}
                                        </span>
                                        <div>
                                            <p
                                                className="font-body font-medium uppercase tracking-[0.14em] text-white/32"
                                                style={{ fontSize: "0.6rem" }}
                                            >
                                                {channel.label}
                                            </p>
                                            <p
                                                className="font-body text-white/75 group-hover:text-secondary-400 transition-colors duration-300 mt-0.5"
                                                style={{
                                                    fontSize:
                                                        "clamp(0.87rem, 1.05vw, 0.94rem)",
                                                }}
                                            >
                                                {channel.value}
                                            </p>
                                        </div>
                                    </a>
                                </li>
                            ))}
                        </ul>
                    </div>

                    {/* ── Right: Form ── */}
                    <div className="lg:pt-[clamp(0px,2vh,24px)]">
                        <ContactForm />
                    </div>
                </div>
            </div>

            {/* Architectural number watermark */}
            <div
                className="absolute bottom-6 right-[clamp(24px,7vw,96px)] font-display font-extrabold text-white/3 select-none pointer-events-none leading-none z-0"
                style={{ fontSize: "clamp(6rem, 15vw, 13rem)" }}
                aria-hidden="true"
            >
                04
            </div>
        </section>
    );
}
