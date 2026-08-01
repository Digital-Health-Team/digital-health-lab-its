import { useState } from "react";
import { Head, Link, useForm, usePage } from "@inertiajs/react";
import { AlertTriangle, ArrowLeft, ArrowRight, Plus, X } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { Heading } from "@/Core/Components/Common/Heading";
import Button from "@/Core/Components/Shared/Button/Button";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import FileDropzone from "@/Features/Publish/Components/FileDropzone";
import KindIllustration from "@/Features/Publish/Components/KindIllustration";
import { type PublishFormItem, type PublishKind } from "@/Features/Publish/Types/publish.type";

interface PublishFormPageProps {
    kind: PublishKind | null;
    item: PublishFormItem | null;
    /** Kilobyte ceilings, already capped by PHP's upload_max_filesize / post_max_size. */
    limits: { coverKb: number; filesKb: number; pdfKb: number };
    auth: { user: { name: string } | null };
    [key: string]: unknown;
}

function mb(kb: number): string {
    return kb >= 1024 ? `${Math.floor(kb / 1024)}mb` : `${kb}kb`;
}

const PROJECT_CATEGORIES = [
    { value: "3d_model", label: "3D Model" },
    { value: "iot_system", label: "IoT System" },
    { value: "medical_device", label: "Medical Device" },
    { value: "software", label: "Software" },
];

const LISTING_TYPES = [
    { value: "", label: "— None —" },
    { value: "journals", label: "Journals" },
    { value: "products", label: "Products" },
    { value: "powerpoint", label: "Powerpoint" },
    { value: "downloadable", label: "Downloadable" },
    { value: "read_only", label: "Read Only" },
];

const LICENSES = ["MIT", "Apache 2.0", "CC BY 4.0", "GPL-3.0"];
const PUBLICATION_CATEGORIES = ["Journals", "Papers"];

const inputClass =
    "w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-secondary-500/30";

/* ── Local building blocks; this file is the only consumer ── */

function Field({ label, required, error, className, children }: {
    label: string;
    required?: boolean;
    error?: string;
    className?: string;
    children: React.ReactNode;
}) {
    return (
        <Box className={`space-y-1.5 ${className ?? ""}`}>
            <Box as="label" className="block text-sm font-medium text-slate-700">
                {label} {required && <Text as="span" className="text-rose-500">*</Text>}
            </Box>
            {children}
            {error && <Text variant="small" className="text-rose-500">{error}</Text>}
        </Box>
    );
}

function Section({ title, children }: { title: string; children: React.ReactNode }) {
    return (
        <Box className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <Heading level={2} className="mb-5 text-sm md:text-sm font-bold uppercase tracking-wider text-slate-400">
                {title}
            </Heading>
            <Box className="space-y-5">{children}</Box>
        </Box>
    );
}

function StringListField({ label, values, onChange }: {
    label: string;
    values: string[];
    onChange: (next: string[]) => void;
}) {
    const { t } = useTranslation();

    return (
        <Box className="space-y-2">
            <Box className="flex items-center justify-between">
                <Box as="label" className="block text-sm font-medium text-slate-700">{label}</Box>
                <Box
                    as="button"
                    type="button"
                    onClick={() => onChange([...values, ""])}
                    className="inline-flex items-center gap-1 text-xs font-semibold text-secondary-500 hover:underline"
                >
                    <Plus className="h-3 w-3" /> {t("Add")}
                </Box>
            </Box>
            {values.length === 0 && (
                <Text variant="small" className="text-slate-400">{t("No items yet.")}</Text>
            )}
            {values.map((value, i) => (
                <Box key={i} className="flex gap-2">
                    <Box
                        as="input"
                        type="text"
                        value={value}
                        onChange={(e: React.ChangeEvent<HTMLInputElement>) =>
                            onChange(values.map((v, j) => (j === i ? e.target.value : v)))
                        }
                        className={`flex-1 ${inputClass}`}
                    />
                    <Box
                        as="button"
                        type="button"
                        aria-label={t("Remove")}
                        onClick={() => onChange(values.filter((_, j) => j !== i))}
                        className="rounded-lg p-1.5 text-slate-500 transition-colors hover:bg-rose-50 hover:text-rose-600"
                    >
                        <X className="h-4 w-4" />
                    </Box>
                </Box>
            ))}
        </Box>
    );
}

/* ── Page ────────────────────────────────────────────────── */

type FormData = Record<string, string | string[] | File[]>;

