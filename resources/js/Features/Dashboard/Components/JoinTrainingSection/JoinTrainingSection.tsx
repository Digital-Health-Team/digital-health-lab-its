import { useState, useMemo } from "react";
import { Link } from "@inertiajs/react";
import {
    Layers, Settings, Heart, Activity, Scan,
    FlaskConical, Lightbulb, Shield, BarChart2, Cpu,
    GraduationCap, type LucideIcon,
} from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Image } from "@/Core/Components/Common/Image";
import { Card, CardBody, Button } from "@/Core/Components/Shared";
import TrainingRowItem from "./fragments/TrainingRowItem";
import { type Course } from "@/Features/Training/Types/course.type";
import { useTranslation } from "@/Core/Hooks/useTranslation";
import { cn } from "@/Core/Utils/utils";

interface JoinTrainingSectionProps {
    trainings: Course[];
}

const CATEGORY_ICONS: Record<string, LucideIcon> = {
    "3D Printing Fundamentals": Layers,
    "Digital Fabrication": Settings,
    "Biomedical Design": Heart,
    "Signal Processing": Activity,
    "Medical Imaging": Scan,
    "Materials Science": FlaskConical,
    "Health Innovation": Lightbulb,
    "Medical Regulation": Shield,
    "Data Science": BarChart2,
    "Embedded Systems": Cpu,
};

const ALL_KEY = "__all__";

export default function JoinTrainingSection({ trainings }: JoinTrainingSectionProps) {
    const { t } = useTranslation();

    // Derive ordered unique categories from the data
    const categories = useMemo(() => {
        const seen = new Set<string>();
        return trainings
            .map((c) => c.category ?? "")
            .filter((cat) => cat && !seen.has(cat) && seen.add(cat));
    }, [trainings]);

    const [activeCategory, setActiveCategory] = useState<string>(ALL_KEY);
    const [heroImageError, setHeroImageError] = useState(false);

    const filtered = useMemo(() => {
        if (activeCategory === ALL_KEY) return trainings;
        return trainings.filter((c) => c.category === activeCategory);
    }, [trainings, activeCategory]);

    if (trainings.length === 0) return null;

    return (
        <section aria-labelledby="training-heading">
            {/* Section title */}
            <Heading
                level={2}
                id="training-heading"
                className="font-display text-2xl font-bold text-slate-800 text-center mb-2"
            >
                {t("Join Our Training!")}
            </Heading>
            <Text className="text-sm text-slate-500 text-center mb-6">
                {t("Learn medical technology skills directly from IDIG Lab experts.")}
            </Text>

            {/* Illustration */}
            <Box className="relative h-64 sm:h-80 w-full mb-8 rounded-2xl overflow-hidden">
                {heroImageError ? (
                    <Box className="w-full h-full bg-gradient-to-br from-primary-900 via-primary-800 to-secondary-600/60 flex items-center justify-center">
                        <GraduationCap className="h-16 w-16 text-white/30" />
                    </Box>
                ) : (
                    <Image
                        src="/assets/images/training/hero_bg.png"
                        alt={t("Training illustration")}
                        className="w-full h-full object-cover object-center"
                        onError={() => setHeroImageError(true)}
                    />
                )}
                <Box className="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-slate-900/20 to-transparent" />
            </Box>

            {/* Two-column layout: sidebar + course list */}
            <Card>
                <CardBody className="p-0 overflow-hidden">
                    <Box className="flex flex-col sm:flex-row min-h-[420px]">
                        {/* Left: interactive category sidebar */}
                        <Box
                            as="nav"
                            aria-label={t("Training categories")}
                            className="sm:w-52 shrink-0 border-b sm:border-b-0 sm:border-r border-slate-100 py-3 sm:py-4"
                        >
                            {/* All categories */}
                            <button
                                type="button"
                                onClick={() => setActiveCategory(ALL_KEY)}
                                className={cn(
                                    "w-full flex items-center gap-3 px-4 py-2.5 text-left",
                                    "text-sm font-medium transition-colors duration-150",
                                    activeCategory === ALL_KEY
                                        ? "text-secondary-600 bg-secondary-50 font-semibold"
                                        : "text-slate-600 hover:bg-slate-50 hover:text-slate-800",
                                )}
                                style={{ transitionTimingFunction: "cubic-bezier(0.25,1,0.5,1)" }}
                            >
                                <Box
                                    className={cn(
                                        "shrink-0 w-7 h-7 rounded-lg flex items-center justify-center",
                                        activeCategory === ALL_KEY
                                            ? "bg-secondary-100 text-secondary-600"
                                            : "bg-slate-100 text-slate-400",
                                    )}
                                >
                                    <GraduationCap className="h-3.5 w-3.5" />
                                </Box>
                                <Text as="span" className="text-[13px] leading-tight">
                                    {t("All")}
                                </Text>
                            </button>

                            {/* Per-category buttons */}
                            {categories.map((cat) => {
                                const Icon = CATEGORY_ICONS[cat] ?? GraduationCap;
                                const isActive = activeCategory === cat;
                                return (
                                    <button
                                        key={cat}
                                        type="button"
                                        onClick={() => setActiveCategory(cat)}
                                        className={cn(
                                            "w-full flex items-center gap-3 px-4 py-2.5 text-left",
                                            "text-sm transition-colors duration-150",
                                            isActive
                                                ? "text-secondary-600 bg-secondary-50 font-semibold"
                                                : "text-slate-600 hover:bg-slate-50 hover:text-slate-800",
                                        )}
                                        style={{ transitionTimingFunction: "cubic-bezier(0.25,1,0.5,1)" }}
                                    >
                                        <Box
                                            className={cn(
                                                "shrink-0 w-7 h-7 rounded-lg flex items-center justify-center",
                                                isActive
                                                    ? "bg-secondary-100 text-secondary-600"
                                                    : "bg-slate-100 text-slate-400",
                                            )}
                                        >
                                            <Icon className="h-3.5 w-3.5" />
                                        </Box>
                                        <Text as="span" className="text-[13px] leading-tight line-clamp-2">
                                            {cat}
                                        </Text>
                                    </button>
                                );
                            })}
                        </Box>

                        {/* Right: filtered course list */}
                        <Box className="flex-1 min-w-0 divide-y divide-slate-100">
                            {filtered.length > 0 ? (
                                filtered.map((course) => (
                                    <TrainingRowItem key={course.id} course={course} />
                                ))
                            ) : (
                                <Box className="flex flex-col items-center justify-center py-16 text-center px-6">
                                    <GraduationCap className="h-8 w-8 text-slate-300 mb-3" />
                                    <Text className="text-sm text-slate-500 font-medium">
                                        {t("No trainings in this category yet.")}
                                    </Text>
                                    <Text className="text-xs text-slate-400 mt-1">
                                        {t("Check back later.")}
                                    </Text>
                                </Box>
                            )}
                        </Box>
                    </Box>

                    {/* Footer CTA */}
                    <Box className="flex justify-center py-6 border-t border-slate-100 bg-slate-50/50">
                        <Link href="/training">
                            <Button
                                variant="primary"
                                size="lg"
                                className="shadow-md shadow-secondary-500/20 hover:shadow-secondary-500/40 transition-shadow duration-200"
                            >
                                {t("See more!")}
                            </Button>
                        </Link>
                    </Box>
                </CardBody>
            </Card>
        </section>
    );
}
