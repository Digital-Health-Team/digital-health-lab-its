import { useState, type FormEvent } from "react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Modal } from "@/Core/Components/Shared";
import { Button, Input } from "@/Core/Components/Shared";
import FormField from "@/Features/Services/Components/ServiceRequestForm/fragments/FormField";
import { CheckCircle2, X } from "lucide-react";

interface TrainingRegistrationModalProps {
    open: boolean;
    onClose: () => void;
    courseTitle: string;
    price: number;
}

interface FormState {
    name: string;
    email: string;
    phone: string;
    session: string;
    notes: string;
}

const emptyForm: FormState = {
    name: "",
    email: "",
    phone: "",
    session: "",
    notes: "",
};

export default function TrainingRegistrationModal({
    open,
    onClose,
    courseTitle,
    price,
}: TrainingRegistrationModalProps) {
    const [form, setForm] = useState<FormState>(emptyForm);
    const [errors, setErrors] = useState<Partial<FormState>>({});
    const [submitted, setSubmitted] = useState(false);

    const priceFormatted = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
    }).format(price);

    function validate(): boolean {
        const next: Partial<FormState> = {};
        if (!form.name.trim()) next.name = "Full name is required.";
        if (!form.email.trim()) next.email = "Email address is required.";
        else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email))
            next.email = "Please enter a valid email.";
        if (!form.phone.trim()) next.phone = "Phone number is required.";
        setErrors(next);
        return Object.keys(next).length === 0;
    }

    function handleSubmit(e: FormEvent) {
        e.preventDefault();
        if (!validate()) return;
        // Frontend-only stub — no server call yet
        setSubmitted(true);
    }

    function handleClose() {
        setSubmitted(false);
        setForm(emptyForm);
        setErrors({});
        onClose();
    }

    return (
        <Modal open={open} onClose={handleClose} className="max-h-[90vh] overflow-y-auto">
            {submitted ? (
                /* ── Success state ──────────────────────────────── */
                <Box className="p-8 text-center space-y-5">
                    <Box className="flex justify-center">
                        <Box className="w-16 h-16 rounded-full bg-secondary-50 flex items-center justify-center">
                            <CheckCircle2 className="h-8 w-8 text-secondary-500" />
                        </Box>
                    </Box>
                    <Heading level={2} className="text-lg font-bold text-slate-900">
                        You&apos;re registered!
                    </Heading>
                    <Text className="text-sm text-slate-500 leading-relaxed">
                        Thanks, <Text as="span" className="font-semibold text-slate-700">{form.name}</Text>! Your registration for{" "}
                        <Text as="span" className="font-semibold text-slate-700">{courseTitle}</Text> has been received.
                        We&apos;ll send a confirmation to{" "}
                        <Text as="span" className="font-semibold text-slate-700">{form.email}</Text> shortly.
                    </Text>
                    <Button variant="primary" size="lg" className="w-full" onClick={handleClose}>
                        Done
                    </Button>
                </Box>
            ) : (
                /* ── Registration form ──────────────────────────── */
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
                        <FormField label="Full name" error={errors.name}>
                            <Input
                                type="text"
                                placeholder="e.g. Budi Santoso"
                                value={form.name}
                                onChange={(e) => setForm((f) => ({ ...f, name: e.target.value }))}
                                className="w-full"
                            />
                        </FormField>

                        <FormField label="Email address" error={errors.email}>
                            <Input
                                type="email"
                                placeholder="you@example.com"
                                value={form.email}
                                onChange={(e) => setForm((f) => ({ ...f, email: e.target.value }))}
                                className="w-full"
                            />
                        </FormField>

                        <FormField label="Phone number" error={errors.phone}>
                            <Input
                                type="tel"
                                placeholder="e.g. 081234567890"
                                value={form.phone}
                                onChange={(e) => setForm((f) => ({ ...f, phone: e.target.value }))}
                                className="w-full"
                            />
                        </FormField>

                        <FormField
                            label="Preferred session"
                            hint="Optional — leave blank if any session works."
                            error={errors.session}
                        >
                            <Input
                                type="text"
                                placeholder="e.g. Weekend morning"
                                value={form.session}
                                onChange={(e) => setForm((f) => ({ ...f, session: e.target.value }))}
                                className="w-full"
                            />
                        </FormField>

                        <FormField
                            label="Additional notes"
                            hint="Optional — any questions or accommodations?"
                            error={errors.notes}
                        >
                            <Box
                                as="textarea"
                                rows={3}
                                placeholder="Anything we should know…"
                                value={form.notes}
                                onChange={(e: React.ChangeEvent<HTMLTextAreaElement>) =>
                                    setForm((f) => ({ ...f, notes: e.target.value }))
                                }
                                className="w-full px-4 py-2.5 text-sm border border-slate-200 bg-white text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-secondary-500/30 focus:border-secondary-400 transition resize-none rounded-2xl"
                            />
                        </FormField>

                        <Button
                            type="submit"
                            variant="primary"
                            size="lg"
                            className="w-full mt-2"
                        >
                            Register now
                        </Button>
                    </Box>
                </>
            )}
        </Modal>
    );
}
