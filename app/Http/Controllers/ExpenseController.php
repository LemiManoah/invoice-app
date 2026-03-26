<?php

namespace App\Http\Controllers;

use App\Actions\Expense\CreateExpenseAction;
use App\Actions\Expense\UpdateExpenseAction;
use App\Actions\Expense\VoidExpenseAction;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Requests\VoidExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function __construct(
        private readonly CreateExpenseAction $createExpense,
        private readonly UpdateExpenseAction $updateExpense,
        private readonly VoidExpenseAction $voidExpense,
    ) {
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $status = $request->input('status');
        $category = $request->input('category');

        $expenses = Expense::with('category')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($expenseQuery) use ($search) {
                    $expenseQuery->where('description', 'like', "%{$search}%")
                        ->orWhere('vendor_name', 'like', "%{$search}%")
                        ->orWhere('reference_number', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($category, fn ($query) => $query->where('expense_category_id', $category))
            ->latest('expense_date')
            ->paginate(10)
            ->withQueryString();
        $categories = ExpenseCategory::where('is_active', true)->orderBy('name')->get();

        return view('expenses.index', compact('expenses', 'categories', 'search', 'status', 'category'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = ExpenseCategory::where('is_active', true)->get();

        return view('expenses.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        ($this->createExpense)($data);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense): View
    {
        $expense->load(['category', 'creator', 'voider']);

        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense): View|RedirectResponse
    {
        if ($expense->status === 'voided') {
            return redirect()->route('expenses.show', $expense)
                ->with('error', 'Voided expenses cannot be edited.');
        }

        $categories = ExpenseCategory::where('is_active', true)->get();

        return view('expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense): RedirectResponse
    {
        $data = $request->validated();
        ($this->updateExpense)($expense, $data);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function void(VoidExpenseRequest $request, Expense $expense): RedirectResponse
    {
        ($this->voidExpense)($expense, $request->validated('void_reason'));

        return back()->with('success', 'Expense voided.');
    }
}
