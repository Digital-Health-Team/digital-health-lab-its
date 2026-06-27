import { Link } from "@inertiajs/react";
import { Clock, Bookmark } from "lucide-react";
import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Card } from "@/Core/Components/Shared/Card/Card";
import Avatar from "@/Core/Components/Shared/Avatar/Avatar";
import Badge from "@/Core/Components/Shared/Badge/Badge";
import Button from "@/Core/Components/Shared/Button/Button";
import { type StaffPickFeature } from "@/Features/Training/Types/course.type";

interface StaffPickFeatureProps {
    data: StaffPickFeature;
}

export default function StaffPickFeatureCard({ data }: StaffPickFeatureProps) {
    return (
        <Link href={data.href} className="block group">
            <Card className="overflow-hidden flex flex-col md:flex-row">
                {/* Left: Thumbnail with Staff Pick badge */}
                <Box className="relative md:w-5/12 min-h-55 md:min-h-65 shrink-0">
                    <Box
                        className="absolute inset-0"
                        style={{
                            backgroundImage: `url(${data.thumbnailUrl})`,
                            backgroundSize: "cover",
                            backgroundPosition: "center",
                        }}
                    />
                    <Box className="absolute top-3 left-3 z-10">
                        <Badge className="bg-black/70 text-white border-transparent backdrop-blur-sm normal-case tracking-normal text-xs font-semibold px-3 py-1">
                            Staff Pick.
                        </Badge>
                    </Box>
                </Box>

                {/* Right: Content */}
                <Box className="flex flex-col justify-between p-6 flex-1">
                    <Heading
                        level={2}
                        className="font-display text-xl md:text-2xl font-bold text-slate-800 leading-snug mb-5 group-hover:text-primary-700 transition-colors"
                    >
                        {data.title}
                    </Heading>

                    {/* Instructor */}
                    <Box className="flex items-center gap-3 mb-6">
                        <Avatar
                            src={data.instructor.avatarUrl}
                            name={data.instructor.name}
                            size="lg"
                        />
                        <Box>
                            <Text as="span" className="text-sm font-semibold text-slate-800 block leading-none mb-1">
                                {data.instructor.name}
                            </Text>
                            {data.instructor.title && (
                                <Text as="span" className="text-xs text-slate-500 block leading-relaxed">
                                    {data.instructor.title}
                                </Text>
                            )}
                            <Text as="span" className="text-xs text-slate-500 block leading-relaxed">
                                {data.instructor.students} students
                            </Text>
                        </Box>
                    </Box>

                    {/* Footer */}
                    <Box className="flex items-center justify-between pt-4 border-t border-slate-100">
                        <Box className="flex items-center gap-1.5 text-slate-500">
                            <Clock className="h-4 w-4 shrink-0" />
                            <Text as="span" className="text-sm text-slate-500">{data.duration}</Text>
                        </Box>
                        <Button variant="icon" aria-label="Save course" onClick={(e) => e.preventDefault()}>
                            <Bookmark className="h-5 w-5 text-slate-400 hover:text-slate-600" />
                        </Button>
                    </Box>
                </Box>
            </Card>
        </Link>
    );
}
