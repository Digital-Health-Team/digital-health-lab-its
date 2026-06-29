import type { LucideIcon } from "lucide-react";

/* ── Data shapes passed as Inertia props ─────────────────────────── */

export interface FilamentOption {
    code: string;
    name: string;
    scientificName: string;
    pricePerGram: number;
    priceLabel: string;   // e.g. "Rp 2.000"
    description: string;
}

export interface ColorOption {
    id: number;
    name: string;
    hex: string | null;   // null if not yet set
}

/* ── Field kinds ─────────────────────────────────────────────────── */

export interface PhotoField {
    kind: "photo";
    name: string;
    label: string;
    description?: string;
    hint: string;
    uploadLabel?: string;
    accept?: string;
}

export interface FileField {
    kind: "file";
    name: string;
    label: string;
    hint: string;
    accept: string;
}

export interface TextareaField {
    kind: "textarea";
    name: string;
    label: string;
    placeholder: string;
    rows?: number;
}

export interface PresetsField {
    kind: "presets";
    name: string;
    label: string;
    placeholder: string;
    unit?: string;
    presets: string[];
    hint?: string;
}

export interface DimensionsAxis {
    name: string;
    label: string;
}

export interface DimensionsField {
    kind: "dimensions";
    name: string;
    label: string;
    unit: string;
    axes: DimensionsAxis[];
}

/** Filament-type radio card selector — data provided via form props. */
export interface FilamentField {
    kind: "filament";
    name: string;
    label: string;
}

/** Derived price-estimation info box — driven by the selected FilamentField value. */
export interface PriceEstimationField {
    kind: "price-estimation";
    label?: string;
}

/** Colour swatch picker — data provided via form props. */
export interface ColorField {
    kind: "color";
    name: string;
    label: string;
    hint?: string;
}

/** Radio card selector for scanning location (visit lab vs home visit). */
export interface ScanningLocationField {
    kind: "scanning-location";
    name: string;
    label: string;
    hint?: string;
}

export type ServiceRequestField =
    | PhotoField
    | FileField
    | TextareaField
    | PresetsField
    | DimensionsField
    | FilamentField
    | PriceEstimationField
    | ColorField
    | ScanningLocationField;

/* ── Config ──────────────────────────────────────────────────────── */

export interface ServiceRequestConfig {
    slug: string;
    title: string;
    subtitle: string;
    icon: LucideIcon;
    submitLabel: string;
    fields: ServiceRequestField[];
}
