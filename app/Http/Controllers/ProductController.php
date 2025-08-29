<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->paginate(15);

        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::active()->count(),
            'services_count' => Product::where('type', 'service')->count(),
            'avg_price' => Product::active()->avg('price'),
        ];

        return Inertia::render('products/index', [
            'products' => $products,
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('products/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        return redirect()->route('products.show', $product)
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['invoiceItems.invoice']);

        $stats = [
            'times_sold' => $product->invoiceItems->count(),
            'total_revenue' => $product->invoiceItems->sum('line_total'),
            'avg_quantity' => $product->invoiceItems->avg('quantity'),
        ];

        return Inertia::render('products/show', [
            'product' => $product,
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return Inertia::render('products/edit', [
            'product' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('products.show', $product)
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->invoiceItems()->exists()) {
            return back()->with('error', 'Cannot delete product that has been used in invoices.');
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}