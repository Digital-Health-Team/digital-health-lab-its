import { useState, FormEvent } from "react";

type FormState = {
    name: string;
    email: string;
    message: string;
};

type FormErrors = Partial<FormState>;

type Status = "idle" | "submitting" | "success";

function CheckIcon() {
    return (
        <svg
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth={1.75}
            className="w-6 h-6"
            aria-hidden="true"
        >
            <path
                strokeLinecap="round"
                strokeLinejoin="round"
                d="M5 13l4 4L19 7"
            />
        </svg>
    );
}

function ArrowIcon() {
    return (
        <svg
            viewBox="0 0 20 20"
            fill="currentColor"
            className="w-4 h-4 translate-x-0 group-hover:translate-x-1 transition-transform duration-200"
            aria-hidden="true"
        >
            <path
                fillRule="evenodd"
                d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z"
                clipRule="evenodd"
            />
        </svg>
    );
}

const fieldBase =
    "w-full bg-white/[0.08] border border-white/[0.18] rounded-lg px-4 py-3.5 " +
    "font-body text-white/92 placeholder:text-white/38 outline-none " +
    "transition-all duration-200";

const fieldNormal =
    "hover:bg-white/[0.11] hover:border-white/[0.28] " +
    "focus:bg-white/[0.11] focus:border-secondary-400 focus:ring-1 focus:ring-secondary-400/20";

const fieldError =
    "border-red-400/55 bg-red-400/[0.06] focus:border-red-400/70 focus:ring-1 focus:ring-red-400/15";

