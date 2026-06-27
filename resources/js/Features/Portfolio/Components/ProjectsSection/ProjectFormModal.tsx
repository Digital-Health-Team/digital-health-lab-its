import { useEffect, useRef } from "react";
import { useForm } from "@inertiajs/react";
import { Plus, X } from "lucide-react";
import Modal from "@/Core/Components/Shared/Modal/Modal";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { Heading } from "@/Core/Components/Common/Heading";
import Button from "@/Core/Components/Shared/Button/Button";
import { type UserProject } from "@/Features/Portfolio/Types/portfolio.type";

interface ProjectFormModalProps {
    open: boolean;
    onClose: () => void;
    project?: UserProject | null;
}

const CATEGORIES = [
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

type FormData = {
    title: string;
    category: string;
    listing_type: string;
    caption: string;
    license: string;
    version: string;
    format: string;
    description: string[];
    highlights: string[];
    includes: string[];
    files: File[];
    [key: string]: string | string[] | File[];
};

export default function ProjectFormModal({ open, onClose, project }: ProjectFormModalProps) {
    const fileInputRef = useRef<HTMLInputElement>(null);
    const isEdit = project != null;

    const form = useForm<FormData>({
        title: "",
        category: "3d_model",
        listing_type: "",
        caption: "",
        license: "MIT",
        version: "",
        format: "",
        description: [],
        highlights: [],
        includes: [],
        files: [],
    });

    useEffect(() => {
        if (open) {
            form.reset();
            if (project) {
                form.setData({
                    title: project.title,
                    category: project.category,
                    listing_type: project.listingType ?? "",
                    caption: project.caption ?? "",
                    license: project.license,
                    version: project.version ?? "",
                    format: project.format ?? "",
                    description: project.description,
                    highlights: project.highlights,
                    includes: project.includes,
                    files: [],
                });
            }
        }
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [open, project?.id]);

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();
        const url = isEdit ? `/my/projects/${project.id}` : "/my/projects";

        form.post(url, {
            forceFormData: true,
            onSuccess: onClose,
        });
    }

    function handleFilesChange(e: React.ChangeEvent<HTMLInputElement>) {
        form.setData("files", Array.from(e.target.files ?? []) as unknown as File[]);
    }

    function addArrayItem(field: "description" | "highlights" | "includes") {
        form.setData(field, [...(form.data[field] as string[]), ""]);
    }

    function updateArrayItem(field: "description" | "highlights" | "includes", index: number, value: string) {
        const arr = [...(form.data[field] as string[])];
        arr[index] = value;
        form.setData(field, arr);
    }

    function removeArrayItem(field: "description" | "highlights" | "includes", index: number) {
        form.setData(
            field,
            (form.data[field] as string[]).filter((_, i) => i !== index),
        );
    }

    return (
        <Modal open={open} onClose={onClose} className="max-w-2xl w-full">
            <Box className="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <Heading level={2} className="text-lg font-semibold text-slate-800">
                    {isEdit ? "Edit Project" : "Upload Project"}
                </Heading>
                <button type="button" onClick={onClose} className="text-slate-400 hover:text-slate-600">
                    <X className="h-5 w-5" />
                </button>
            </Box>

            <Box
                as="form"
                onSubmit={handleSubmit}
                className="px-6 py-5 space-y-5 max-h-[70vh] overflow-y-auto"
            >
                {/* Title */}
                <Box className="space-y-1.5">
                    <label className="block text-sm font-medium text-slate-700">
                        Title <span className="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        value={form.data.title}
                        onChange={(e) => form.setData("title", e.target.value)}
                        className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#00426D]/30"
                        placeholder="Project title"
                    />
                    {form.errors.title && <Text variant="small" className="text-rose-500">{form.errors.title}</Text>}
                </Box>

                {/* Category + Listing Type */}
                <Box className="grid grid-cols-2 gap-4">
                    <Box className="space-y-1.5">
                        <label className="block text-sm font-medium text-slate-700">
                            Category <span className="text-rose-500">*</span>
                        </label>
                        <select
                            value={form.data.category}
                            onChange={(e) => form.setData("category", e.target.value)}
                            className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#00426D]/30"
                        >
                            {CATEGORIES.map((c) => (
                                <option key={c.value} value={c.value}>{c.label}</option>
                            ))}
                        </select>
                    </Box>
                    <Box className="space-y-1.5">
                        <label className="block text-sm font-medium text-slate-700">Listing Type</label>
                        <select
                            value={form.data.listing_type}
                            onChange={(e) => form.setData("listing_type", e.target.value)}
                            className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#00426D]/30"
                        >
                            {LISTING_TYPES.map((t) => (
                                <option key={t.value} value={t.value}>{t.label}</option>
                            ))}
                        </select>
                    </Box>
                </Box>

                {/* Caption */}
                <Box className="space-y-1.5">
                    <label className="block text-sm font-medium text-slate-700">Caption</label>
                    <textarea
                        value={form.data.caption}
                        onChange={(e) => form.setData("caption", e.target.value)}
                        rows={2}
                        maxLength={500}
                        className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#00426D]/30 resize-none"
                        placeholder="Brief caption (max 500 characters)"
                    />
                </Box>

                {/* License + Version + Format */}
                <Box className="grid grid-cols-3 gap-4">
                    <Box className="space-y-1.5">
                        <label className="block text-sm font-medium text-slate-700">License</label>
                        <select
                            value={form.data.license}
                            onChange={(e) => form.setData("license", e.target.value)}
                            className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#00426D]/30"
                        >
                            {LICENSES.map((l) => (
                                <option key={l} value={l}>{l}</option>
                            ))}
                        </select>
                    </Box>
                    <Box className="space-y-1.5">
                        <label className="block text-sm font-medium text-slate-700">Version</label>
                        <input
                            type="text"
                            value={form.data.version}
                            onChange={(e) => form.setData("version", e.target.value)}
                            className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#00426D]/30"
                            placeholder="e.g. v1.0.0"
                        />
                    </Box>
                    <Box className="space-y-1.5">
                        <label className="block text-sm font-medium text-slate-700">Format</label>
                        <input
                            type="text"
                            value={form.data.format}
                            onChange={(e) => form.setData("format", e.target.value)}
                            className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#00426D]/30"
                            placeholder="e.g. ZIP, PDF"
                        />
                    </Box>
                </Box>

                {/* Dynamic arrays */}
                {(["description", "highlights", "includes"] as const).map((field) => (
                    <Box key={field} className="space-y-2">
                        <Box className="flex items-center justify-between">
                            <label className="block text-sm font-medium text-slate-700 capitalize">{field}</label>
                            <button
                                type="button"
                                onClick={() => addArrayItem(field)}
                                className="inline-flex items-center gap-1 text-xs text-[#00426D] hover:underline"
                            >
                                <Plus className="h-3 w-3" /> Add
                            </button>
                        </Box>
                        {(form.data[field] as string[]).map((item, i) => (
                            <Box key={i} className="flex gap-2">
                                <input
                                    type="text"
                                    value={item}
                                    onChange={(e) => updateArrayItem(field, i, e.target.value)}
                                    className="flex-1 rounded-lg border border-slate-200 px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#00426D]/30"
                                    placeholder={`${field.charAt(0).toUpperCase() + field.slice(1)} item`}
                                />
                                <button
                                    type="button"
                                    onClick={() => removeArrayItem(field, i)}
                                    className="text-slate-400 hover:text-rose-500"
                                >
                                    <X className="h-4 w-4" />
                                </button>
                            </Box>
                        ))}
                    </Box>
                ))}

                {/* Files */}
                <Box className="space-y-1.5">
                    <label className="block text-sm font-medium text-slate-700">
                        Files {isEdit && <span className="font-normal text-slate-400">(new files will be appended)</span>}
                    </label>
                    <input
                        ref={fileInputRef}
                        type="file"
                        multiple
                        onChange={handleFilesChange}
                        className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-600 file:mr-3 file:rounded file:border-0 file:bg-slate-100 file:px-3 file:py-1 file:text-xs file:font-medium file:text-slate-700 hover:file:bg-slate-200"
                    />
                    <Text variant="small" className="text-slate-400">Max 20 MB per file.</Text>
                    {form.errors["files.0"] && (
                        <Text variant="small" className="text-rose-500">{form.errors["files.0"]}</Text>
                    )}
                </Box>
            </Box>

            <Box className="flex justify-end gap-3 px-6 py-4 border-t border-slate-100">
                <Button type="button" variant="ghost" onClick={onClose}>
                    Cancel
                </Button>
                <Button type="submit" variant="primary" loading={form.processing}>
                    {isEdit ? "Save Changes" : "Submit for Review"}
                </Button>
            </Box>
        </Modal>
    );
}
