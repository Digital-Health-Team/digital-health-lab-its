import { Image } from "@/Core/Components/Common/Image";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Badge } from "@/Core/Components/Shared";
import { Clock, Users, BarChart2, Globe, Eye, Tag } from "lucide-react";
import TrainingRating from "./fragments/TrainingRating";
import { type TrainingDetail } from "@/Features/Training/Types/trainingDetail.type";

interface TrainingPreviewProps {
    training: TrainingDetail;
}

export default function TrainingPreview({ training }: TrainingPreviewProps) {
    return (
        <Box className="rounded-2xl overflow-hidden border border-slate-100 bg-white shadow-sm">
            {/* Hero image */}
            <Box className="relative h-48 sm:h-64 md:h-100 bg-slate-100">
                <Image
                    src={training.previewImageUrl ?? ''}
                    alt={training.title}
                    className="w-full h-full object-cover"
                />
                <Box className="absolute top-3 left-3">
                    <Badge className="bg-accent-400/90 text-white text-xs font-semibold px-2 py-0.5 rounded-full">
                        {training.level}
                    </Badge>
                </Box>
            </Box>

            {/* Info block */}
            <Box className="p-4 sm:p-5 space-y-4">
                <Heading level={2} className="text-xl font-bold text-slate-900 leading-snug">
                    {training.title}
                </Heading>
                <Text className="text-sm text-slate-500 leading-relaxed">
                    {training.subtitle}
                </Text>

                {/* Category badge */}
                {training.category && (
                    <Box className="flex items-center gap-1.5">
                        <Badge variant="tag" className="normal-case tracking-normal text-[11px]">
                            {training.category}
                        </Badge>
                        {training.extraTags != null && (
                            <Text as="span" className="text-xs text-slate-400 font-medium">
                                +{training.extraTags}
                            </Text>
                        )}
                    </Box>
                )}

                {training.rating != null && training.ratingCount != null && (
                    <TrainingRating
                        rating={training.rating}
                        ratingCount={training.ratingCount}
                        students={training.students ?? '0'}
                    />
                )}

                {/* Meta row */}
                <Box className="flex flex-wrap gap-3 sm:gap-4 pt-1">
                    {training.duration && (
                        <Box className="flex items-center gap-1.5 text-xs text-slate-500">
                            <Clock className="h-3.5 w-3.5 text-slate-400" />
                            <Text as="span">{training.duration}</Text>
                        </Box>
                    )}
                    {training.students && (
                    <Box className="flex items-center gap-1.5 text-xs text-slate-500">
                        <Users className="h-3.5 w-3.5 text-slate-400" />
                        <Text as="span">{training.students} students</Text>
                    </Box>
                    )}
                    <Box className="flex items-center gap-1.5 text-xs text-slate-500">
                        <BarChart2 className="h-3.5 w-3.5 text-slate-400" />
                        <Text as="span">{training.level}</Text>
                    </Box>
                    <Box className="flex items-center gap-1.5 text-xs text-slate-500">
                        <Globe className="h-3.5 w-3.5 text-slate-400" />
                        <Text as="span">{training.language}</Text>
                    </Box>
                    {training.views != null && (
                        <Box className="flex items-center gap-1.5 text-xs text-slate-500">
                            <Eye className="h-3.5 w-3.5 text-slate-400" />
                            <Text as="span">{training.views} views</Text>
                        </Box>
                    )}
                    {training.category && (
                        <Box className="flex items-center gap-1.5 text-xs text-slate-500">
                            <Tag className="h-3.5 w-3.5 text-slate-400" />
                            <Text as="span">{training.category}</Text>
                        </Box>
                    )}
                </Box>
            </Box>
        </Box>
    );
}
