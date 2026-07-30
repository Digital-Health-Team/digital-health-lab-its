import React from "react";
import { Link } from "@inertiajs/react";
import { ArrowRight, ChevronLeft } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Container } from "@/Core/Components/Common/Container";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { localeTag } from "@/Core/Utils/locale";
import { useCountdown } from "@/Features/Pameran/Hooks/useCountdown";
import { pameranData } from "@/Features/Pameran/Data/pameran.data";
import { home } from "@/routes";

// ── Helpers ───────────────────────────────────────────────────────────────────

/** Zero-pad a number to 2 digits. */
function pad(n: number): string {
    return String(n).padStart(2, "0");
}

// ── Sub-components ────────────────────────────────────────────────────────────

/** A single countdown unit (digits + label). No box — borderless column. */
function CountdownUnit({
    value,
    label,
    animKey,
}: {
    value: string;
    label: string;
    /** Changing this key triggers the CSS tick animation. */
    animKey: string;
}): React.JSX.Element {
    return (
        <Box className="flex flex-col items-center gap-2.5">
            {/*
             * Key change forces React to remount this element, triggering the
             * .pameran-tick CSS animation. font-feature-settings:"tnum" keeps
             * the column width stable as digits roll over.
             */}
            <Text
                key={animKey}
                as="span"
                className="pameran-tick font-display font-extrabold text-[#F8FAFC] tabular-nums leading-none"
                style={{
                    fontSize: "clamp(2.5rem, 8vw, 5.5rem)",
                    letterSpacing: "-0.02em",
                    fontFeatureSettings: '"tnum"',
                    textShadow: "0 0 60px rgba(34,211,238,0.18)",
                }}
            >
                {value}
            </Text>

            <Text
                as="span"
                className="font-body font-medium text-[#22D3EE]/60 uppercase"
                style={{ fontSize: "0.6875rem", letterSpacing: "0.12em" }}
            >
                {label}
            </Text>
        </Box>
    );
}

/** Thin hairline `:` separator between units. */
function ClockSeparator(): React.JSX.Element {
    return (
        <Text
            as="span"
            className="font-display font-extrabold text-[#22D3EE]/30 select-none"
            style={{
                fontSize: "clamp(2rem, 6vw, 4rem)",
                lineHeight: 1,
                letterSpacing: 0,
                alignSelf: "flex-start",
                paddingTop: "0.1em",
            }}
            aria-hidden
        >
            :
        </Text>
    );
}

// ── Shared header ─────────────────────────────────────────────────────────────

/**
 * Pre-title eyebrow, exhibition subtitle, and the signature extrabold-italic
 * display title. Ported from PameranHero; shared by both countdown and live states.
 */
function CountdownHeader(): React.JSX.Element {
    const { hero } = pameranData;
    const { t } = useTranslation();

    return (
        <>
            {/* ITS institution label */}
            <Box className="flex items-center justify-center gap-3 mb-8">
                <Box className="h-px w-8 bg-[#22D3EE]/35" aria-hidden />
                <Text
                    as="span"
                    className="font-body font-medium text-[#22D3EE] uppercase tracking-[0.36em]"
                    style={{ fontSize: "clamp(0.6rem, 1.4vw, 0.75rem)" }}
                >
                    {t(hero.preTitle)}
                </Text>
                <Box className="h-px w-8 bg-[#22D3EE]/35" aria-hidden />
            </Box>

            {/* Exhibition type */}
            <Text
                as="span"
                className="font-body text-[#94A3B8] uppercase tracking-[0.22em] block mb-4"
                style={{ fontSize: "clamp(0.6875rem, 1.6vw, 0.8125rem)" }}
            >
                {t(hero.subtitle)}
            </Text>

            {/*
             * Primary display title — Italic Display Rule:
             * Plus Jakarta Sans extrabold italic is the single typographic
             * identity gesture. It appears once, here, and nowhere else.
             */}
            <Heading
                level={1}
                className="font-display font-extrabold italic text-[#F8FAFC] text-center leading-[1.02] p-0 m-0"
                style={{
                    fontSize: "clamp(3rem, 9vw, 7rem)",
                    letterSpacing: "-0.02em",
                    textShadow:
                        "0 0 80px rgba(34,211,238,0.24), 0 0 180px rgba(34,211,238,0.09)",
                }}
            >
                {hero.title}
            </Heading>
        </>
    );
}

// ── Live state ────────────────────────────────────────────────────────────────

/** Rendered once the countdown reaches zero. */
function LiveState(): React.JSX.Element {
    const { countdown } = pameranData;
    const { t } = useTranslation();

    return (
        <Box className="flex flex-col items-center gap-8 mt-10">
            {/* Status badge — dot + text (never color alone per a11y rule) */}
            <Box className="flex items-center gap-2.5">
                <Box
                    className="w-2 h-2 rounded-full bg-[#22D3EE]"
                    style={{ boxShadow: "0 0 8px rgba(34,211,238,0.8)" }}
                    aria-hidden
                />
                <Text
                    as="span"
                    className="font-body font-semibold text-[#22D3EE] uppercase tracking-[0.2em]"
                    style={{ fontSize: "0.75rem" }}
                    role="status"
                    aria-live="polite"
                >
                    {t(countdown.live.badge)}
                </Text>
            </Box>

            {/* Primary CTA — Biomedical Current fill, button-primary from DESIGN.md */}
            <Link
                href={home.url()}
                className="inline-flex items-center gap-2.5 font-body font-semibold text-[#F8FAFC] rounded-[12px] px-10 py-4 transition-all duration-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#22D3EE]/60 focus-visible:ring-offset-2 focus-visible:ring-offset-[#031026]"
                style={{
                    backgroundColor: "#00A8B5",
                    fontSize: "0.9375rem",
                }}
                onMouseEnter={(e) => {
                    (e.currentTarget as HTMLElement).style.backgroundColor =
                        "#00909B";
                    (e.currentTarget as HTMLElement).style.transform =
                        "translateY(-2px)";
                    (e.currentTarget as HTMLElement).style.boxShadow =
                        "0 8px 24px rgba(0,168,181,0.30)";
                }}
                onMouseLeave={(e) => {
                    (e.currentTarget as HTMLElement).style.backgroundColor =
                        "#00A8B5";
                    (e.currentTarget as HTMLElement).style.transform =
                        "translateY(0)";
                    (e.currentTarget as HTMLElement).style.boxShadow = "none";
                }}
            >
                {t(countdown.live.ctaText)}
                <ArrowRight className="w-4 h-4" aria-hidden />
            </Link>
        </Box>
    );
}

