import { type FormEvent, useEffect, useRef, useState } from "react";
import { Loader2, Send, X } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Button, Input } from "@/Core/Components/Shared";
import { useEscapeKey } from "@/Core/Hooks/useEscapeKey";
import { useMediaQuery } from "@/Core/Hooks/useMediaQuery";
import { type Locale } from "@/Core/Hooks/useTranslation";
import { cn } from "@/Core/Utils/utils";
import { chatbotCopy, chatbotSuggestions } from "../copy";
import { useChatbot } from "../Hooks/useChatbot";
import { type ChatbotVariant } from "../Types/chatbot.type";
import ChatbotMessage from "./ChatbotMessage";

interface ChatbotPanelProps {
    variant: ChatbotVariant;
    lang: Locale;
    panelId: string;
    titleId: string;
    onClose: () => void;
}

export default function ChatbotPanel({ variant, lang, panelId, titleId, onClose }: ChatbotPanelProps) {
    const { messages, pending, error, serverMessage, send } = useChatbot();
    const [draft, setDraft] = useState("");
    const transcriptRef = useRef<HTMLDivElement>(null);
    const reducedMotion = useMediaQuery("(prefers-reduced-motion: reduce)");

    const copy = chatbotCopy(lang);
    const suggestions = chatbotSuggestions(variant, lang);

    useEscapeKey(onClose);

    useEffect(() => {
        transcriptRef.current?.scrollTo({
            top: transcriptRef.current.scrollHeight,
            behavior: reducedMotion ? "auto" : "smooth",
        });
    }, [messages, pending, error, reducedMotion]);

    const submit = () => {
        void send(draft);
        setDraft("");
    };

    const errorText = error === "rate_limited"
        ? copy.rateLimited
        : serverMessage ?? copy.unavailable;

    return (
        <Box
            id={panelId}
            role="dialog"
            // Not aria-modal: the panel neither traps focus nor blocks the page, and
            // claiming otherwise makes a screen reader hide the rest of the document.
            aria-modal="false"
            aria-labelledby={titleId}
            className={cn(
                "flex flex-col overflow-hidden rounded-2xl border border-primary-800 bg-primary-950 shadow-card-elevated",
                variant === "panel"
                    ? "h-[min(38rem,calc(100vh-7rem))] w-[min(28rem,calc(100vw-2rem))]"
                    : "h-[min(32rem,calc(100vh-7rem))] w-[min(24rem,calc(100vw-2rem))]",
            )}
        >
            <Box className="flex items-start justify-between gap-3 border-b border-primary-800 px-4 py-3">
                <Box>
                    <Heading
                        level={2}
                        id={titleId}
                        className="font-display text-base font-semibold text-white"
                    >
                        {copy.title}
                    </Heading>
                    <Text variant="small" className="text-xs text-slate-400">
                        {copy.subtitle}
                    </Text>
                </Box>
                <Button
                    variant="icon"
                    type="button"
                    onClick={onClose}
                    aria-label={copy.close}
                    className="h-8 w-8 shrink-0 text-slate-400 hover:bg-primary-900 hover:text-white"
                >
                    <X aria-hidden="true" className="h-4 w-4" />
                </Button>
            </Box>

            <Box
                ref={transcriptRef}
                className="flex-1 overflow-y-auto px-4 py-4"
            >
                {messages.length === 0 && (
                    <Text variant="small" className="text-sm leading-relaxed text-slate-400">
                        {copy.intro}
                    </Text>
                )}

                {/* role=log + aria-live: an answer arrives without any focus change, so a
                    screen reader would otherwise never announce it. */}
                <Box
                    as="ul"
                    role="log"
                    aria-live="polite"
                    aria-label={copy.transcriptLabel}
                    className={cn("flex flex-col gap-4", messages.length > 0 && "mt-0")}
                >
                    {messages.map((message) => (
                        <ChatbotMessage
                            key={message.id}
                            message={message}
                            sourcesLabel={copy.sourcesLabel}
                        />
                    ))}

                    {pending && (
                        <Box as="li" className="flex items-center gap-2 text-slate-400">
                            <Loader2
                                aria-hidden="true"
                                className={cn("h-4 w-4", !reducedMotion && "animate-spin")}
                            />
                            <Text as="span" variant="small" className="text-xs">
                                {copy.thinking}
                            </Text>
                        </Box>
                    )}

                    {error !== null && (
                        <Box
                            as="li"
                            role="status"
                            className="rounded-xl border border-accent-400/40 bg-accent-400/10 px-3 py-2"
                        >
                            <Text variant="small" className="text-xs leading-relaxed text-accent-300">
                                {errorText}
                            </Text>
                        </Box>
                    )}
                </Box>

                {messages.length === 0 && (
                    <Box className="mt-4">
                        <Text
                            as="span"
                            variant="small"
                            className="text-xs font-semibold uppercase tracking-wide text-secondary-300/70"
                        >
                            {copy.suggestionsLabel}
                        </Text>
                        <Box as="ul" className="mt-2 flex flex-col gap-2">
                            {suggestions.map((suggestion) => (
                                <Box as="li" key={suggestion}>
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        type="button"
                                        onClick={() => void send(suggestion)}
                                        className="w-full justify-start rounded-xl border-primary-800 bg-primary-900/60 text-left text-xs font-normal text-slate-200 hover:border-secondary-400/50 hover:bg-primary-900 hover:text-white"
                                    >
                                        {suggestion}
                                    </Button>
                                </Box>
                            ))}
                        </Box>
                    </Box>
                )}
            </Box>

            <Box
                as="form"
                onSubmit={(event: FormEvent) => {
                    event.preventDefault();
                    submit();
                }}
                className="flex items-center gap-2 border-t border-primary-800 px-3 py-3"
            >
                <Input
                    // Shared Input is not a forwardRef component, so focus is claimed
                    // declaratively. Appropriate here: the panel only mounts because
                    // someone just clicked to open it.
                    autoFocus
                    value={draft}
                    onChange={(event) => setDraft(event.target.value)}
                    placeholder={copy.placeholder}
                    aria-label={copy.placeholder}
                    maxLength={500}
                    disabled={pending}
                    wrapperClassName="flex-1"
                    className="border-primary-800 bg-primary-900 text-slate-100 placeholder:text-slate-500 hover:bg-primary-900 focus:border-secondary-400 focus:bg-primary-900"
                />
                <Button
                    variant="primary"
                    size="sm"
                    type="submit"
                    aria-label={copy.send}
                    disabled={pending || draft.trim().length < 2}
                    className="h-10 w-10 shrink-0 rounded-full p-0"
                >
                    <Send aria-hidden="true" className="h-4 w-4" />
                </Button>
            </Box>
        </Box>
    );
}
