import { usePage } from "@inertiajs/react";
import { categories } from "@/Features/Dashboard/Data/categories.data";
import CategoryTile from "./fragments/CategoryTile";

export default function CategoryQuickAccess() {
    const { url } = usePage();

    return (
        <div>
            <h2 className="font-display text-2xl font-bold text-slate-800 mb-4 text-center">Our Catalogue</h2>
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
        </div>
    );
}
