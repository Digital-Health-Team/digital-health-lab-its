import { type FormEvent } from "react";
import { useForm } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Modal } from "@/Core/Components/Shared";
import { Button, Input } from "@/Core/Components/Shared";
import FormField from "@/Features/Services/Components/ServiceRequestForm/fragments/FormField";
import { X } from "lucide-react";
import training from "@/routes/training";

interface TrainingRegistrationModalProps {
    open: boolean;
    onClose: () => void;
    courseTitle: string;
    price: number;
    trainingSlug: string;
}

export default function TrainingRegistrationModal({
    open,
    onClose,
    courseTitle,
    price,
    trainingSlug,
}: TrainingRegistrationModalProps) {
    const form = useForm({
        full_name: "",
        email: "",
        phone_number: "",
        preferred_session: "",
        additional_notes: "",
    });

    const priceFormatted = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(price);

    function handleSubmit(e: FormEvent) {
        e.preventDefault();
        form.post(training.register(trainingSlug).url, {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                onClose();
            },
        });
    }

    function handleClose() {
        form.reset();
        form.clearErrors();
        onClose();
    }

    return (
        <Modal open={open} onClose={handleClose} className="max-h-[90vh] overflow-y-auto">
            <>
                {/* Header */}
                <Box className="flex items-start justify-between gap-4 p-5 border-b border-slate-100">
                    <Box className="space-y-0.5 min-w-0">
                        <Heading level={4} className="text-base font-bold text-slate-900 leading-snug">
                            Register for this course
                        </Heading>
                        <Text className="text-xs text-slate-500 leading-snug line-clamp-2">
                            {courseTitle}
                        </Text>
                        <Text className="text-base font-bold text-secondary-600 pt-1">
                            {priceFormatted}
                        </Text>
                    </Box>
                    <button
                        type="button"
                        onClick={handleClose}
                        className="shrink-0 p-1.5 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors"
                        aria-label="Close"
                    >
                        <X className="h-4 w-4" />
                    </button>
                </Box>

                {/* Form body */}
                <Box
                    as="form"
                    onSubmit={handleSubmit}
                    noValidate
                    className="p-5 space-y-4"
                >
                    {form.errors.registration && (
                        <Box className="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3">
                            <Text className="text-sm text-rose-600">{form.errors.registration}</Text>
                        </Box>
                    )}

                    <FormField label="Full name" error={form.errors.full_name}>
                        <Input
                            type="text"
                            placeholder="e.g. Budi Santoso"
                            value={form.data.full_name}
                            onChange={(e) => form.setData("full_name", e.target.value)}
                            className="w-full"
                        />
                    </FormField>

                    <FormField label="Email address" error={form.errors.email}>
                        <Input
                            type="email"
                            placeholder="you@example.com"
                            value={form.data.email}
                            onChange={(e) => form.setData("email", e.target.value)}
                            className="w-full"
                        />
                    </FormField>

                    <FormField label="Phone number" error={form.errors.phone_number}>
                        <Input
                            type="tel"
                            placeholder="e.g. 081234567890"
                            value={form.data.phone_number}
                            onChange={(e) => form.setData("phone_number", e.target.value)}
                            className="w-full"
                        />
                    </FormField>

                    <FormField
                        label="Preferred session"
                        hint="Optional — leave blank if any session works."
                        error={form.errors.preferred_session}
                    >
                        <Input
                            type="text"
                            placeholder="e.g. Weekend morning"
                            value={form.data.preferred_session}
                            onChange={(e) => form.setData("preferred_session", e.target.value)}
                            className="w-full"
                        />
                    </FormField>

                    <FormField
                        label="Additional notes"
                        hint="Optional — any questions or accommodations?"
                        error={form.errors.additional_notes}
                    >
                        <Box
                            as="textarea"
                            rows={3}
                            placeholder="Anything we should know…"
                            value={form.data.additional_notes}
                            onChange={(e: React.ChangeEvent<HTMLTextAreaElement>) =>
                                form.setData("additional_notes", e.target.value)
                            }
                            className="w-full px-4 py-2.5 text-sm border border-slate-200 bg-white text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-secondary-500/30 focus:border-secondary-400 transition resize-none rounded-2xl"
                        />
                    </FormField>

                    <Button
                        type="submit"
                        variant="primary"
                        size="lg"
                        className="w-full mt-2"
                        disabled={form.processing}
                    >
                        {form.processing ? "Submitting…" : "Register now"}
                    </Button>
                </Box>
            </>
        </Modal>
    );
}
