import { Box } from "@/Core/Components/Common/Box";
import { Heading } from "@/Core/Components/Common/Heading";
import CourseCard from "./fragments/CourseCard";
import { type Course } from "@/Features/Training/Types/course.type";

interface FeaturedClassesProps {
    courses: Course[];
}

export default function FeaturedClasses({ courses }: FeaturedClassesProps) {
    return (
        <Box as="section">
            <Heading
                level={3}
                className="font-display text-2xl font-bold text-slate-800 mb-6"
            >
                Featured Classes
            </Heading>
            <Box className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {courses.map((course) => (
                    <CourseCard key={course.id} course={course} />
                ))}
            </Box>
        </Box>
    );
}
