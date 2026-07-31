import { type FormEvent, useState, useRef } from "react";
import { useForm, usePage } from "@inertiajs/react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Modal } from "@/Core/Components/Shared";
import { Button, Input } from "@/Core/Components/Shared";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { formatIDR } from "@/Core/Utils/locale";
import FormField from "@/Features/Services/Components/ServiceRequestForm/fragments/FormField";
import { X, CheckCircle2, Upload, Landmark, QrCode } from "lucide-react";
import training from "@/routes/training";

interface PaymentInfo {
    qrisImageUrl: string;
    bankName: string;
    bankAccountName: string;
    bankAccountNumber: string;
}

interface TrainingRegistrationModalProps {
    open: boolean;
    onClose: () => void;
    courseTitle: string;
    price: number;
    trainingSlug: string;
    isPaid: boolean;
}

type Step = "form" | "payment" | "done";

export default function TrainingRegistrationModal({
    open,
    onClose,
    courseTitle,
    price,
    trainingSlug,
    isPaid,
}: TrainingRegistrationModalProps) {
    const { props } = usePage<{ paymentInfo: PaymentInfo | null }>();
    const { t } = useTranslation();
    const paymentInfo = props.paymentInfo;

    const [step, setStep] = useState<Step>("form");
    const fileInputRef = useRef<HTMLInputElement>(null);
    const [selectedFile, setSelectedFile] = useState<File | null>(null);

    const form = useForm({
        full_name: "",
        email: "",
        phone_number: "",
        preferred_session: "",
        additional_notes: "",
    });

    const proofForm = useForm<{ payment_proof: File | null }>({
        payment_proof: null,
    });

    const priceFormatted = formatIDR(price);

    function handleSubmit(e: FormEvent) {
        e.preventDefault();
        form.post(training.register(trainingSlug).url, {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                if (isPaid) {
                    setStep("payment");
                } else {
                    onClose();
                }
            },
        });
    }

    function handleFileChange(e: React.ChangeEvent<HTMLInputElement>) {
        const file = e.target.files?.[0] ?? null;
        setSelectedFile(file);
        proofForm.setData("payment_proof", file);
        proofForm.clearErrors("payment_proof");
    }

    function handleUploadProof(e: FormEvent) {
        e.preventDefault();
        if (!proofForm.data.payment_proof) {
            proofForm.setError("payment_proof", t("Please choose a payment proof file."));
            return;
        }
        proofForm.post(training.uploadProof(trainingSlug).url, {
            preserveScroll: true,
            onSuccess: () => setStep("done"),
        });
    }

    function handleClose() {
        form.reset();
        form.clearErrors();
        proofForm.reset();
        proofForm.clearErrors();
        setStep("form");
        setSelectedFile(null);
        onClose();
    }

    return (
        <Modal open={open} onClose={handleClose} className="max-h-[90vh] overflow-y-auto">
            <>
                {/* Header */}
                <Box className="flex items-start justify-between gap-4 p-5 border-b border-slate-100">
                    <Box className="space-y-0.5 min-w-0">
                        <Heading level={4} className="text-base font-bold text-slate-900 leading-snug">
                            {step === "form" && "Register for this course"}
                            {step === "payment" && "Upload payment proof"}
                            {step === "done" && "Proof uploaded"}
                        </Heading>
                        <Text className="text-xs text-slate-500 leading-snug line-clamp-2">
                            {courseTitle}
                        </Text>
                        {isPaid && (
                            <Text className="text-base font-bold text-secondary-600 pt-1">
                                {priceFormatted}
                            </Text>
                        )}
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

                {/* Step: Registration form */}
                {step === "form" && (
                    <Box
                        as="form"
                        onSubmit={handleSubmit}
                        noValidate
                        className="p-5 space-y-4"
                    >
                        {(form.errors as Record<string, string>).registration && (
                            <Box className="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3">
                                <Text className="text-sm text-rose-600">
                                    {(form.errors as Record<string, string>).registration}
                                </Text>
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
                            {form.processing
                                ? "Submitting…"
                                : isPaid
                                ? "Continue to Payment"
                                : "Register now"}
                        </Button>
                    </Box>
                )}

                {/* Step: Payment */}
                {step === "payment" && (
                    <Box as="form" onSubmit={handleUploadProof} className="p-5 space-y-5">
                        <Box className="rounded-xl bg-slate-50 border border-slate-200 p-4 space-y-3 text-sm text-slate-700">
                            <Text className="font-semibold text-slate-800">
                                {t("Transfer to one of the following methods:")}
                            </Text>

                            {/* Bank transfer */}
                            {paymentInfo?.bankAccountNumber && (
                                <Box className="flex items-start gap-3">
                                    <Landmark className="h-4 w-4 text-slate-400 shrink-0 mt-0.5" />
                                    <Box>
                                        <Text className="font-medium">{paymentInfo.bankName}</Text>
                                        <Text className="font-mono text-base font-bold tracking-widest text-slate-900">
                                            {paymentInfo.bankAccountNumber}
                                        </Text>
                                        <Text className="text-xs text-slate-500">{paymentInfo.bankAccountName}</Text>
                                    </Box>
                                </Box>
                            )}

                            {/* QRIS */}
                            {paymentInfo?.qrisImageUrl && (
                                <Box className="flex items-start gap-3">
                                    <QrCode className="h-4 w-4 text-slate-400 shrink-0 mt-0.5" />
                                    <Box className="space-y-2">
                                        <Text className="font-medium">QRIS</Text>
                                        <img
                                            src={paymentInfo.qrisImageUrl}
                                            alt="QRIS"
                                            className="w-40 h-40 object-contain border border-slate-200 rounded-lg"
                                        />
                                    </Box>
                                </Box>
                            )}

                            <Text className="text-xs text-slate-500 pt-1">
                                {t("Transfer amount:")} <span className="font-bold text-slate-700">{priceFormatted}</span>
                            </Text>
                        </Box>

                        {/* File upload */}
                        <Box className="space-y-2">
                            <Text className="text-sm font-medium text-slate-700">
                                {t("Payment proof")} <span className="text-rose-500">*</span>
                            </Text>
                            <Box
                                className="relative border-2 border-dashed border-slate-300 hover:border-secondary-400 rounded-xl p-6 text-center cursor-pointer transition-colors"
                                onClick={() => fileInputRef.current?.click()}
                            >
                                <Upload className="h-6 w-6 text-slate-400 mx-auto mb-2" />
                                {selectedFile ? (
                                    <Text className="text-sm font-medium text-slate-700">{selectedFile.name}</Text>
                                ) : (
                                    <>
                                        <Text className="text-sm text-slate-500">{t("Click to choose a file")}</Text>
                                        <Text className="text-xs text-slate-400 mt-1">{t("JPG, PNG, or PDF · Max. 5 MB")}</Text>
                                    </>
                                )}
                                <input
                                    ref={fileInputRef}
                                    type="file"
                                    accept="image/jpeg,image/png,application/pdf"
                                    onChange={handleFileChange}
                                    className="sr-only"
                                />
                            </Box>
                            {proofForm.errors.payment_proof && (
                                <Text className="text-xs text-rose-600">{proofForm.errors.payment_proof}</Text>
                            )}
                        </Box>

                        <Button
                            type="submit"
                            variant="primary"
                            size="lg"
                            className="w-full"
                            disabled={proofForm.processing}
                        >
                            {proofForm.processing ? t("Uploading…") : t("Send Payment Proof")}
                        </Button>
                    </Box>
                )}

                {/* Step: Done */}
                {step === "done" && (
                    <Box className="p-5 space-y-5 text-center">
                        <Box className="flex justify-center">
                            <CheckCircle2 className="h-14 w-14 text-emerald-500" />
                        </Box>
                        <Box className="space-y-2">
                            <Heading level={4} className="text-base font-bold text-slate-900">
                                {t("Payment proof sent!")}
                            </Heading>
                            <Text className="text-sm text-slate-500 leading-relaxed">
                                {t(
                                    "We will verify your payment within 1×24 hours. You will receive a confirmation email once your registration is confirmed.",
                                )}
                            </Text>
                        </Box>
                        <Button variant="primary" size="lg" className="w-full" onClick={handleClose}>
                            {t("Close")}
                        </Button>
                    </Box>
                )}
            </>
        </Modal>
    );
}
