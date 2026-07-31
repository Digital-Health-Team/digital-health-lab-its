import { useState } from "react";
import { Link, router } from "@inertiajs/react";
import { AlertTriangle, ChevronRight, Trash2 } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { Heading } from "@/Core/Components/Common/Heading";
import { Image } from "@/Core/Components/Common/Image";
import Modal from "@/Core/Components/Shared/Modal/Modal";
import Button from "@/Core/Components/Shared/Button/Button";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { type PublishItem } from "@/Features/Publish/Types/publish.type";

interface PublishCardProps {
    item: PublishItem;
}

/** Cover gradients, keyed by the category chip. Tokens from resources/css/public.css. */
const COVER_GRADIENTS: Record<string, string> = {
    Projects: "from-primary-600 via-secondary-500 to-secondary-500",
    Papers: "from-primary-950 via-primary-800 to-primary-600",
    Journals: "from-[#c08a10] via-[#e0a614] to-accent-400",
    Research: "from-primary-950 via-primary-900 to-primary-800",
};

const STATUS_PILL: Record<string, string> = {
    approved: "bg-white/30 text-white backdrop-blur-sm ring-1 ring-inset ring-white/40",
    pending: "bg-accent-400 text-primary-950",
    rejected: "bg-rose-500 text-white",
};

const STATUS_LABEL: Record<string, string> = {
    approved: "Published",
    pending: "Under Review",
    rejected: "Rejected",
};

