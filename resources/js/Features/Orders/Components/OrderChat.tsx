import { FormEvent, useEffect, useRef, useState } from "react";
import { router, useForm } from "@inertiajs/react";
import { ChevronDown, MessagesSquare, Send } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { Card, CardBody, CardHeader, CardTitle } from "@/Core/Components/Shared";
import Button from "@/Core/Components/Shared/Button/Button";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { localeTag } from "@/Core/Utils/locale";
import { type OrderDetail } from "@/Features/Orders/Types/order.type";

function formatTime(dt: string | null, tag: string): string {
    if (!dt) return "";
    const d = new Date(dt);
    const now = new Date();
    const isToday = d.toDateString() === now.toDateString();
    const time = d.toLocaleTimeString(tag, { hour: "2-digit", minute: "2-digit" });
    return isToday
        ? time
        : d.toLocaleDateString(tag, { day: "2-digit", month: "short" }) + ", " + time;
}

function initials(name: string | null): string {
    if (!name) return "A";
    return name
        .split(" ")
        .slice(0, 2)
        .map((w) => w[0]?.toUpperCase() ?? "")
        .join("");
}

// Only id + messages are read, so the consultation thread can reuse this as-is.
export default function OrderChat({ order }: { order: Pick<OrderDetail, "id" | "messages"> }) {
    const { t, lang } = useTranslation();
    const threadRef = useRef<HTMLDivElement>(null);

    const { data, setData, post, processing, errors, reset } = useForm({ body: "" });
    const [newMessageAlert, setNewMessageAlert] = useState(false);
    const [showScrollBtn, setShowScrollBtn] = useState(false);

    // Auto-scroll to the latest message.
    useEffect(() => {
        if (threadRef.current) {
            threadRef.current.scrollTop = threadRef.current.scrollHeight;
        }
    }, [order.messages.length]);

    // Dismiss the new-message alert after messages reload.
    useEffect(() => {
        if (!newMessageAlert) return;
        const t = setTimeout(() => setNewMessageAlert(false), 3500);
        return () => clearTimeout(t);
    }, [order.messages.length]);

    // Live receive: show alert + refetch the thread when the admin sends a message.
    useEffect(() => {
        const echo = (window as any).Echo;
        if (!echo) return;

        const channel = `booking.${order.id}`;
        echo.private(channel).listen("BookingMessageSent", () => {
            setNewMessageAlert(true);
            router.reload({ only: ["order"] });
        });

        return () => echo.leave(channel);
    }, [order.id]);

    function handleSubmit(e: FormEvent) {
        e.preventDefault();
        if (!data.body.trim()) return;
        post(`/orders/${order.id}/messages`, {
            preserveScroll: true,
            onSuccess: () => reset(),
        });
    }

    function scrollToBottom() {
        threadRef.current?.scrollTo({ top: threadRef.current.scrollHeight, behavior: "smooth" });
    }

    return (
        <Card>
            <CardHeader className="pb-2">
                <CardTitle className="flex items-center gap-2 text-base">
                    <MessagesSquare className="h-4 w-4 text-[#00A8B5]" />
                    {t("Consultation")}
                    {order.messages.length > 0 && (
                        <Box as="span" className="ml-auto text-xs font-normal text-slate-400">
                            {order.messages.length} {t("messages")}
                        </Box>
                    )}
                </CardTitle>
                <Box className="flex items-center gap-1.5 mt-0.5">
                    <Box as="span" className="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse" />
                    <Text variant="small" className="text-slate-400">
                        {t("Live consultation with the lab team")}
                    </Text>
                </Box>
            </CardHeader>

            <CardBody className="pt-0 space-y-3">
                {/* New message alert banner */}
                {newMessageAlert && (
                    <Box className="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-gradient-to-r from-[#00426D] to-[#00A8B5] text-white text-sm font-semibold shadow-lg shadow-[#00426D]/20">
                        <MessagesSquare className="h-4 w-4 animate-bounce shrink-0" />
                        {t("New message from Admin")}
                    </Box>
                )}

                {/* Thread */}
                <Box className="relative">
                    <Box
                        ref={threadRef}
                        className="max-h-96 overflow-y-auto space-y-3 rounded-xl bg-gradient-to-b from-slate-50 to-slate-50/40 p-3 border border-slate-100"
                        onScroll={(e) => {
                            const el = e.currentTarget as HTMLElement;
                            setShowScrollBtn(el.scrollHeight - el.scrollTop - el.clientHeight > 60);
                        }}
                    >
                        {order.messages.length === 0 ? (
                            <Box className="py-12 flex flex-col items-center gap-2 text-center">
                                <MessagesSquare className="h-10 w-10 text-slate-200" />
                                <Text variant="small" className="text-slate-400 font-medium">
                                    {t("No messages yet.")}
                                </Text>
                                <Text variant="small" className="text-slate-400 opacity-70">
                                    {t("Start the conversation with the admin.")}
                                </Text>
                            </Box>
                        ) : (
                            order.messages.map((message) => (
                                <Box
                                    key={message.id}
                                    className={`flex items-end gap-2 ${message.isMine ? "justify-end" : "justify-start"}`}
                                >
                                    {/* Avatar (admin, left) */}
                                    {!message.isMine && (
                                        <Box className="shrink-0 h-7 w-7 rounded-full bg-gradient-to-br from-[#00426D] to-[#00A8B5] flex items-center justify-center text-[10px] font-bold text-white shadow-sm">
                                            {initials(message.senderName)}
                                        </Box>
                                    )}

                                    <Box
                                        className={`max-w-[78%] px-3.5 py-2.5 ${
                                            message.isMine
                                                ? "bg-gradient-to-br from-[#00426D] to-[#00A8B5] text-white rounded-2xl rounded-br-sm shadow-md shadow-[#00426D]/20"
                                                : "bg-white border border-slate-200 text-slate-700 rounded-2xl rounded-bl-sm shadow-sm"
                                        }`}
                                    >
                                        <Text
                                            as="span"
                                            className={`block text-[10px] font-bold uppercase tracking-wide mb-0.5 ${
                                                message.isMine ? "text-white/70" : "text-[#00A8B5]"
                                            }`}
                                        >
                                            {message.isMine ? t("You") : (message.senderName ?? t("Admin"))}
                                        </Text>
                                        <Text as="span" className="block text-sm whitespace-pre-line break-words">
                                            {message.body}
                                        </Text>
                                        {message.createdAt && (
                                            <Text
                                                as="span"
                                                className={`block text-[10px] mt-1 text-right ${
                                                    message.isMine ? "text-white/60" : "text-slate-400"
                                                }`}
                                            >
                                                {formatTime(message.createdAt, localeTag(lang))}
                                            </Text>
                                        )}
                                    </Box>

                                    {/* Avatar (user, right) */}
                                    {message.isMine && (
                                        <Box className="shrink-0 h-7 w-7 rounded-full bg-[#00426D] flex items-center justify-center text-[10px] font-bold text-white shadow-sm">
                                            {t("Me")[0]}
                                        </Box>
                                    )}
                                </Box>
                            ))
                        )}
                    </Box>

                    {/* Floating scroll-to-bottom button */}
                    {showScrollBtn && (
                        <button
                            type="button"
                            onClick={scrollToBottom}
                            className="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#00426D] text-white text-xs font-semibold shadow-lg hover:bg-[#00426D]/90 transition-all"
                        >
                            <ChevronDown className="h-3.5 w-3.5" />
                            {t("Scroll to latest")}
                        </button>
                    )}
                </Box>

                {/* Compose form */}
                <form onSubmit={handleSubmit} className="space-y-2">
                    <textarea
                        value={data.body}
                        onChange={(e) => setData("body", e.target.value)}
                        rows={2}
                        placeholder={t("Type a message…")}
                        className="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 placeholder-slate-400 bg-white focus:outline-none focus:border-[#00426D] focus:ring-1 focus:ring-[#00426D] transition-colors duration-150 resize-none"
                    />
                    {errors.body && (
                        <Text as="span" className="block text-xs text-red-500">
                            {errors.body}
                        </Text>
                    )}
                    <Box className="flex justify-end">
                        <Button type="submit" loading={processing} disabled={processing}>
                            <Send className="h-4 w-4" />
                            {t("Send")}
                        </Button>
                    </Box>
                </form>
            </CardBody>
        </Card>
    );
}
