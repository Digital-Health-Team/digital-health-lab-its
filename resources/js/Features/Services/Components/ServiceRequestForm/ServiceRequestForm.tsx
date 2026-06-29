import { useState, useCallback } from "react";
import { Link, router } from "@inertiajs/react";
import { MapPin } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Card } from "@/Core/Components/Shared/Card/Card";
import Button from "@/Core/Components/Shared/Button/Button";
import {
    type ServiceRequestConfig,
    type FilamentOption,
    type ColorOption,
} from "@/Features/Services/Types/serviceRequest.type";
import FormField from "./fragments/FormField";
import PhotoUpload from "./fragments/PhotoUpload";
import PresetInput from "./fragments/PresetInput";
import DimensionsInput from "./fragments/DimensionsInput";
import FilamentTypeInput from "./fragments/FilamentTypeInput";
import PriceEstimation from "./fragments/PriceEstimation";
import ColorSwatchInput from "./fragments/ColorSwatchInput";
import ScanningLocationInput, { type ScanningLocationValue } from "./fragments/ScanningLocationInput";

interface ServiceRequestFormProps {
    config: ServiceRequestConfig;
    serviceId: number;
    isAuthenticated: boolean;
    filaments: FilamentOption[];
    colors: ColorOption[];
}

export default function ServiceRequestForm({ config, serviceId, isAuthenticated, filaments, colors }: ServiceRequestFormProps) {
    const Icon = config.icon;

    // Initialise filament_type to the first available filament (if any); scanning_location defaults to visit_lab
    const [fields, setFields] = useState<Record<string, unknown>>(() => ({
        filament_type: filaments[0]?.code ?? "",
        scanning_location: "visit_lab",
    }));
    const [errors, setErrors] = useState<Record<string, string>>({});
    const [processing, setProcessing] = useState(false);

    // The currently selected filament object, used by PriceEstimation and ColorSwatchInput
    const selectedFilament = filaments.find(
        (f) => f.code === (fields["filament_type"] as string),
    );

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

        // Derive brief_description from whichever text field exists.
        // For printing, also fold in the selected color name.
        const selectedColorId = fields["color"] as number | undefined;
        const selectedColorName = selectedColorId
            ? (colors.find((c) => c.id === selectedColorId)?.name ?? "")
            : "";
        const notesText =
            (fields["description"] as string) ??
            (fields["notes"] as string) ??
            (fields["purpose"] as string) ??
            "";
        const briefDescription = [
            notesText,
            selectedColorName ? `Color: ${selectedColorName}` : "",
        ]
            .filter(Boolean)
            .join(" | ") || "";

        const dimensions = fields["object_size"] as Record<string, string> | undefined;

        router.post(
            "/orders",
            {
                service_id: serviceId,
                brief_description: briefDescription,
                reference_photo: (fields["reference_photo"] as File) ?? (fields["reference_image"] as File) ?? undefined,
                model_file: (fields["model_file"] as File) ?? undefined,
                // For printing, use the selected filament code; fall back to free-text material.
                material_preference:
                    (fields["filament_type"] as string) ||
                    (fields["material"] as string) ||
                    undefined,
                filament_width: (fields["filament_width"] as string) ?? undefined,
                scan_purpose: (fields["scan_purpose"] as string) ?? undefined,
                scanning_location: (fields["scanning_location"] as string) ?? undefined,
                address: (fields["address"] as string) ?? undefined,
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
                        const defaultUploadLabel =
                            field.kind === "file"
                                ? "Click to upload your 3D model file"
                                : "Click to upload your reference photo";
                        const uploadLabel =
                            (field.kind === "photo" && field.uploadLabel) || defaultUploadLabel;
                        return (
                            <FormField
                                key={field.name}
                                label={field.label}
                                description={field.kind === "photo" ? field.description : undefined}
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

                    if (field.kind === "scanning-location") {
                        const locationValue = (fields[field.name] as ScanningLocationValue) ?? "visit_lab";
                        const isHomeVisit = locationValue === "home_visit";
                        return (
                            <Box key={field.name} className="space-y-4">
                                <FormField
                                    label={field.label}
                                    description={field.hint}
                                    error={errors[field.name]}
                                >
                                    <ScanningLocationInput
                                        name={field.name}
                                        value={locationValue}
                                        onChange={(val) => setField(field.name, val)}
                                    />
                                </FormField>

                                {isHomeVisit && (
                                    <Box className="space-y-1.5">
                                        <Box className="flex items-center gap-1.5">
                                            <MapPin className="h-3.5 w-3.5 text-[#00426D]" />
                                            <Box
                                                as="label"
                                                className="block text-sm font-semibold text-slate-700"
                                            >
                                                Your Address
                                            </Box>
                                        </Box>
                                        <Box
                                            as="textarea"
                                            name="address"
                                            rows={4}
                                            placeholder="Enter your full address — include building name, street, city, and any landmarks for easy navigation"
                                            value={(fields["address"] as string) ?? ""}
                                            onChange={(e: React.ChangeEvent<HTMLTextAreaElement>) =>
                                                setField("address", e.target.value)
                                            }
                                            className="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 bg-white focus:outline-none focus:border-[#00426D] focus:ring-1 focus:ring-[#00426D] transition-colors duration-150 resize-y"
                                        />
                                        {errors["address"] && (
                                            <Text as="span" className="block text-xs text-red-500 pl-0.5">
                                                {errors["address"]}
                                            </Text>
                                        )}
                                        <Text as="span" className="block text-xs text-slate-400 pl-0.5">
                                            The exact fee will be confirmed after we review the distance from our laboratory
                                        </Text>
                                    </Box>
                                )}
                            </Box>
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

                    if (field.kind === "filament") {
                        return (
                            <FormField
                                key={field.name}
                                label={field.label}
                                error={errors[field.name]}
                            >
                                <FilamentTypeInput
                                    name={field.name}
                                    filaments={filaments}
                                    value={(fields[field.name] as string) ?? ""}
                                    onChange={(code) => setField(field.name, code)}
                                />
                            </FormField>
                        );
                    }

                    if (field.kind === "price-estimation") {
                        return (
                            <PriceEstimation
                                key="price-estimation"
                                filament={selectedFilament}
                            />
                        );
                    }

                    if (field.kind === "color") {
                        return (
                            <FormField
                                key={field.name}
                                label={field.label}
                                error={errors[field.name]}
                            >
                                <ColorSwatchInput
                                    name={field.name}
                                    colors={colors}
                                    filamentName={selectedFilament?.name}
                                    value={(fields[field.name] as number) ?? null}
                                    onChange={(colorId) => setField(field.name, colorId)}
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
