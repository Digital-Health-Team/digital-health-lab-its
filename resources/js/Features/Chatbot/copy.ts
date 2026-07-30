import { type ChatbotVariant } from "./Types/chatbot.type";
import { type Locale } from "@/Core/Hooks/useTranslation";

/**
 * Widget copy, per locale.
 *
 * Not useTranslation()'s t(): its keys are the English source strings and lang/id.json
 * carries the Indonesian, so a key that is not in that file renders English. Indonesian is
 * the primary audience here, and adding a dozen keys to an 85KB shared translation file
 * would collide with the auto-translate workflow that maintains it.
 */
const copy = {
    id: {
        launch: "Buka asisten IDIG",
        close: "Tutup asisten",
        title: "Asisten IDIG",
        subtitle: "Menjawab dari dokumentasi lab",
        placeholder: "Tanyakan layanan, agenda, atau pesananmu",
        send: "Kirim pertanyaan",
        thinking: "Menyusun jawaban",
        intro: "Tanyakan apa saja tentang layanan cetak dan scan 3D, agenda, publikasi, atau pesananmu. Jawaban disusun dari dokumentasi lab.",
        suggestionsLabel: "Contoh pertanyaan",
        sourcesLabel: "Sumber",
        transcriptLabel: "Percakapan dengan asisten IDIG",
        rateLimited:
            "Pertanyaanmu terkirim terlalu sering. Tunggu sekitar satu menit, lalu coba lagi.",
        unavailable:
            "Asisten sedang tidak tersedia. Silakan coba lagi sebentar lagi, atau hubungi admin lab.",
    },
    en: {
        launch: "Open the IDIG assistant",
        close: "Close the assistant",
        title: "IDIG Assistant",
        subtitle: "Answers from the lab documentation",
        placeholder: "Ask about services, events, or your order",
        send: "Send question",
        thinking: "Composing an answer",
        intro: "Ask about 3D printing and scanning services, events, publications, or your own orders. Answers are drawn from the lab documentation.",
        suggestionsLabel: "Example questions",
        sourcesLabel: "Sources",
        transcriptLabel: "Conversation with the IDIG assistant",
        rateLimited: "That was too many questions too quickly. Wait about a minute and try again.",
        unavailable:
            "The assistant is unavailable right now. Please try again shortly, or contact the lab admin.",
    },
} as const;

/**
 * Starter prompts, shown only while the transcript is empty — a visitor who does not know
 * what the assistant covers asks nothing at all.
 *
 * The dashboard set deliberately points at knowledge/id/tahapan-status-pesanan.md and
 * kebijakan-pembayaran.md, which are the two documents that answer what people actually
 * ask once they have an order.
 */
const suggestions = {
    floating: {
        id: [
            "Bagaimana cara memesan cetak 3D?",
            "Berapa biaya cetak 3D per gram?",
            "Format berkas 3D apa yang diterima?",
            "Apa saja layanan Laboratorium IDIG?",
        ],
        en: [
            "How do I place a 3D printing order?",
            "How much does 3D printing cost per gram?",
            "Which 3D file formats are accepted?",
            "What services does the IDIG lab offer?",
        ],
    },
    panel: {
        id: [
            "Pesanan saya sampai tahap mana?",
            "Apa arti tahap Warehouse Check?",
            "Bagaimana cara mengunggah bukti pembayaran?",
            "Kapan sisa pembayaran harus dilunasi?",
        ],
        en: [
            "What stage is my order at?",
            "What does the Warehouse Check stage mean?",
            "How do I upload payment proof?",
            "When is the remaining balance due?",
        ],
    },
} as const;

export function chatbotCopy(lang: Locale) {
    return copy[lang] ?? copy.id;
}

export function chatbotSuggestions(variant: ChatbotVariant, lang: Locale): readonly string[] {
    return suggestions[variant][lang] ?? suggestions[variant].id;
}
