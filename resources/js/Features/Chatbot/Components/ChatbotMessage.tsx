import { ExternalLink } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { cn } from "@/Core/Utils/utils";
import { type ChatMessage } from "../Types/chatbot.type";

interface ChatbotMessageProps {
    message: ChatMessage;
    sourcesLabel: string;
}

export default function ChatbotMessage({ message, sourcesLabel }: ChatbotMessageProps) {
    const isUser = message.role === "user";
    const sources = message.sources ?? [];

    return (
        <Box
            as="li"
            className={cn("flex flex-col gap-2", isUser ? "items-end" : "items-start")}
        >
            <Box
                className={cn(
                    "max-w-[85%] rounded-2xl px-4 py-3",
                    isUser
                        ? "bg-secondary-500/20 border border-secondary-400/30 rounded-br-sm"
                        : "bg-primary-900/70 border border-primary-800 rounded-bl-sm",
                )}
            >
                {/* whitespace-pre-line keeps the model's paragraph breaks without rendering
                    its output as HTML. */}
                <Text
                    variant="small"
                    className="whitespace-pre-line text-[0.9375rem] leading-relaxed text-slate-100"
                >
                    {message.content}
                </Text>
            </Box>

            {sources.length > 0 && (
                <Box className="max-w-[85%]">
                    <Text
                        as="span"
                        variant="small"
                        className="text-xs font-semibold uppercase tracking-wide text-secondary-300/70"
                    >
                        {sourcesLabel}
                    </Text>
                    <Box as="ul" className="mt-1 flex flex-wrap gap-x-3 gap-y-1">
                        {sources.map((source) => (
                            <Box as="li" key={source.url}>
                                <Box
                                    as="a"
                                    href={source.url}
                                    className={cn(
                                        "inline-flex items-center gap-1 text-xs text-secondary-300 underline underline-offset-2",
                                        "hover:text-secondary-200",
                                        "focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-secondary-400/60 focus-visible:ring-offset-2 focus-visible:ring-offset-primary-950 rounded",
                                    )}
                                >
                                    {source.title}
                                    <ExternalLink aria-hidden="true" className="h-3 w-3" />
                                </Box>
                            </Box>
                        ))}
                    </Box>
                </Box>
            )}
        </Box>
    );
}
