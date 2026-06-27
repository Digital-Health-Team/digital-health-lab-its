import { useState, useCallback } from "react";
import { Link, router } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Card } from "@/Core/Components/Shared/Card/Card";
import Button from "@/Core/Components/Shared/Button/Button";
import { type ServiceRequestConfig } from "@/Features/Services/Types/serviceRequest.type";
import FormField from "./fragments/FormField";
import PhotoUpload from "./fragments/PhotoUpload";
import PresetInput from "./fragments/PresetInput";
import DimensionsInput from "./fragments/DimensionsInput";

interface ServiceRequestFormProps {
    config: ServiceRequestConfig;
    serviceId: number;
    isAuthenticated: boolean;
}

export default function ServiceRequestForm({ config, serviceId, isAuthenticated }: ServiceRequestFormProps) {
    const Icon = config.icon;

    const [fields, setFields] = useState<Record<string, unknown>>({});
    const [errors, setErrors] = useState<Record<string, string>>({});
    const [processing, setProcessing] = useState(false);

    const setField = useCallback((name: string, value: unknown) => {
        setFields((prev) => ({ ...prev, [name]: value }));
        setErrors((prev) => {
            const next = { ...prev };
            delete next[name];
            return next;
        });
    }, []);

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();
        setErrors({});
        setProcessing(true);

        // Derive brief_description from whichever text field exists
        const briefDescription =
            (fields["description"] as string) ??
            (fields["notes"] as string) ??
            (fields["purpose"] as string) ??
            "";

        const dimensions = fields["object_size"] as Record<string, string> | undefined;

        router.post(
            "/orders",
            {
                service_id: serviceId,
                brief_description: briefDescription,
                reference_photo: (fields["reference_photo"] as File) ?? undefined,
                model_file: (fields["model_file"] as File) ?? undefined,
                material_preference: (fields["material"] as string) ?? undefined,
                filament_width: (fields["filament_width"] as string) ?? undefined,
                scan_purpose: (fields["scan_purpose"] as string) ?? undefined,
                object_dimensions: dimensions ?? undefined,
            },
            {
                forceFormData: true,
                onError: (validationErrors) => {
                    setErrors(validationErrors as Record<string, string>);
                    setProcessing(false);
                },
                onFinish: () => setProcessing(false),
            }
        );
    }

    return (
        <Card className="overflow-hidden">
            {/* ── Dark gradient card header ──────────────────────────── */}
            <Box
                className="flex items-center gap-4 px-6 py-5"
                style={{
                    background:
                        "linear-gradient(108deg, #062e5c 0%, #00426d 55%, #006e80 100%)",
                }}
            >
                {/* Glassy icon tile */}
                <Box
                    className="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 border border-white/20"
                    style={{
                        background:
                            "linear-gradient(145deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.05) 100%)",
                        backdropFilter: "blur(8px)",
                    }}
                >
                    <Icon className="h-5 w-5 text-secondary-200" strokeWidth={1.5} />
                </Box>

                <Box>
                    <Heading
                        level={3}
                        className="font-display text-lg font-bold text-white"
                    >
                        {config.title}
                    </Heading>
                    <Text as="span" className="text-xs text-white/65">
                        {config.subtitle}
                    </Text>
                </Box>
            </Box>

            {/* ── Form body ─────────────────────────────────────────── */}
            <Box
                as="form"
                className="px-6 py-6 space-y-6"
                onSubmit={handleSubmit}
            >
                {/* Global error */}
                {errors.brief_description && (
                    <Box className="px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                        {errors.brief_description}
                    </Box>
                )}

                {/* Render each field by kind */}
                {config.fields.map((field) => {
                    if (field.kind === "photo" || field.kind === "file") {
                        const uploadLabel =
                            field.kind === "file"
                                ? "Click to upload your model file"
                                : "Click to upload your reference photo";
                        return (
                            <FormField
                                key={field.name}
                                label={field.label}
                                hint={field.hint}
                                error={errors[field.name]}
                            >
                                <PhotoUpload
                                    name={field.name}
                                    hint={field.hint}
                                    accept={field.accept}
                                    uploadLabel={uploadLabel}
                                    value={(fields[field.name] as File) ?? null}
                                    onChange={(file) => setField(field.name, file)}
                                />
                            </FormField>
                        );
                    }

                    if (field.kind === "textarea") {
                        return (
                            <FormField key={field.name} label={field.label} error={errors[field.name]}>
                                <Box
                                    as="textarea"
                                    name={field.name}
                                    rows={field.rows ?? 4}
                                    placeholder={field.placeholder}
                                    value={(fields[field.name] as string) ?? ""}
                                    onChange={(e: React.ChangeEvent<HTMLTextAreaElement>) =>
                                        setField(field.name, e.target.value)
                                    }
                                    className="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 bg-white focus:outline-none focus:border-[#00426D] focus:ring-1 focus:ring-[#00426D] transition-colors duration-150 resize-y"
                                />
                            </FormField>
                        );
                    }

                    if (field.kind === "presets") {
                        return (
                            <FormField
                                key={field.name}
                                label={field.label}
                                hint={field.hint}
                                error={errors[field.name]}
                            >
                                <PresetInput
                                    name={field.name}
                                    placeholder={field.placeholder}
                                    unit={field.unit}
                                    presets={field.presets}
                                    value={(fields[field.name] as string) ?? ""}
                                    onChange={(val) => setField(field.name, val)}
                                />
                            </FormField>
                        );
                    }

                    if (field.kind === "dimensions") {
                        return (
                            <FormField key={field.name} label={field.label} error={errors["object_dimensions"]}>
                                <DimensionsInput
                                    name={field.name}
                                    unit={field.unit}
                                    axes={field.axes}
                                    value={
                                        (fields[field.name] as Record<string, string>) ?? {}
                                    }
                                    onChange={(axisName, axisValue) =>
                                        setField(field.name, {
                                            ...((fields[field.name] as Record<string, string>) ??
                                                {}),
                                            [axisName]: axisValue,
                                        })
                                    }
                                />
                            </FormField>
                        );
                    }

                    return null;
                })}

                {/* ── Footer: Cancel + Submit / Login gate ─────────── */}
                <Box className="flex items-center gap-3 pt-2">
                    <Link href="/services">
                        <Button
                            type="button"
                            variant="outline"
                            size="md"
                            className="border-slate-200 text-slate-600 hover:bg-slate-50"
                            disabled={processing}
                        >
                            Cancel
                        </Button>
                    </Link>

                    {isAuthenticated ? (
                        <Button
                            type="submit"
                            variant="primary"
                            size="md"
                            disabled={processing}
                        >
                            {processing ? "Submitting…" : config.submitLabel}
                        </Button>
                    ) : (
                        <Link href="/login">
                            <Button type="button" variant="primary" size="md">
                                Login to Order
                            </Button>
                        </Link>
                    )}
                </Box>
            </Box>
        </Card>
    );
}
