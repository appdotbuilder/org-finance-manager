<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::with(['invoices' => function ($query) {
                $query->select('id', 'customer_id', 'total_amount', 'balance_due', 'status');
            }])
            ->latest()
            ->paginate(15);

        $stats = [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::active()->count(),
            'total_outstanding' => Customer::with('invoices')
                ->get()
                ->sum('outstanding_balance'),
        ];

        return Inertia::render('customers/index', [
            'customers' => $customers,
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('customers/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->validated());

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $customer->load([
            'invoices' => function ($query) {
                $query->latest()->with('items');
            },
            'payments' => function ($query) {
                $query->latest();
            },
        ]);

        $stats = [
            'total_invoices' => $customer->invoices->count(),
            'total_invoiced' => $customer->invoices->sum('total_amount'),
            'total_paid' => $customer->invoices->sum('paid_amount'),
            'outstanding_balance' => $customer->outstanding_balance,
        ];

        return Inertia::render('customers/show', [
            'customer' => $customer,
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return Inertia::render('customers/edit', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        if ($customer->invoices()->exists()) {
            return back()->with('error', 'Cannot delete customer with existing invoices.');
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}