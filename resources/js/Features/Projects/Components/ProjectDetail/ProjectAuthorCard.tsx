import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import { Text } from "@/Core/Components/Common/Text";
import { Image } from "@/Core/Components/Common/Image";
import { Avatar, Badge } from "@/Core/Components/Shared";
import { GraduationCap } from "lucide-react";
import { type ProjectAuthor } from "@/Features/Projects/Types/projectDetail.type";

interface ProjectAuthorCardProps {
    author: ProjectAuthor;
}

export default function ProjectAuthorCard({ author }: ProjectAuthorCardProps) {
    return (
        <Box className="rounded-2xl border border-slate-100 bg-white shadow-sm p-5 space-y-4">
            <Heading level={4} className="text-base font-bold text-slate-900">
                Submitted by
            </Heading>

            <Box className="flex items-center gap-4">
                {/* Avatar */}
                <Box className="relative shrink-0">
                    {author.avatarUrl ? (
                        <Image
                            src={author.avatarUrl}
                            alt={author.name}
                            className="w-16 h-16 rounded-full object-cover border-2 border-slate-100"
                        />
                    ) : (
                        <Avatar name={author.name} size="xl" />
                    )}
                </Box>

                {/* Name + meta */}
                <Box className="min-w-0">
                    <Box className="flex items-center gap-2 flex-wrap">
                        <Heading level={5} className="text-sm font-bold text-slate-900">
                            {author.name}
                        </Heading>
                        <Badge className="bg-primary-50 text-primary-700 text-xs px-2 py-0.5 rounded-full font-medium">
                            {author.nim}
                        </Badge>
                    </Box>
                    <Box className="flex items-center gap-1.5 mt-1.5 text-xs text-slate-500">
                        <GraduationCap className="h-3.5 w-3.5 text-slate-400" />
                        <Text as="span">{author.faculty}</Text>
                    </Box>
                </Box>
            </Box>
        </Box>
    );
}
