import { useState, useCallback } from "react";
import { Link } from "@inertiajs/react";
import { CheckCircle2 } from "lucide-react";
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
}

export default function ServiceRequestForm({ config }: ServiceRequestFormProps) {
    const Icon = config.icon;

    // Flat state bag: field name → value (File | string | Record<string,string>)
    const [fields, setFields] = useState<Record<string, unknown>>({});
    const [submitted, setSubmitted] = useState(false);

    const setField = useCallback((name: string, value: unknown) => {
        setFields((prev) => ({ ...prev, [name]: value }));
    }, []);

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();
        setSubmitted(true);
        setFields({});
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
                {/* Success banner */}
                {submitted && (
                    <Box className="flex items-center gap-2.5 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
                        <CheckCircle2 className="h-4 w-4 shrink-0" />
                        <Text as="span" className="text-sm font-medium text-emerald-700">
                            Your request has been submitted! We'll get back to you shortly.
                        </Text>
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
                            <FormField key={field.name} label={field.label}>
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
                            <FormField key={field.name} label={field.label}>
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

                {/* ── Footer: Cancel + Submit ───────────────────────── */}
                <Box className="flex items-center gap-3 pt-2">
                    <Link href="/services">
                        <Button
                            type="button"
                            variant="outline"
                            size="md"
                            className="border-slate-200 text-slate-600 hover:bg-slate-50"
                        >
                            Cancel
                        </Button>
                    </Link>

                    <Button
                        type="submit"
                        variant="primary"
                        size="md"
                    >
                        {config.submitLabel}
                    </Button>
                </Box>
            </Box>
        </Card>
    );
}
