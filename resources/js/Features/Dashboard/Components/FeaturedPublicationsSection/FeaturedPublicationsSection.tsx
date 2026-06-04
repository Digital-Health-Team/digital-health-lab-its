import { featuredPublications } from "@/Features/Dashboard/Data/featuredPublications.data";
import FeaturedPublicationCard from "./fragments/FeaturedPublicationCard";

export default function FeaturedPublicationsSection() {
    return (
        <section aria-labelledby="explore-heading">
            <h2
                id="explore-heading"
                className="font-display text-2xl font-bold text-slate-800 text-center mb-6"
            >
                Explore Our Projects
            </h2>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                {featuredPublications.map((pub) => (
                    <FeaturedPublicationCard key={pub.id} publication={pub} />
                ))}
            </div>
        </section>
    );
}
