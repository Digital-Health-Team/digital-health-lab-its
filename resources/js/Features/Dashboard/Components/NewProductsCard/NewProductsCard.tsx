import { useState } from "react";
import { Card, CardHeader, CardTitle, CardBody, PillToggle } from "@/Core/Components/Shared";
import { type Product, type Service } from "@/Features/Dashboard/Types/product.type";
import ProductCard from "./fragments/ProductCard";
import { useTranslation } from "@/Core/Hooks/useTranslation";

type Tab = "PRODUCTS" | "SERVICES";

interface NewProductsCardProps {
    products: Product[];
    services: Service[];
}

export default function NewProductsCard({ products, services }: NewProductsCardProps) {
    const [tab, setTab] = useState<Tab>("PRODUCTS");
    const { t } = useTranslation();

    const tabOptions: [{ value: string; label: string }, { value: string; label: string }] = [
        { value: "PRODUCTS", label: t("PRODUCTS") },
        { value: "SERVICES", label: t("SERVICES") },
    ];

    const items = tab === "PRODUCTS" ? products : services;

    return (
        <Card>
            <CardHeader className="flex flex-row items-center justify-between gap-4 pb-4">
                <CardTitle>{t("New Products")}</CardTitle>
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
