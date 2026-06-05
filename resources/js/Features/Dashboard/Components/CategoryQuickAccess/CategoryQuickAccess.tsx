import { usePage } from "@inertiajs/react";
import { Card, CardBody } from "@/Core/Components/Shared";
import { categories } from "@/Features/Dashboard/Data/categories.data";
import CategoryTile from "./fragments/CategoryTile";

export default function CategoryQuickAccess() {
    const { url } = usePage();

    return (
        <Card>
            <CardBody className="py-5">
                {/* Horizontal scroll on mobile, auto-fit grid on lg+ */}
                <div className="-mx-1 overflow-x-auto pb-1 scrollbar-thin">
                    <div className="flex gap-4 px-1 min-w-max lg:min-w-0 lg:grid lg:grid-cols-10">
                        {categories.map((cat) => (
                            <CategoryTile
                                key={cat.id}
                                category={cat}
                                active={url.startsWith(cat.href.split("?")[0])}
                            />
                        ))}
                    </div>
                </div>
            </CardBody>
        </Card>
    );
}
