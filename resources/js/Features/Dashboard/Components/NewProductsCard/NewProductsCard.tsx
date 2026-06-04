import { useState } from "react";
import { Card, CardHeader, CardTitle, CardBody, PillToggle } from "@/Core/Components/Shared";
import { newProducts, newServices } from "@/Features/Dashboard/Data/newProducts.data";
import ProductCard from "./fragments/ProductCard";

type Tab = "PRODUCTS" | "SERVICES";

const tabOptions: [{ value: string; label: string }, { value: string; label: string }] = [
    { value: "PRODUCTS", label: "PRODUCTS" },
    { value: "SERVICES", label: "SERVICES" },
];

export default function NewProductsCard() {
    const [tab, setTab] = useState<Tab>("PRODUCTS");
    const items = tab === "PRODUCTS" ? newProducts : newServices;

    return (
        <Card>
            <CardHeader className="flex flex-row items-center justify-between gap-4 pb-4">
                <CardTitle>New Products</CardTitle>
                <PillToggle
                    options={tabOptions}
                    value={tab}
                    onChange={(v) => setTab(v as Tab)}
                />
            </CardHeader>
            <CardBody className="pt-0">
                <div className="grid grid-cols-2 xl:grid-cols-3 gap-3">
                    {items.map((item) => (
                        <ProductCard key={item.id} item={item} />
                    ))}
                </div>
            </CardBody>
        </Card>
    );
}
