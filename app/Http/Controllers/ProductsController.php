<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class ProductsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Features/Products/Pages/ProductsPage');
    }

    public function show(string $product): Response
    {
        return Inertia::render('Features/Products/Pages/ProductDetailPage', [
            'productId' => $product,
        ]);
    }
}
