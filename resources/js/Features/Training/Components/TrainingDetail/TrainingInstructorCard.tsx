import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Image } from "@/Core/Components/Common/Image";
import { Avatar, Badge } from "@/Core/Components/Shared";
import { CheckCircle2, Users } from "lucide-react";
import { type InstructorWithStudents } from "@/Features/Training/Types/course.type";

interface TrainingInstructorCardProps {
    instructor: InstructorWithStudents;
}

export default function TrainingInstructorCard({ instructor }: TrainingInstructorCardProps) {
    return (
        <Box className="rounded-2xl border border-slate-100 bg-white shadow-sm p-5 space-y-4">
            <Heading level={4} className="text-base font-bold text-slate-900">
                Your instructor
            </Heading>

            <Box className="flex items-center gap-4">
                {/* Avatar */}
                <Box className="relative shrink-0">
                    {instructor.avatarUrl ? (
                        <Image
                            src={instructor.avatarUrl}
                            alt={instructor.name}
                            className="w-16 h-16 rounded-full object-cover border-2 border-slate-100"
                        />
                    ) : (
                        <Avatar name={instructor.name} size="xl" />
                    )}
                    {instructor.verified && (
                        <Box className="absolute -bottom-1 -right-1 bg-white rounded-full p-px shadow">
                            <CheckCircle2 className="h-4 w-4 text-secondary-500" />
                        </Box>
                    )}
                </Box>

                {/* Name + title */}
                <Box className="min-w-0">
                    <Box className="flex items-center gap-2 flex-wrap">
                        <Heading level={5} className="text-sm font-bold text-slate-900">
                            {instructor.name}
                        </Heading>
                        {instructor.verified && (
                            <Badge className="bg-secondary-50 text-secondary-600 text-xs px-2 py-0.5 rounded-full font-medium">
                                Verified
                            </Badge>
                        )}
                    </Box>
                    {instructor.title && (
                        <Text className="text-xs text-slate-500 mt-0.5">{instructor.title}</Text>
                    )}
                    <Box className="flex items-center gap-1.5 mt-1.5 text-xs text-slate-500">
                        <Users className="h-3.5 w-3.5 text-slate-400" />
                        <Text as="span">{instructor.students} students</Text>
                    </Box>
                </Box>
            </Box>
        </Box>
    );
}
