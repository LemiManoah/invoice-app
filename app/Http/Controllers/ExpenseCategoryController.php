<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use App\Http\Requests\StoreExpenseCategoryRequest;
use App\Http\Requests\UpdateExpenseCategoryRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

final readonly class ExpenseCategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:expenses.view', only: ['index']),
            new Middleware('permission:expenses.create', only: ['create', 'store']),
            new Middleware('permission:expenses.update', only: ['edit', 'update', 'destroy']),
        ];
    }

    public function index(): View
    {
        $categories = ExpenseCategory::query()
            ->withCount('expenses')
            ->orderBy('name')
            ->paginate(15);

        return view('expense-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('expense-categories.create');
    }

    public function store(StoreExpenseCategoryRequest $request): RedirectResponse
    {
        ExpenseCategory::create([
            'name' => $request->validated('name'),
            'description' => $request->validated('description'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return to_route('expense-categories.index')->with('success', 'Expense Category created successfully.');
    }

    public function show(ExpenseCategory $expenseCategory)
    {
        return to_route('expense-categories.edit', $expenseCategory);
    }

    public function edit(ExpenseCategory $expenseCategory): View
    {
        return view('expense-categories.edit', compact('expenseCategory'));
    }

    public function update(UpdateExpenseCategoryRequest $request, ExpenseCategory $expenseCategory): RedirectResponse
    {
        $expenseCategory->update([
            'name' => $request->validated('name'),
            'description' => $request->validated('description'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return to_route('expense-categories.index')->with('success', 'Expense Category updated successfully.');
    }

    public function destroy(ExpenseCategory $expenseCategory): RedirectResponse
    {
        if ($expenseCategory->expenses()->exists()) {
            return back()->with('error', 'Cannot delete this category because it is currently assigned to one or more expenses. Consider marking it as inactive instead.');
        }

        $expenseCategory->delete();

        return to_route('expense-categories.index')->with('success', 'Expense Category deleted successfully.');
    }
}
