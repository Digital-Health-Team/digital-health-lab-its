import { Head } from "@inertiajs/react";
import Preloader from "@/Core/Components/Shared/Preloader/Preloader";
import DashboardLayout from "@/Features/Dashboard/Layouts/DashboardLayout";
import TrainingHero from "@/Features/Training/Components/TrainingHero/TrainingHero";
import StaffPickFeatureCard from "@/Features/Training/Components/StaffPickFeature/StaffPickFeature";
import FeaturedClasses from "@/Features/Training/Components/FeaturedClasses/FeaturedClasses";
import { trainingHeroData } from "@/Features/Training/Data/trainingHero.data";
import { staffPickData } from "@/Features/Training/Data/staffPick.data";
import { featuredClasses } from "@/Features/Training/Data/featuredClasses.data";

export default function TrainingPage() {
    return (
        <>
            <Head title="Training" />
            <Preloader />
            <DashboardLayout>
                {/* 1. Hero Banner */}
                <TrainingHero data={trainingHeroData} />

                {/* 2. Staff Pick Feature Course */}
                <StaffPickFeatureCard data={staffPickData} />

                {/* 3. Featured Classes grid */}
                <FeaturedClasses courses={featuredClasses} />
            </DashboardLayout>
        </>
    );
}