export default function ContactForm() {
    const [form, setForm] = useState<FormState>({
        name: "",
        email: "",
        message: "",
    });
    const [errors, setErrors] = useState<FormErrors>({});
    const [status, setStatus] = useState<Status>("idle");

    function validate(): boolean {
        const errs: FormErrors = {};
        if (!form.name.trim()) errs.name = "Nama wajib diisi.";
        if (!form.email.trim()) errs.email = "Email wajib diisi.";
        else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email))
            errs.email = "Format email tidak valid.";
        if (!form.message.trim()) errs.message = "Pesan wajib diisi.";
        setErrors(errs);
        return Object.keys(errs).length === 0;
    }

    async function handleSubmit(e: FormEvent<HTMLFormElement>) {
        e.preventDefault();
        if (!validate()) return;
        setStatus("submitting");
        await new Promise<void>((resolve) => setTimeout(resolve, 1200));
        setStatus("success");
    }

    if (status === "success") {
        return (
            <div className="cs-form flex flex-col justify-center min-h-[420px] gap-7">
                <div className="w-14 h-14 rounded-full border border-secondary-400/40 flex items-center justify-center text-secondary-400">
                    <CheckIcon />
                </div>
                <div className="space-y-2">
                    <h3
                        className="font-display font-bold text-white"
                        style={{
                            fontSize: "clamp(1.25rem, 2vw, 1.5rem)",
                        }}
                    >
                        Pesan Terkirim
                    </h3>
                    <p
                        className="font-body text-white/55 leading-relaxed"
                        style={{
                            fontSize: "clamp(0.875rem, 1.1vw, 0.95rem)",
                            maxWidth: "34ch",
                        }}
                    >
                        Terima kasih. Kami akan menghubungi Anda segera melalui email yang Anda berikan.
                    </p>
                </div>
                <div className="h-px bg-white/10 w-20" />
                <p className="font-body text-white/30 text-xs tracking-wide">
                    Message sent — we'll be in touch.
                </p>
            </div>
        );
    }

    return (
        <form
            onSubmit={handleSubmit}
            noValidate
            className="cs-form space-y-8"
            aria-label="Formulir kontak"
        >
            {/* Name */}
            <div className="space-y-2">
                <label
                    htmlFor="cs-name"
                    className="block font-body font-medium uppercase tracking-[0.12em] text-white/70"
                    style={{ fontSize: "0.63rem" }}
                >
                    Nama Lengkap{" "}
                    <span className="text-white/40" aria-hidden="true">
                        / Full Name
                    </span>
                </label>
                <input
                    id="cs-name"
                    type="text"
                    value={form.name}
                    onChange={(e) => {
                        setForm((prev) => ({ ...prev, name: e.target.value }));
                        if (errors.name) setErrors((prev) => ({ ...prev, name: undefined }));
                    }}
                    placeholder="Nama lengkap Anda"
                    autoComplete="name"
                    aria-invalid={!!errors.name}
                    aria-describedby={errors.name ? "cs-name-err" : undefined}
                    className={`${fieldBase} ${errors.name ? fieldError : fieldNormal}`}
                    style={{ fontSize: "clamp(0.9rem, 1.1vw, 0.98rem)" }}
                />
                {errors.name && (
                    <p
                        id="cs-name-err"
                        role="alert"
                        className="font-body text-red-400/80 text-xs"
                    >
                        {errors.name}
                    </p>
                )}
            </div>

            {/* Email */}
            <div className="space-y-2">
                <label
                    htmlFor="cs-email"
                    className="block font-body font-medium uppercase tracking-[0.12em] text-white/70"
                    style={{ fontSize: "0.63rem" }}
                >
                    Email{" "}
                    <span className="text-white/40" aria-hidden="true">
                        / Email Address
                    </span>
                </label>
                <input
                    id="cs-email"
                    type="email"
                    value={form.email}
                    onChange={(e) => {
                        setForm((prev) => ({ ...prev, email: e.target.value }));
                        if (errors.email) setErrors((prev) => ({ ...prev, email: undefined }));
                    }}
                    placeholder="email@contoh.com"
                    autoComplete="email"
                    aria-invalid={!!errors.email}
                    aria-describedby={errors.email ? "cs-email-err" : undefined}
                    className={`${fieldBase} ${errors.email ? fieldError : fieldNormal}`}
                    style={{ fontSize: "clamp(0.9rem, 1.1vw, 0.98rem)" }}
                />
                {errors.email && (
                    <p
                        id="cs-email-err"
                        role="alert"
                        className="font-body text-red-400/80 text-xs"
                    >
                        {errors.email}
                    </p>
                )}
            </div>

            {/* Message */}
            <div className="space-y-2">
                <label
                    htmlFor="cs-message"
                    className="block font-body font-medium uppercase tracking-[0.12em] text-white/70"
                    style={{ fontSize: "0.63rem" }}
                >
                    Pesan{" "}
                    <span className="text-white/40" aria-hidden="true">
                        / Message
                    </span>
                </label>
                <textarea
                    id="cs-message"
                    value={form.message}
                    onChange={(e) => {
                        setForm((prev) => ({ ...prev, message: e.target.value }));
                        if (errors.message) setErrors((prev) => ({ ...prev, message: undefined }));
                    }}
                    placeholder="Tuliskan pesan Anda..."
                    rows={5}
                    aria-invalid={!!errors.message}
                    aria-describedby={
                        errors.message ? "cs-message-err" : undefined
                    }
                    className={`${fieldBase} resize-none ${errors.message ? fieldError : fieldNormal}`}
                    style={{ fontSize: "clamp(0.9rem, 1.1vw, 0.98rem)" }}
                />
                {errors.message && (
                    <p
                        id="cs-message-err"
                        role="alert"
                        className="font-body text-red-400/80 text-xs"
                    >
                        {errors.message}
                    </p>
                )}
            </div>

            {/* Submit */}
            <div className="pt-2">
                <button
                    type="submit"
                    disabled={status === "submitting"}
                    className="group inline-flex items-center gap-3 font-body font-semibold text-white
                        bg-secondary-500 hover:bg-[#00909B] hover:-translate-y-px
                        disabled:opacity-50 disabled:cursor-not-allowed disabled:translate-y-0
                        rounded-xl px-10 py-4 transition-all duration-300 active:translate-y-0
                        focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-secondary-400"
                    style={{
                        fontSize: "clamp(0.875rem, 1.05vw, 0.95rem)",
                        boxShadow: "0 8px 24px rgba(0, 168, 181, 0.28)",
                    }}
                >
                    {status === "submitting" ? (
                        <>
                            <span
                                className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"
                                aria-hidden="true"
                            />
                            Mengirim...
                        </>
                    ) : (
                        <>
                            Kirim Pesan
                            <ArrowIcon />
                        </>
                    )}
                </button>
            </div>
        </form>
    );
}
