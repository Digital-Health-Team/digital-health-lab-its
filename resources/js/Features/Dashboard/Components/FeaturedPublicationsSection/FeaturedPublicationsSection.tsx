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
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                {publications.map((pub) => (
                    <FeaturedPublicationCard key={pub.id} publication={pub} />
                ))}
            </div>
        </section>
    );
}
