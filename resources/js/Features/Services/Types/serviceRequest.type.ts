import type { LucideIcon } from "lucide-react";

/* ── Field kinds ─────────────────────────────────────────────────── */

export interface PhotoField {
    kind: "photo";
    name: string;
    label: string;
    hint: string;
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

export type ServiceRequestField =
    | PhotoField
    | FileField
    | TextareaField
    | PresetsField
    | DimensionsField;

/* ── Config ──────────────────────────────────────────────────────── */

export interface ServiceRequestConfig {
    slug: string;
    title: string;
    subtitle: string;
    icon: LucideIcon;
    submitLabel: string;
    fields: ServiceRequestField[];
}
