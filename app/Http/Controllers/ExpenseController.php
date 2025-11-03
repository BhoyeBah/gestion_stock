<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $expenses = Expense::paginate(10);
        $total = Expense::sum('amount');
        $today = Expense::where('created_at', '>=', now()->startOfDay())->sum('amount');
        $thisWeek = Expense::where('created_at', '>=', now()->startOfWeek())->sum('amount');
        $thisMonth = Expense::where('created_at', '>=', now()->startOfMonth())->sum('amount');

        return view('back.expenses.index', compact('expenses', 'total', 'today', 'thisWeek', 'thisMonth'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request)
    {
        //
        $expense = Expense::create([
            'reason' => $request->reason,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
        ]);

        return back()->with('success', 'Dépense enrégistrée avec success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        //
        return view('back.expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
        //

        $expense->update($request->validated());

        return redirect()->route('expenses.index')->with('success', 'Dépense mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        //
        $expense->delete();

        return back()->with('success', 'Dépense supprimée avec succès.');
    }
}