export default function PublishFormPage() {
    const { kind: initialKind, item, limits, auth } = usePage<PublishFormPageProps>().props;
    const { t } = useTranslation();
    // Set by the PostTooLarge handler in bootstrap/app.php, which has no session to flash to.
    const uploadError = new URLSearchParams(window.location.search).get("uploadError");
    // Local only — picking a kind on create needs no round trip.
    const [kind, setKind] = useState<PublishKind | null>(initialKind);
    const isEdit = item != null;

    const form = useForm<FormData>({
        kind: initialKind ?? "project",
        title: item?.title ?? "",
        description: item?.description ?? [],
        // project
        category: item?.category ?? (initialKind === "publication" ? "Journals" : "3d_model"),
        listing_type: item?.listing_type ?? "",
        caption: item?.caption ?? "",
        license: item?.license ?? "MIT",
        version: item?.version ?? "",
        format: item?.format ?? "",
        highlights: item?.highlights ?? [],
        includes: item?.includes ?? [],
        cover_file: [],
        files: [],
        // publication
        author: item?.author ?? auth.user?.name ?? "",
        abstract: item?.abstract ?? "",
        keywords: item?.keywords ?? [],
        doi: item?.doi ?? "",
        journal: item?.journal ?? "",
        pmid: item?.pmid ?? "",
        published_at: item?.published_at ?? "",
        thumbnail_file: [],
        pdf_file: [],
    });

    function chooseKind(next: PublishKind) {
        setKind(next);
        form.setData("kind", next);
        form.setData("category", next === "publication" ? "Journals" : "3d_model");
    }

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();
        // Both create and update are POST — the form carries files.
        form.transform((data) => ({
            ...data,
            // Inertia sends single-file inputs as a bare File, not a one-item array.
            cover_file: (data.cover_file as File[])[0] ?? null,
            thumbnail_file: (data.thumbnail_file as File[])[0] ?? null,
            pdf_file: (data.pdf_file as File[])[0] ?? null,
        }));
        form.post(isEdit ? `/publish/${kind}/${item.id}` : "/publish", { forceFormData: true });
    }

    const set = (field: string) => (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) =>
        form.setData(field, e.target.value);

    const existingCover = item?.coverUrl
        ? [{ name: t("Current cover image"), size: null, url: item.coverUrl }]
        : [];

    // Whole phrases, not "Publish" + kind — Indonesian and English do not share word order.
    const heading =
        kind === "project"
            ? t(isEdit ? "Edit Open-Source Project" : "Publish Open-Source Project")
            : t(isEdit ? "Edit Publication" : "Publish Publication");

    return (
        <>
            <Head title={kind === null ? t("Publish something new") : heading} />
            <DashboardLayout>
                <Link
                    href="/publish"
                    className="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-800"
                >
                    <ArrowLeft className="h-4 w-4" />
                    {t("Your Publications")}
                </Link>

                {kind === null ? (
                    /* ── Kind picker ── */
                    <Box className="mx-auto w-full max-w-4xl py-6">
                        <Heading
                            level={1}
                            className="font-display text-3xl md:text-4xl font-extrabold text-primary-700"
                        >
                            {t("Publish something new")}
                        </Heading>
                        <Text className="mt-2 text-slate-500">
                            {t("Choose what you want to submit. An admin reviews it before it goes public.")}
                        </Text>

                        <Box className="mt-8 space-y-5">
                            {([
                                {
                                    value: "project" as const,
                                    title: t("Open-Source Project"),
                                    blurb: t("A 3D model, IoT system, medical device, or piece of software, with downloadable files for the community."),
                                },
                                {
                                    value: "publication" as const,
                                    title: t("Publication"),
                                    blurb: t("A journal article or research paper, with an abstract, keywords, and an optional PDF."),
                                },
                            ]).map(({ value, title, blurb }) => (
                                <Box
                                    key={value}
                                    className="flex flex-col items-center gap-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md sm:flex-row sm:p-8"
                                >
                                    <Box className="w-full shrink-0 sm:w-56">
                                        <KindIllustration kind={value} />
                                    </Box>

                                    <Box className="flex-1 text-center sm:text-left">
                                        <Heading
                                            level={2}
                                            className="font-display text-xl md:text-xl font-bold text-primary-700"
                                        >
                                            {title}
                                        </Heading>
                                        <Text className="mt-2 text-slate-500">{blurb}</Text>
                                    </Box>

                                    <Box
                                        as="button"
                                        type="button"
                                        onClick={() => chooseKind(value)}
                                        className="inline-flex shrink-0 items-center gap-2 rounded-full bg-gradient-to-r from-primary-700 to-primary-600 px-7 py-3 text-sm font-bold text-white shadow-lg shadow-primary-700/25 transition-shadow hover:shadow-xl hover:shadow-primary-700/35 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-600 focus-visible:ring-offset-2"
                                    >
                                        {t("Select")}
                                        <ArrowRight className="h-4 w-4" />
                                    </Box>
                                </Box>
                            ))}
                        </Box>
                    </Box>
                ) : (
                    /* ── Form, full page width ── */
                    <Box className="w-full">
                        <Heading
                            level={1}
                            className="mt-3 mb-6 font-display text-3xl md:text-4xl font-extrabold text-primary-700"
                        >
                            {heading}
                        </Heading>

                        {uploadError && (
                            <Box className="mb-6 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3">
                                <AlertTriangle className="mt-0.5 h-4 w-4 shrink-0 text-rose-600" />
                                <Text variant="small" className="text-rose-700">
                                    {uploadError}
                                </Text>
                            </Box>
                        )}

                        <Box as="form" onSubmit={handleSubmit} className="space-y-6 pb-4">
                            {kind === "project" ? (
                                <>
                                    <Section title={t("Details")}>
                                        <Field label={t("Title")} required error={form.errors.title}>
                                            <Box as="input" type="text" value={form.data.title as string}
                                                onChange={set("title")} className={inputClass} />
                                        </Field>

                                        <Box className="grid grid-cols-1 gap-5 md:grid-cols-2">
                                            <Field label={t("Category")} required error={form.errors.category}>
                                                <Box as="select" value={form.data.category as string}
                                                    onChange={set("category")} className={inputClass}>
                                                    {PROJECT_CATEGORIES.map((c) => (
                                                        <option key={c.value} value={c.value}>{c.label}</option>
                                                    ))}
                                                </Box>
                                            </Field>
                                            <Field label={t("Listing Type")}>
                                                <Box as="select" value={form.data.listing_type as string}
                                                    onChange={set("listing_type")} className={inputClass}>
                                                    {LISTING_TYPES.map((l) => (
                                                        <option key={l.value} value={l.value}>{l.label}</option>
                                                    ))}
                                                </Box>
                                            </Field>
                                        </Box>

                                        <Field label={t("Caption")}>
                                            <Box as="textarea" rows={2} maxLength={500}
                                                value={form.data.caption as string} onChange={set("caption")}
                                                className={`${inputClass} resize-none`} />
                                        </Field>

                                        <Box className="grid grid-cols-1 gap-5 md:grid-cols-3">
                                            <Field label={t("License")}>
                                                <Box as="select" value={form.data.license as string}
                                                    onChange={set("license")} className={inputClass}>
                                                    {LICENSES.map((l) => <option key={l} value={l}>{l}</option>)}
                                                </Box>
                                            </Field>
                                            <Field label={t("Version")}>
                                                <Box as="input" type="text" value={form.data.version as string}
                                                    onChange={set("version")} className={inputClass} placeholder="v1.0.0" />
                                            </Field>
                                            <Field label={t("Format")}>
                                                <Box as="input" type="text" value={form.data.format as string}
                                                    onChange={set("format")} className={inputClass} placeholder="ZIP, PDF" />
                                            </Field>
                                        </Box>
                                    </Section>

                                    <Section title={t("Content")}>
                                        <Box className="grid grid-cols-1 gap-6 lg:grid-cols-3">
                                            <StringListField label={t("Description")} values={form.data.description as string[]}
                                                onChange={(v) => form.setData("description", v)} />
                                            <StringListField label={t("Highlights")} values={form.data.highlights as string[]}
                                                onChange={(v) => form.setData("highlights", v)} />
                                            <StringListField label={t("Includes")} values={form.data.includes as string[]}
                                                onChange={(v) => form.setData("includes", v)} />
                                        </Box>
                                    </Section>

                                    <Section title={t("Media & Files")}>
                                        <Box className="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                            <FileDropzone
                                                label={t("Cover Image")}
                                                accept="image/*"
                                                maxKb={limits.coverKb}
                                                hint={t("JPG, JPEG, PNG, WEBP up to :limit").replace(":limit", mb(limits.coverKb))}
                                                caption={t("4:3 ratio recommended · shown on your publication card")}
                                                files={form.data.cover_file as File[]}
                                                onChange={(f) => form.setData("cover_file", f)}
                                                existing={existingCover}
                                                error={form.errors.cover_file}
                                            />
                                            <FileDropzone
                                                label={t("Project Files")}
                                                multiple
                                                maxKb={limits.filesKb}
                                                hint={t("STL, OBJ, ZIP, PDF up to :limit each").replace(":limit", mb(limits.filesKb))}
                                                caption={isEdit ? t("New files are appended to the existing ones.") : undefined}
                                                files={form.data.files as File[]}
                                                onChange={(f) => form.setData("files", f)}
                                                existing={item?.existingFiles ?? []}
                                                error={form.errors["files.0"]}
                                            />
                                        </Box>
                                    </Section>
                                </>
                            ) : (
                                <>
                                    <Section title={t("Details")}>
                                        <Field label={t("Title")} required error={form.errors.title}>
                                            <Box as="input" type="text" value={form.data.title as string}
                                                onChange={set("title")} className={inputClass} />
                                        </Field>

                                        <Box className="grid grid-cols-1 gap-5 md:grid-cols-2">
                                            <Field label={t("Author")} required error={form.errors.author}>
                                                <Box as="input" type="text" value={form.data.author as string}
                                                    onChange={set("author")} className={inputClass} />
                                            </Field>
                                            <Field label={t("Category")} required error={form.errors.category}>
                                                <Box as="select" value={form.data.category as string}
                                                    onChange={set("category")} className={inputClass}>
                                                    {PUBLICATION_CATEGORIES.map((c) => (
                                                        <option key={c} value={c}>{t(c)}</option>
                                                    ))}
                                                </Box>
                                            </Field>
                                        </Box>

                                        <Field label={t("Abstract")}>
                                            <Box as="textarea" rows={5} value={form.data.abstract as string}
                                                onChange={set("abstract")} className={`${inputClass} resize-none`} />
                                        </Field>
                                    </Section>

                                    <Section title={t("Content")}>
                                        <Box className="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                            <StringListField label={t("Description")} values={form.data.description as string[]}
                                                onChange={(v) => form.setData("description", v)} />
                                            <StringListField label={t("Keywords")} values={form.data.keywords as string[]}
                                                onChange={(v) => form.setData("keywords", v)} />
                                        </Box>
                                    </Section>

                                    <Section title={t("Citation")}>
                                        <Box className="grid grid-cols-1 gap-5 md:grid-cols-4">
                                            <Field label="DOI">
                                                <Box as="input" type="text" value={form.data.doi as string}
                                                    onChange={set("doi")} className={inputClass} placeholder="10.1000/…" />
                                            </Field>
                                            <Field label={t("Journal")}>
                                                <Box as="input" type="text" value={form.data.journal as string}
                                                    onChange={set("journal")} className={inputClass} />
                                            </Field>
                                            <Field label="PMID">
                                                <Box as="input" type="text" value={form.data.pmid as string}
                                                    onChange={set("pmid")} className={inputClass} />
                                            </Field>
                                            {/* Native date input — no picker dependency. */}
                                            <Field label={t("Publication Date")} error={form.errors.published_at}>
                                                <Box as="input" type="date" value={form.data.published_at as string}
                                                    onChange={set("published_at")} className={inputClass} />
                                            </Field>
                                        </Box>
                                    </Section>

                                    <Section title={t("Media & Files")}>
                                        <Box className="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                            <FileDropzone
                                                label={t("Cover Image")}
                                                accept="image/*"
                                                maxKb={limits.coverKb}
                                                hint={t("JPG, JPEG, PNG, WEBP up to :limit").replace(":limit", mb(limits.coverKb))}
                                                caption={t("4:3 ratio recommended · shown on your publication card")}
                                                files={form.data.thumbnail_file as File[]}
                                                onChange={(f) => form.setData("thumbnail_file", f)}
                                                existing={existingCover}
                                                error={form.errors.thumbnail_file}
                                            />
                                            <FileDropzone
                                                label={t("PDF")}
                                                accept="application/pdf"
                                                maxKb={limits.pdfKb}
                                                hint={t("PDF up to :limit").replace(":limit", mb(limits.pdfKb))}
                                                files={form.data.pdf_file as File[]}
                                                onChange={(f) => form.setData("pdf_file", f)}
                                                existing={item?.pdfName
                                                    ? [{ name: item.pdfName, size: item.pdfSize ?? null, url: item.pdfUrl ?? null }]
                                                    : []}
                                                error={form.errors.pdf_file}
                                            />
                                        </Box>
                                    </Section>
                                </>
                            )}

                            <Box className="flex justify-end gap-3">
                                <Link href="/publish">
                                    <Button type="button" variant="ghost">{t("Cancel")}</Button>
                                </Link>
                                <Button type="submit" variant="primary" loading={form.processing}>
                                    {isEdit ? t("Save Changes") : t("Submit for Review")}
                                </Button>
                            </Box>
                        </Box>
                    </Box>
                )}
            </DashboardLayout>
        </>
    );
}