function formatDate(iso: string, lang: string): string {
    const d = new Date(iso);
    if (Number.isNaN(d.getTime())) return iso;
    return d.toLocaleDateString(lang === "en" ? "en-US" : "id-ID", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
}

export default function PublishCard({ item }: PublishCardProps) {
    const { t, lang } = useTranslation();
    const [confirmOpen, setConfirmOpen] = useState(false);
    const [processing, setProcessing] = useState(false);
    const isApproved = item.status === "approved";

    // One link on every card, as in the design. Unapproved work has no public page,
    // so it points at the edit form instead — which is also the resubmit path.
    const viewHref = item.detailHref ?? item.editHref;

    function confirmDelete() {
        setProcessing(true);
        router.delete(item.deleteUrl, {
            preserveScroll: true,
            onFinish: () => {
                setProcessing(false);
                setConfirmOpen(false);
            },
        });
    }

    return (
        <Box className="flex flex-col overflow-hidden rounded-2xl bg-white shadow-[0_10px_40px_-10px_rgba(3,16,38,0.2)] transition-shadow hover:shadow-[0_20px_60px_-15px_rgba(3,16,38,0.4)]">
            {/* ── Cover ── */}
            <Box className="relative aspect-[4/3] shrink-0 overflow-hidden">
                {item.coverUrl ? (
                    /* No width/height: Image turns those into an inline style that beats
                       `h-full w-full`, and they also switch on a srcSet of `?w=` URLs that
                       nothing on this server resizes. Let the wrapper fill the cover. */
                    <Image
                        src={item.coverUrl}
                        alt={item.title}
                        className="h-full w-full"
                        objectFit="cover"
                    />
                ) : (
                    <Box
                        className={`h-full w-full bg-gradient-to-br ${COVER_GRADIENTS[item.category] ?? COVER_GRADIENTS.Research}`}
                    >
                        {/* Decorative disc, bleeding off the top-right corner. */}
                        <Box className="absolute -right-10 -top-16 h-56 w-56 rounded-full bg-white/10" />
                    </Box>
                )}

                {/* Scrim: the pills sit on user-uploaded photos, which can be any
                    brightness. Without it the translucent "Published" pill vanishes on a
                    light cover. */}
                <Box className="pointer-events-none absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-primary-950/55 to-transparent" />

                <Box className="absolute inset-x-4 top-4 flex items-start justify-between gap-2">
                    <Text
                        as="span"
                        className="rounded-md bg-primary-950/80 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider text-white backdrop-blur-sm"
                    >
                        {t(item.category)}
                    </Text>
                    <Text
                        as="span"
                        className={`rounded-md px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider ${STATUS_PILL[item.status] ?? STATUS_PILL.pending}`}
                    >
                        {t(STATUS_LABEL[item.status] ?? item.status)}
                    </Text>
                </Box>

                {item.canDelete && (
                    <Box
                        as="button"
                        type="button"
                        onClick={() => setConfirmOpen(true)}
                        aria-label={isApproved ? t("Request removal") : t("Delete")}
                        title={isApproved ? t("Request removal") : t("Delete")}
                        className="absolute bottom-4 right-4 rounded-xl bg-primary-950/60 p-2.5 text-white backdrop-blur-sm transition-colors hover:bg-rose-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
                    >
                        <Trash2 className="h-4 w-4" />
                    </Box>
                )}
            </Box>

            {/* ── Body ── */}
            <Box className="flex flex-1 flex-col gap-1 p-5">
                <Text as="span" className="font-display text-lg font-bold leading-snug text-primary-700">
                    {item.title}
                </Text>

                {/* "Published" above an UNDER REVIEW pill would contradict itself. */}
                <Text variant="small" className="text-slate-500">
                    {t(isApproved ? "Published" : "Submitted")} {formatDate(item.date, lang)}
                </Text>

                {item.withdrawalRequested && (
                    <Text variant="small" className="mt-1 font-semibold text-orange-600">
                        {t("Removal requested — awaiting admin review.")}
                    </Text>
                )}

                {item.status === "rejected" && (
                    <Box className="mt-2 rounded-lg border border-rose-100 bg-rose-50 px-3 py-2">
                        <Text variant="small" className="text-rose-700">
                            {t("Rejected by admin. Edit and resubmit for review.")}
                        </Text>
                    </Box>
                )}

                <Link
                    href={viewHref}
                    className="mt-2 inline-flex items-center gap-1 text-sm font-bold text-secondary-500 hover:underline"
                >
                    {t("View Details")}
                    <ChevronRight className="h-4 w-4" />
                </Link>
            </Box>

            <Modal open={confirmOpen} onClose={() => setConfirmOpen(false)}>
                <Box className="p-6">
                    <Box className="flex items-start gap-4">
                        <Box
                            className={`shrink-0 rounded-xl p-2.5 ${
                                isApproved ? "bg-orange-50 text-orange-600" : "bg-rose-50 text-rose-600"
                            }`}
                        >
                            <AlertTriangle className="h-5 w-5" />
                        </Box>
                        <Box className="min-w-0">
                            <Heading level={2} className="text-base md:text-base font-bold text-slate-900">
                                {isApproved ? t("Request removal") : t("Delete submission")}
                            </Heading>
                            <Text variant="small" className="mt-1.5 text-slate-500">
                                {isApproved
                                    ? t("This is live on the site. An admin will review your removal request before it comes down.")
                                    : t("This cannot be undone.")}
                            </Text>
                            <Text as="span" className="mt-3 block truncate font-semibold text-slate-800">
                                {item.title}
                            </Text>
                        </Box>
                    </Box>

                    <Box className="mt-6 flex justify-end gap-3">
                        <Button type="button" variant="ghost" size="sm" onClick={() => setConfirmOpen(false)}>
                            {t("Cancel")}
                        </Button>
                        <Button
                            type="button"
                            size="sm"
                            loading={processing}
                            onClick={confirmDelete}
                            className={
                                isApproved
                                    ? "bg-orange-600 text-white shadow-md shadow-orange-600/30 hover:bg-orange-700"
                                    : "bg-rose-600 text-white shadow-md shadow-rose-600/30 hover:bg-rose-700"
                            }
                        >
                            {isApproved ? t("Request removal") : t("Delete")}
                        </Button>
                    </Box>
                </Box>
            </Modal>
        </Box>
    );
}
