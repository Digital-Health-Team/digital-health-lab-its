import { Crosshair, Printer, ScanLine } from "lucide-react";
import { type ServiceRequestConfig } from "../Types/serviceRequest.type";

export const serviceRequestConfigs: Record<string, ServiceRequestConfig> = {
    design: {
        slug: "design",
        title: "Create 3D Design",
        subtitle: "Fill in the details below to submit your design request",
        icon: Crosshair,
        submitLabel: "Submit Request",
        fields: [
            {
                kind: "photo",
                name: "reference_photo",
                label: "Reference Photo",
                hint: "PNG, JPG, or JPEG up to 10MB",
                accept: "image/png,image/jpg,image/jpeg",
            },
            {
                kind: "textarea",
                name: "description",
                label: "Description",
                placeholder:
                    "Describe the 3D model you'd like us to create — include details about shape, purpose, material preferences, etc.",
                rows: 4,
            },
            {
                kind: "presets",
                name: "filament_width",
                label: "Desired Filament Width",
                placeholder: "1.75",
                unit: "mm",
                presets: ["1.75 mm", "2.85 mm"],
                hint: "Common sizes: 1.75mm (standard) or 2.85mm",
            },
            {
                kind: "dimensions",
                name: "object_size",
                label: "Object Size (Dimensions)",
                unit: "cm",
                axes: [
                    { name: "length", label: "LENGTH" },
                    { name: "width", label: "WIDTH" },
                    { name: "height", label: "HEIGHT" },
                ],
            },
        ],
    },

    printing: {
        slug: "printing",
        title: "Get 3D Prints",
        subtitle: "Upload your 3D model file and configure your print",
        icon: Printer,
        submitLabel: "Submit Request",
        fields: [
            {
                kind: "file",
                name: "model_file",
                label: "Upload 3D Model File",
                hint: "Accepted formats: .OBJ, .STL",
                accept: ".stl,.obj",
            },
            {
                kind: "filament",
                name: "filament_type",
                label: "Filament Type",
            },
            {
                kind: "price-estimation",
            },
            {
                kind: "color",
                name: "color",
                label: "Filament Color",
            },
            {
                kind: "dimensions",
                name: "object_size",
                label: "Object Size (Dimensions)",
                unit: "cm",
                axes: [
                    { name: "length", label: "LENGTH" },
                    { name: "width", label: "WIDTH" },
                    { name: "height", label: "HEIGHT" },
                ],
            },
            {
                kind: "textarea",
                name: "notes",
                label: "Notes",
                placeholder:
                    "Any specific instructions — layer height, infill %, support structures, etc.",
                rows: 4,
            },
        ],
    },

    scanning: {
        slug: "scanning",
        title: "Start 3D Scanning",
        subtitle: "Upload a reference and choose your scanning option",
        icon: ScanLine,
        submitLabel: "Submit Scan Request",
        fields: [
            {
                kind: "photo",
                name: "reference_image",
                label: "Reference Image",
                description: "Upload a photo of the object you want to 3D scan",
                uploadLabel: "Click to upload a reference image",
                hint: "PNG, JPG, or JPEG up to 10MB",
                accept: "image/png,image/jpg,image/jpeg",
            },
            {
                kind: "scanning-location",
                name: "scanning_location",
                label: "Scanning Location",
                hint: "Choose how you'd like the scanning to be done",
            },
        ],
    },
};
