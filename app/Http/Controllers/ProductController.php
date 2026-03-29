<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

final readonly class ProductController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:products.view', only: ['index']),
            new Middleware('permission:products.create', only: ['create', 'store']),
            new Middleware('permission:products.update', only: ['edit', 'update', 'destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $status = $request->query('status');
        $category = $request->query('category');

        $products = Product::query()
            ->with('category')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($productQuery) use ($search): void {
                    $productQuery->where('name', 'like', sprintf('%%%s%%', $search))
                        ->orWhere('description', 'like', sprintf('%%%s%%', $search));
                });
            })
            ->when($status !== null && $status !== '', function ($query) use ($status): void {
                $isActive = $status === 'active';
                $query->where('is_active', $isActive);
            })
            ->when($category !== null && $category !== '', fn ($query) => $query->where('product_category_id', $category))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $categories = ProductCategory::query()->where('is_active', true)->orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'search', 'status', 'category'));
    }

    public function create(): View
    {
        $categories = ProductCategory::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::create([
            'product_category_id' => $request->validated('product_category_id'),
            'name' => $request->validated('name'),
            'description' => $request->validated('description'),
            'base_price' => $request->validated('base_price'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return to_route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return to_route('products.edit', $product);
    }

    public function edit(Product $product): View
    {
        $categories = ProductCategory::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update([
            'product_category_id' => $request->validated('product_category_id'),
            'name' => $request->validated('name'),
            'description' => $request->validated('description'),
            'base_price' => $request->validated('base_price'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return to_route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return to_route('products.index')->with('success', 'Product deleted successfully.');
    }
}
