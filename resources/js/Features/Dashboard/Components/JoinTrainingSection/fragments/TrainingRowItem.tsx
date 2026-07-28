import { Link } from "@inertiajs/react";
import { Star, Clock, Users, Signal } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Text } from "@/Core/Components/Common/Text";
import Button from "@/Core/Components/Shared/Button/Button";
import { type Course } from "@/Features/Training/Types/course.type";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { cn } from "@/Core/Utils/utils";

interface TrainingRowItemProps {
    course: Course;
}

export default function TrainingRowItem({ course }: TrainingRowItemProps) {
    const { t } = useTranslation();

    const priceLabel = course.isPaid
        ? `Rp ${course.price.toLocaleString("id-ID")}`
        : t("Free");

    return (
        <Box className="flex items-center gap-4 py-4 px-5 hover:bg-slate-50 transition-colors duration-150 group"
             style={{ transitionTimingFunction: "cubic-bezier(0.25,1,0.5,1)" }}>
            {/* Thumbnail */}
            <Box className="shrink-0 w-14 h-14 rounded-lg overflow-hidden ring-1 ring-primary-900/10">
                {course.thumbnailUrl ? (
                    <Box
                        className="w-full h-full"
                        style={{
                            backgroundImage: `url(${course.thumbnailUrl})`,
                            backgroundSize: "cover",
                            backgroundPosition: "center",
                        }}
                    />
                ) : (
                    <Box className="w-full h-full bg-gradient-to-br from-primary-800 to-secondary-600/80" />
                )}
            </Box>

            {/* Body */}
            <Box className="flex-1 min-w-0">
                <Text
                    as="p"
                    className={cn(
                        "text-sm font-bold text-slate-800 line-clamp-1 mb-1.5",
                        "group-hover:text-primary-700 transition-colors duration-150",
                    )}
                >
                    {course.title}
                </Text>
                <Box className="flex flex-wrap items-center gap-x-3 gap-y-1">
                    {course.duration && (
                        <Box className="flex items-center gap-1 text-slate-500">
                            <Clock className="h-3 w-3 shrink-0" />
                            <Text as="span" className="text-xs text-slate-500">{course.duration}</Text>
                        </Box>
                    )}
                    {course.rating !== undefined && (
                        <Box className="flex items-center gap-0.5">
                            <Star className="h-3 w-3 text-accent-400 fill-accent-400 shrink-0" />
                            <Text as="span" className="text-xs font-semibold text-slate-700">
                                {course.rating.toFixed(1)}
                            </Text>
                        </Box>
                    )}
                    <Box className="flex items-center gap-1 text-slate-500">
                        <Signal className="h-3 w-3 shrink-0" />
                        <Text as="span" className="text-xs text-slate-500">{course.level}</Text>
                    </Box>
                    {course.students && (
                        <Box className="flex items-center gap-1 text-slate-500">
                            <Users className="h-3 w-3 shrink-0" />
                            <Text as="span" className="text-xs text-slate-500">{course.students} {t("participants")}</Text>
                        </Box>
                    )}
                </Box>
            </Box>

            {/* Price + CTA */}
            <Box className="shrink-0 flex flex-col items-end gap-2">
                <Text as="span" className="text-xs font-semibold text-secondary-600">
                    {priceLabel}
                </Text>
                <Link href={course.href}>
                    <Button
                        variant="ghost"
                        size="sm"
                        className="text-xs border border-secondary-200 hover:bg-secondary-50 hover:border-secondary-400 transition-all duration-150"
                    >
                        {t("Learn now")}
                    </Button>
                </Link>
            </Box>
        </Box>
    );
}
