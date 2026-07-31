import { Link } from "@inertiajs/react";
import { Star, Users, Clock, Bookmark, Signal } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Card } from "@/Core/Components/Shared/Card/Card";
import Avatar from "@/Core/Components/Shared/Avatar/Avatar";
import Badge from "@/Core/Components/Shared/Badge/Badge";
import Button from "@/Core/Components/Shared/Button/Button";
import { type Course } from "@/Features/Training/Types/course.type";

interface CourseCardProps {
    course: Course;
}

export default function CourseCard({ course }: CourseCardProps) {
    return (
        <Link href={course.href} className="block group h-full">
            <Card className="overflow-hidden flex flex-col h-full card-hover-lift">
                {/* Thumbnail */}
                <Box className="relative h-48 shrink-0">
                    <Box
                        className="absolute inset-0"
                        style={{
                            backgroundImage: `url(${course.thumbnailUrl})`,
                            backgroundSize: "cover",
                            backgroundPosition: "center",
                        }}
                    />
                    {course.staffPick && (
                        <Box className="absolute top-3 left-3 z-10">
                            <Badge className="bg-black/70 text-white border-transparent backdrop-blur-sm normal-case tracking-normal text-xs font-semibold px-3 py-1">
                                Staff Pick.
                            </Badge>
                        </Box>
                    )}
                </Box>

                {/* Body */}
                <Box className="flex flex-col flex-1 p-4">
                    {/* Instructor row + rating */}
                    <Box className="flex items-center gap-2 mb-2.5">
                        <Avatar
                            src={course.instructor.avatarUrl}
                            name={course.instructor.name}
                            size="sm"
                        />
                        {/* Name + verified badge grouped */}
                        <Box className="flex items-center gap-1 flex-1 min-w-0">
                            <Text as="span" className="text-xs font-medium text-slate-600 truncate">
                                {course.instructor.name}
                            </Text>
                            {course.instructor.verified && (
                                <Box className="shrink-0 h-4 w-4 rounded-full bg-emerald-500 flex items-center justify-center">
                                    <Text as="span" className="text-white text-[9px] font-bold leading-none">✓</Text>
                                </Box>
                            )}
                        </Box>
                        {/* Rating */}
                        <Box className="flex items-center gap-0.5 shrink-0">
                            <Star className="h-3 w-3 text-accent-400 fill-accent-400" />
                            <Text as="span" className="text-xs font-semibold text-slate-700">
                                {course.rating}
                            </Text>
                            <Text as="span" className="text-xs text-slate-400">
                                ({course.ratingCount})
                            </Text>
                        </Box>
                    </Box>

                    {/* Title */}
                    <Heading
                        level={4}
                        className="font-display text-base font-bold text-slate-800 leading-snug mb-3 line-clamp-2 group-hover:text-primary-700 transition-colors flex-1"
                    >
                        {course.title}
                    </Heading>

                    {/* Category tags */}
                    <Box className="flex items-center gap-1.5 mb-3">
                        <Badge variant="tag" className="normal-case tracking-normal text-[11px]">
                            {course.category}
                        </Badge>
                        {course.extraTags !== undefined && (
                            <Text as="span" className="text-xs text-slate-400 font-medium">
                                +{course.extraTags}
                            </Text>
                        )}
                    </Box>

                    {/* Footer meta */}
                    <Box className="flex items-center gap-2.5 pt-3 border-t border-slate-100">
                        <Box className="flex items-center gap-1 text-slate-500">
                            <Signal className="h-3 w-3 shrink-0" />
                            <Text as="span" className="text-[11px] text-slate-500">{course.level}</Text>
                        </Box>
                        <Box className="flex items-center gap-1 text-slate-500">
                            <Users className="h-3 w-3 shrink-0" />
                            <Text as="span" className="text-[11px] text-slate-500">{course.students}</Text>
                        </Box>
                        <Box className="flex items-center gap-1 text-slate-500">
                            <Clock className="h-3 w-3 shrink-0" />
                            <Text as="span" className="text-[11px] text-slate-500">{course.duration}</Text>
                        </Box>
                        <Button
                            variant="icon"
                            className="ml-auto"
                            aria-label="Save course"
                            onClick={(e) => e.preventDefault()}
                        >
                            <Bookmark className="h-4 w-4 text-slate-400 group-hover:text-slate-500" />
                        </Button>
                    </Box>
                </Box>
            </Card>
        </Link>
    );
}
