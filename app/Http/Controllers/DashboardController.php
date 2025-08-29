<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Overview statistics
        $stats = [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::active()->count(),
            'total_products' => Product::active()->count(),
            'total_invoices' => Invoice::count(),
            'draft_invoices' => Invoice::where('status', 'draft')->count(),
            'outstanding_invoices' => Invoice::whereIn('status', ['issued', 'partially_paid', 'overdue'])->count(),
            'overdue_invoices' => Invoice::overdue()->count(),
            'total_revenue' => Invoice::where('status', 'fully_paid')->sum('total_amount'),
            'outstanding_amount' => Invoice::whereIn('status', ['issued', 'partially_paid', 'overdue'])->sum('balance_due'),
            'payments_received' => Payment::completed()->sum('amount'),
        ];

        // Recent activities
        $recent_invoices = Invoice::with('customer')
            ->latest()
            ->limit(5)
            ->get();

        $recent_payments = Payment::with('customer')
            ->completed()
            ->latest('payment_date')
            ->limit(5)
            ->get();

        // Overdue invoices
        $overdue_invoices = Invoice::with('customer')
            ->overdue()
            ->orderBy('due_date')
            ->limit(10)
            ->get();

        // Monthly revenue chart data
        $monthly_revenue = Invoice::selectRaw('strftime("%m", created_at) as month, SUM(total_amount) as revenue')
            ->where('created_at', '>=', now()->subYear())
            ->where('status', '!=', 'draft')
            ->groupByRaw('strftime("%m", created_at)')
            ->orderByRaw('strftime("%m", created_at)')
            ->get()
            ->mapWithKeys(function ($item) {
                $monthNum = (int) $item->getAttribute('month');
                $revenue = (float) $item->getAttribute('revenue');
                return [date('M', mktime(0, 0, 0, $monthNum, 1)) => $revenue];
            });

        // Payment method breakdown
        $payment_methods = Payment::selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->completed()
            ->groupBy('payment_method')
            ->get();

        return Inertia::render('dashboard', [
            'stats' => $stats,
            'recent_invoices' => $recent_invoices,
            'recent_payments' => $recent_payments,
            'overdue_invoices' => $overdue_invoices,
            'monthly_revenue' => $monthly_revenue,
            'payment_methods' => $payment_methods,
        ]);
    }
}