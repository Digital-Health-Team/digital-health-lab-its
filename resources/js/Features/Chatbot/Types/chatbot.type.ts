export type ChatRole = "user" | "assistant";

/** Mirrors ChatbotVariant on the mount side: floating launcher, or the dashboard panel. */
export type ChatbotVariant = "floating" | "panel";

export interface ChatSource {
    title: string;
    url: string;
}

export interface ChatMessage {
    id: string;
    role: ChatRole;
    content: string;
    /** Assistant only. Empty when the answer came from the fallback path. */
    sources?: ChatSource[];
}

/** Response body of POST /chatbot/ask. */
export interface ChatbotResponse {
    answer: string;
    sources: ChatSource[];
    answered: boolean;
}
