// TODO(v2): wire to App\Actions\Articles\FetchPubMedFeedAction (60-min cached RSS feed)
import { Card, CardHeader, CardTitle, CardBody } from "@/Core/Components/Shared";
import { pubmedUpdates } from "@/Features/Dashboard/Data/pubmedUpdates.data";
import PubMedListItem from "./fragments/PubMedListItem";
import { useTranslation } from "@/Core/Hooks/useTranslation";

export default function PubMedUpdatesCard() {
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
                {pubmedUpdates.map((item) => (
                    <PubMedListItem key={item.id} item={item} />
                ))}
            </CardBody>
        </Card>
    );
}
