import { Head, usePage } from "@inertiajs/react";
import Preloader from "@/Core/Components/Shared/Preloader/Preloader";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import TrainingHero from "@/Features/Training/Components/TrainingHero/TrainingHero";
import StaffPickFeatureCard from "@/Features/Training/Components/StaffPickFeature/StaffPickFeature";
import FeaturedClasses from "@/Features/Training/Components/FeaturedClasses/FeaturedClasses";
import { type Course, type StaffPickFeature, type TrainingHero as TrainingHeroData } from "@/Features/Training/Types/course.type";

const heroData: TrainingHeroData = {
    title: "Explore Our Workshops",
    subtitle: "Learn hands-on skills from experts in medical technology and 3D printing.",
    ctaLabel: "Browse All",
    ctaHref: "/training",
    backgroundUrl: "/assets/images/training-hero.jpg",
};

interface TrainingPageProps {
    trainings: Course[];
    staffPick: StaffPickFeature | null;
    [key: string]: unknown;
}

export default function TrainingPage() {
    const { props } = usePage<TrainingPageProps>();
    const { trainings, staffPick } = props;

    return (
        <>
            <Head title="Training" />
            <Preloader />
            <DashboardLayout>
                <TrainingHero data={heroData} />
                {staffPick && <StaffPickFeatureCard data={staffPick} />}
                <FeaturedClasses courses={trainings} />
            </DashboardLayout>
        </>
    );
}
