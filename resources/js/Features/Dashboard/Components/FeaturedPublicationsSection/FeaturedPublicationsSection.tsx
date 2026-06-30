import FeaturedPublicationCard from "./fragments/FeaturedPublicationCard";
import { type FeaturedPublication } from "@/Features/Dashboard/Types/publication.type";
import { useTranslation } from "@/Core/Hooks/useTranslation";

interface FeaturedPublicationsSectionProps {
    publications: FeaturedPublication[];
}

export default function FeaturedPublicationsSection({ publications }: FeaturedPublicationsSectionProps) {
    const { t } = useTranslation();

    return (
        <section aria-labelledby="explore-heading">
            <h2
                id="explore-heading"
                className="font-display text-2xl font-bold text-slate-800 text-center mb-6"
            >
                {t("Explore Our Projects")}
            </h2>
            {/* Horizontal scroll rail — 4-5 portrait posters visible */}
            <div className="flex gap-4 overflow-x-auto scrollbar-none snap-x snap-mandatory pb-3 -mx-1 px-1">
                {publications.map((pub) => (
                    <FeaturedPublicationCard key={pub.id} publication={pub} />
                ))}
            </div>
        </section>
    );
}
