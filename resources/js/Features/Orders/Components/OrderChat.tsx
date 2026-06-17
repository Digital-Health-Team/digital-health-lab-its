import { FormEvent, useEffect, useRef } from "react";
import { router, useForm } from "@inertiajs/react";
import { MessagesSquare, Send } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import { Card, CardBody, CardHeader, CardTitle } from "@/Core/Components/Shared";
import Button from "@/Core/Components/Shared/Button/Button";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { type OrderDetail } from "@/Features/Orders/Types/order.type";

export default function OrderChat({ order }: { order: OrderDetail }) {
    const { t } = useTranslation();
    const threadRef = useRef<HTMLDivElement>(null);

    const { data, setData, post, processing, errors, reset } = useForm({ body: "" });

    // Auto-scroll to the latest message.
    useEffect(() => {
        if (threadRef.current) {
            threadRef.current.scrollTop = threadRef.current.scrollHeight;
        }
    }, [order.messages.length]);

    // Live receive: refetch the thread when the admin sends a message.
    useEffect(() => {
        const echo = (window as any).Echo;
        if (!echo) return;

        const channel = `booking.${order.id}`;
        echo.private(channel).listen("BookingMessageSent", () => {
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

    return (
        <Card>
            <CardHeader className="pb-2">
                <CardTitle className="flex items-center gap-2 text-base">
                    <MessagesSquare className="h-4 w-4 text-slate-400" />
                    {t("Consultation")}
                </CardTitle>
                <Text variant="small" className="text-slate-400">
                    {t("Chat with the admin about this order")}
                </Text>
            </CardHeader>
            <CardBody className="pt-0 space-y-3">
                <Box
                    ref={threadRef}
                    className="max-h-96 overflow-y-auto space-y-3 rounded-xl bg-slate-50/60 p-3"
                >
                    {order.messages.length === 0 ? (
                        <Box className="py-8 text-center">
                            <Text variant="small" className="text-slate-400">
                                {t("No messages yet. Start the conversation with the admin.")}
                            </Text>
                        </Box>
                    ) : (
                        order.messages.map((message) => (
                            <Box
                                key={message.id}
                                className={`flex ${message.isMine ? "justify-end" : "justify-start"}`}
                            >
                                <Box
                                    className={`max-w-[80%] rounded-2xl px-3.5 py-2 ${
                                        message.isMine
                                            ? "bg-[#00426D] text-white rounded-br-sm"
                                            : "bg-white border border-slate-200 text-slate-700 rounded-bl-sm"
                                    }`}
                                >
                                    <Text
                                        as="span"
                                        className={`block text-[10px] font-semibold uppercase tracking-wide ${
                                            message.isMine ? "text-white/70" : "text-slate-400"
                                        }`}
                                    >
                                        {message.isMine ? t("You") : message.senderName ?? t("Admin")}
                                    </Text>
                                    <Text as="span" className="block text-sm whitespace-pre-line break-words">
                                        {message.body}
                                    </Text>
                                    {message.createdAt && (
                                        <Text
                                            as="span"
                                            className={`block text-[10px] mt-0.5 text-right ${
                                                message.isMine ? "text-white/60" : "text-slate-400"
                                            }`}
                                        >
                                            {message.createdAt}
                                        </Text>
                                    )}
                                </Box>
                            </Box>
                        ))
                    )}
                </Box>

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
