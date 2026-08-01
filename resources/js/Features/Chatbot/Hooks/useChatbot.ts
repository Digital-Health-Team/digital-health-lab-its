import { useCallback, useRef, useState } from "react";
import { ask } from "@/routes/chatbot";
import { type ChatMessage, type ChatbotResponse } from "../Types/chatbot.type";

/**
 * Server caps: 12 history entries, 5,000 characters each. Trimming here rather than letting
 * the request come back 422 — the transcript that would break it is one we generated.
 */
const MAX_HISTORY_ENTRIES = 12;
const MAX_HISTORY_CHARS = 5000;

export type ChatbotError = "rate_limited" | "unavailable";

let messageCounter = 0;

function nextId(): string {
    messageCounter += 1;

    return `m${messageCounter}`;
}

function csrfToken(): string {
    return document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? "";
}

export function useChatbot() {
    const [messages, setMessages] = useState<ChatMessage[]>([]);
    const [pending, setPending] = useState(false);
    const [error, setError] = useState<ChatbotError | null>(null);
    const [serverMessage, setServerMessage] = useState<string | null>(null);
    // Read inside send() so a rapid second submit sees the first question, which state
    // captured in the closure would not.
    const messagesRef = useRef<ChatMessage[]>([]);

    messagesRef.current = messages;

    const send = useCallback(async (text: string) => {
        const question = text.trim();

        if (question.length < 2 || pending) {
            return;
        }

        const history = messagesRef.current
            .slice(-MAX_HISTORY_ENTRIES)
            .map((message) => ({
                role: message.role,
                content: message.content.slice(0, MAX_HISTORY_CHARS),
            }));

        setMessages((current) => [...current, { id: nextId(), role: "user", content: question }]);
        setError(null);
        setServerMessage(null);
        setPending(true);

        try {
            const response = await fetch(ask.url(), {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": csrfToken(),
                },
                body: JSON.stringify({ message: question, history }),
            });

            // 429 comes from the throttle middleware and needs its own wording: "you asked
            // too often" is a different instruction to the user than "something broke".
            if (response.status === 429) {
                setError("rate_limited");

                return;
            }

            const data: ChatbotResponse | null = await response.json().catch(() => null);

            if (!response.ok || !data) {
                setError("unavailable");
                // The 503 body already carries locale-correct wording; prefer it.
                setServerMessage(data?.answer ?? null);

                return;
            }

            setMessages((current) => [
                ...current,
                { id: nextId(), role: "assistant", content: data.answer, sources: data.sources },
            ]);
        } catch {
            setError("unavailable");
        } finally {
            setPending(false);
        }
    }, [pending]);

    return { messages, pending, error, serverMessage, send };
}
