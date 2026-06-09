import { Link } from "@inertiajs/react";
import { Card, CardBody, Button } from "@/Core/Components/Shared";
import { featuredPublicationsList } from "@/Features/Dashboard/Data/featuredPublicationsList.data";
import PublicationsBanner from "./fragments/PublicationsBanner";
import PublicationRowItem from "./fragments/PublicationRowItem";
import { useTranslation } from "@/Core/Hooks/useTranslation";

export default function FeaturedPublicationsList() {
    const { t } = useTranslation();

    return (
        <Card>
            <CardBody className="py-8">
                <PublicationsBanner />

                {/* Row list */}
                <div className="bg-white border border-slate-200 rounded-2xl divide-y divide-slate-100 overflow-hidden">
                    {featuredPublicationsList.map((pub) => (
                        <PublicationRowItem key={pub.id} publication={pub} />
                    ))}
                </div>

                {/* Footer CTA */}
                <div className="flex justify-center mt-8">
                    <Link href="/publications">
                        <Button variant="primary" size="lg" className="shadow-lg shadow-secondary-500/30 hover:shadow-secondary-500/50">
                            {t("See more!")}
                        </Button>
                    </Link>
                </div>
            </CardBody>
        </Card>
    );
}
