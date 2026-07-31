import { Activity } from "lucide-react";
import { Card, CardHeader, CardTitle, CardBody } from "@/Core/Components/Shared";
import { type PubMedItem } from "@/Features/Dashboard/Types/pubmed.type";
import PubMedListItem from "./fragments/PubMedListItem";
import { useTranslation } from "@/Core/Hooks/useTranslation";

interface PubMedUpdatesCardProps {
    articles: PubMedItem[];
}

export default function PubMedUpdatesCard({ articles }: PubMedUpdatesCardProps) {
    const { t } = useTranslation();

    return (
        <Card className="flex flex-col">
            <CardHeader className="pb-4 border-b border-slate-100">
                <CardTitle>{t("PubMed Updates")}</CardTitle>
                <p className="text-xs text-slate-500 mt-1">
                    {t("Feature updates and other PubMed highlights")}
                </p>
            </CardHeader>
            <CardBody className="flex-1 space-y-5">
                {articles.length > 0 ? (
                    articles.map((item) => (
                        <PubMedListItem key={item.id} item={item} />
                    ))
                ) : (
                    <div className="flex flex-col items-center justify-center py-10 text-center">
                        <div className="w-14 h-14 rounded-full bg-gradient-to-br from-primary-900/10 via-secondary-500/10 to-primary-800/10 border border-primary-700/15 flex items-center justify-center mb-3">
                            <Activity className="h-6 w-6 text-secondary-500/70" />
                        </div>
                        <p className="text-sm text-slate-500 font-medium">{t("No PubMed articles available right now.")}</p>
                        <p className="text-xs text-slate-400 mt-1">{t("Check back later.")}</p>
                    </div>
                )}
            </CardBody>
        </Card>
    );
}