// ── Main component ────────────────────────────────────────────────────────────

export default function PameranCountdown(): React.JSX.Element {
    const { event, countdown } = pameranData;
    const { t, lang } = useTranslation();

    const { days, hours, minutes, seconds, isComplete } = useCountdown(
        event.dateISO,
    );

    // Formatted from dateISO rather than a stored label, and pinned to the event's
    // own timezone so every viewer sees the Jakarta date regardless of their clock.
    const eventDate = new Date(event.dateISO);
    const dateStamp = eventDate.toLocaleDateString(localeTag(lang), {
        day: "numeric",
        month: "long",
        year: "numeric",
        timeZone: event.timeZone,
    });
    const timeStamp = eventDate.toLocaleTimeString(localeTag(lang), {
        hour: "2-digit",
        minute: "2-digit",
        hour12: false,
        timeZone: event.timeZone,
    });

    return (
        <Box
            as="section"
            className="relative min-h-screen flex items-center justify-center overflow-hidden bg-[#031026]"
            aria-label={t("InnovaTech 2026 countdown page")}
        >
            {/* Honeycomb domain texture — authority, not decoration */}
            <Box
                className="absolute inset-0 honeycomb-dark opacity-[0.10] pointer-events-none"
                aria-hidden
            />

            {/* Radial teal glow — structural depth, per Depth Signals Precision */}
            <Box
                className="absolute inset-0 pointer-events-none"
                aria-hidden
                style={{
                    background:
                        "radial-gradient(ellipse 70% 60% at 50% 48%, rgba(0,168,181,0.09) 0%, rgba(0,168,181,0.03) 50%, transparent 75%)",
                }}
            />

            {/* Bottom fade — smooth floor */}
            <Box
                className="absolute bottom-0 left-0 right-0 h-40 pointer-events-none"
                aria-hidden
                style={{
                    background: "linear-gradient(to bottom, transparent, #031026)",
                }}
            />

            <Container
                centerContent
                className="relative z-10 py-32 text-center"
                style={{ maxWidth: "820px" }}
            >
                {/* ── Shared header ────────────────────────────────────── */}
                <CountdownHeader />

                {/* ── Separator ────────────────────────────────────────── */}
                <Box
                    className="mt-8 mb-9 h-px mx-auto"
                    style={{
                        width: "72px",
                        background:
                            "linear-gradient(to right, transparent, rgba(34,211,238,0.35), transparent)",
                    }}
                    aria-hidden
                />

                {/* ── Countdown clock or live state ─────────────────────── */}
                {isComplete ? (
                    <LiveState />
                ) : (
                    <Box
                        className="flex items-start justify-center gap-4 sm:gap-6"
                        role="timer"
                        aria-label={`${t("Counting down")}: ${days} ${t("Days")}, ${hours} ${t("Hours")}, ${minutes} ${t("Minutes")}, ${seconds} ${t("Seconds")}`}
                    >
                        <CountdownUnit
                            value={pad(days)}
                            label={t(countdown.units.days)}
                            animKey={`d-${days}`}
                        />
                        <ClockSeparator />
                        <CountdownUnit
                            value={pad(hours)}
                            label={t(countdown.units.hours)}
                            animKey={`h-${hours}`}
                        />
                        <ClockSeparator />
                        <CountdownUnit
                            value={pad(minutes)}
                            label={t(countdown.units.minutes)}
                            animKey={`m-${minutes}`}
                        />
                        <ClockSeparator />
                        <CountdownUnit
                            value={pad(seconds)}
                            label={t(countdown.units.seconds)}
                            animKey={`s-${seconds}`}
                        />
                    </Box>
                )}

                {/* ── Date stamp ───────────────────────────────────────── */}
                {!isComplete && (
                    <Text
                        as="span"
                        className="font-body text-[#475569] uppercase tracking-[0.26em] block mt-10"
                        style={{ fontSize: "11px" }}
                    >
                        {dateStamp} · {timeStamp} {event.timeZoneLabel}
                    </Text>
                )}

                {/* ── Back link — standalone page must not be a dead-end ── */}
                <Box className="mt-12">
                    <Link
                        href={home.url()}
                        className="inline-flex items-center gap-1.5 font-body text-[#475569] hover:text-[#94A3B8] transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#22D3EE]/50 focus-visible:ring-offset-1 focus-visible:ring-offset-[#031026] rounded"
                        style={{ fontSize: "0.8125rem" }}
                    >
                        <ChevronLeft className="w-3.5 h-3.5" aria-hidden />
                        {t(countdown.backLabel)}
                    </Link>
                </Box>
            </Container>
        </Box>
    );
}
