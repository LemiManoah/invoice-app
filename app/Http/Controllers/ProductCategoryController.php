<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

final readonly class ProductCategoryController implements HasMiddleware
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

        $categories = ProductCategory::query()
            ->withCount('products')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($catQuery) use ($search): void {
                    $catQuery->where('name', 'like', sprintf('%%%s%%', $search))
                        ->orWhere('description', 'like', sprintf('%%%s%%', $search));
                });
            })
            ->when($status !== null && $status !== '', function ($query) use ($status): void {
                $isActive = $status === 'active';
                $query->where('is_active', $isActive);
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('product-categories.index', compact('categories', 'search', 'status'));
    }

    public function create(): View
    {
        return view('product-categories.create');
    }

    public function store(StoreProductCategoryRequest $request): RedirectResponse
    {
        ProductCategory::create([
            'name' => $request->validated('name'),
            'description' => $request->validated('description'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return to_route('product-categories.index')->with('success', 'Product Category created successfully.');
    }

    public function show(ProductCategory $productCategory)
    {
        return to_route('product-categories.edit', $productCategory);
    }

    public function edit(ProductCategory $productCategory): View
    {
        return view('product-categories.edit', compact('productCategory'));
    }

    public function update(UpdateProductCategoryRequest $request, ProductCategory $productCategory): RedirectResponse
    {
        $productCategory->update([
            'name' => $request->validated('name'),
            'description' => $request->validated('description'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return to_route('product-categories.index')->with('success', 'Product Category updated successfully.');
    }

    public function destroy(ProductCategory $productCategory): RedirectResponse
    {
        if ($productCategory->products()->exists()) {
            return back()->with('error', 'Cannot delete this category because it is currently assigned to one or more products. Consider marking it as inactive instead.');
        }

        $productCategory->delete();

        return to_route('product-categories.index')->with('success', 'Product Category deleted successfully.');
    }
}
