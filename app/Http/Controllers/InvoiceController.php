<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with(['customer', 'items'])
            ->latest()
            ->paginate(15);

        $stats = [
            'total_invoices' => Invoice::count(),
            'total_amount' => Invoice::sum('total_amount'),
            'outstanding_amount' => Invoice::whereIn('status', ['issued', 'partially_paid', 'overdue'])->sum('balance_due'),
            'overdue_count' => Invoice::overdue()->count(),
        ];

        return Inertia::render('invoices/index', [
            'invoices' => $invoices,
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::active()->select('id', 'name', 'email')->get();
        $products = Product::active()->select('id', 'name', 'price', 'tax_rate', 'type')->get();

        return Inertia::render('invoices/create', [
            'customers' => $customers,
            'products' => $products,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        DB::transaction(function () use ($request) {
            // Generate invoice number
            $invoiceNumber = 'INV-' . str_pad((string)(Invoice::count() + 1), 6, '0', STR_PAD_LEFT);

            // Calculate totals
            $subtotal = 0;
            $totalTax = 0;
            $totalDiscount = 0;

            foreach ($request->items as $item) {
                $lineTotal = $item['quantity'] * $item['unit_price'];
                $taxAmount = $lineTotal * ($item['tax_rate'] ?? 0) / 100;
                $discountAmount = $lineTotal * ($item['discount_rate'] ?? 0) / 100;

                $subtotal += $lineTotal;
                $totalTax += $taxAmount;
                $totalDiscount += $discountAmount;
            }

            $totalAmount = $subtotal + $totalTax - $totalDiscount;

            // Create invoice
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $request->customer_id,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date,
                'status' => 'draft',
                'subtotal' => $subtotal,
                'tax_amount' => $totalTax,
                'discount_amount' => $totalDiscount,
                'total_amount' => $totalAmount,
                'balance_due' => $totalAmount,
                'currency' => $request->currency,
                'notes' => $request->notes,
                'is_recurring' => $request->is_recurring ?? false,
                'recurring_frequency' => $request->recurring_frequency,
                'billing_address' => $request->billing_address,
            ]);

            // Create invoice items
            foreach ($request->items as $item) {
                $lineTotal = $item['quantity'] * $item['unit_price'];
                $taxAmount = $lineTotal * ($item['tax_rate'] ?? 0) / 100;
                $discountAmount = $lineTotal * ($item['discount_rate'] ?? 0) / 100;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $lineTotal,
                    'tax_rate' => $item['tax_rate'] ?? 0,
                    'tax_amount' => $taxAmount,
                    'discount_rate' => $item['discount_rate'] ?? 0,
                    'discount_amount' => $discountAmount,
                ]);
            }
        });

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'items.product', 'paymentAllocations.payment']);

        return Inertia::render('invoices/show', [
            'invoice' => $invoice,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        $invoice->load(['items']);
        $customers = Customer::active()->select('id', 'name', 'email')->get();
        $products = Product::active()->select('id', 'name', 'price', 'tax_rate', 'type')->get();

        return Inertia::render('invoices/edit', [
            'invoice' => $invoice,
            'customers' => $customers,
            'products' => $products,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreInvoiceRequest $request, Invoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return back()->with('error', 'Only draft invoices can be edited.');
        }

        DB::transaction(function () use ($request, $invoice) {
            // Calculate totals
            $subtotal = 0;
            $totalTax = 0;
            $totalDiscount = 0;

            foreach ($request->items as $item) {
                $lineTotal = $item['quantity'] * $item['unit_price'];
                $taxAmount = $lineTotal * ($item['tax_rate'] ?? 0) / 100;
                $discountAmount = $lineTotal * ($item['discount_rate'] ?? 0) / 100;

                $subtotal += $lineTotal;
                $totalTax += $taxAmount;
                $totalDiscount += $discountAmount;
            }

            $totalAmount = $subtotal + $totalTax - $totalDiscount;

            // Update invoice
            $invoice->update([
                'customer_id' => $request->customer_id,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date,
                'subtotal' => $subtotal,
                'tax_amount' => $totalTax,
                'discount_amount' => $totalDiscount,
                'total_amount' => $totalAmount,
                'balance_due' => $totalAmount - $invoice->paid_amount,
                'currency' => $request->currency,
                'notes' => $request->notes,
                'is_recurring' => $request->is_recurring ?? false,
                'recurring_frequency' => $request->recurring_frequency,
                'billing_address' => $request->billing_address,
            ]);

            // Delete existing items and create new ones
            $invoice->items()->delete();

            foreach ($request->items as $item) {
                $lineTotal = $item['quantity'] * $item['unit_price'];
                $taxAmount = $lineTotal * ($item['tax_rate'] ?? 0) / 100;
                $discountAmount = $lineTotal * ($item['discount_rate'] ?? 0) / 100;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $lineTotal,
                    'tax_rate' => $item['tax_rate'] ?? 0,
                    'tax_amount' => $taxAmount,
                    'discount_rate' => $item['discount_rate'] ?? 0,
                    'discount_amount' => $discountAmount,
                ]);
            }
        });

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        if ($invoice->status !== 'draft') {
            return back()->with('error', 'Only draft invoices can be deleted.');
        }

        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }
}